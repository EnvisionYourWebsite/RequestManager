
	function cancel() {
		$('#newidea .text_cancel, #newidea .cancel').remove();
		$('#newidea textarea, #newidea button, #newidea select, #newidea input[type="email"]').fadeOut();

		$('#filters').after('<div id="createidea"><a href="#">Vote for an existing idea</a> Or <a onclick="newidea_event();" class="btn btn-xs btn-primary" style="margin-left:5px;" href="#">Post a new idea</a></div>');
		$('#filters').fadeOut();
	}

	function newidea_event() {
		$('#createidea').remove();
		$('#filters').fadeIn();
		$('#newidea textarea, #newidea button').fadeIn();
	}

$(document).ready(function(){

	
	function hide_controls() {
		if (anonymous) {
			$('#newidea input[type="email"]').removeAttr('required');
			$('#newidea input[type="password"]').removeAttr('required');
		}

		
		$('#newidea textarea,#newidea input[type="email"],#newidea button[type="submit"],#newidea select,#newidea input[type="password"]').hide();
		$('#newidea .text_cancel').fadeOut();
		$('#newidea .cancel').fadeOut();
		$('#newidea .votebuttons').hide(); 
	}

	function show_controls() {
		$('#newidea textarea,#newidea .votebuttons, #newidea select').fadeIn();
	}

	function get_anonymous_login_url() {
		return 	$('#baseUrl').val() + 'login/log_as_anonymous';
	}

	var hasChanged = false;
	function reset_search(element) {
		$.ajax({
			type:'POST',
			dataType:'JSON',
			url: $('#baseUrl').val() + 'home/index',
			async: true,
			beforeSend: function() {
				$(element).addClass('ajax-searching');
			},
			complete: function() {
				hasChanged = false;
			},
			success:function(e) {
			$(element).removeClass('ajax-searching');
				$("#features").fadeOut()
					.remove();

				$('#featuresContainer .noideas').remove();
				$('#featuresContainer').prepend(e.html);
				}
			})
	}

	// Hide Controls except suggestion input
	hide_controls();
	$('#newidea input[type="text"]').bind('input', function(){
		var element = this;
		$('#newidea .alert').fadeOut()
							.remove();

		if(connected){
			$('#newidea textarea, #newidea button, #newidea select, #newidea .votebuttons').fadeIn();
		}	
		else
		{
			$('#newidea input[type="email"]').fadeIn();
			if (anonymous) {
				$('#newidea textarea, #newidea select, #newidea .votebuttons, #newidea button').fadeIn();
			}
		}		

		$('#createidea').remove();

		if (connected) {
			$('#filters').fadeIn();	
		}
			

		if(connected || anonymous){
			if($(this).val().length >=3){
				function DoSuccess(e,element) {
					$(element).removeClass('ajax-searching');
					$('#msg').text('Add new suggestion');
					if (e.status === 1){
						$('#features').fadeOut()
									  .remove();
						$('#featuresContainer').prepend(e.html)
											   .addClass('search-result');
						if ($('#newidea .cancel').length === 0){
							$('#newidea button.btn-submit').after('<p class="text_cancel">Or</p>');
							$('#newidea .text_cancel').after('<a href="#" class="cancel" onclick="cancel();">Cancel</a>');
						}

						initEvent();
					}
					else
					{
						$('#newidea textarea').fadeIn();
					}
				}

				$.ajax({
					type:'POST',
					dataType:'JSON',
					url:$('#baseUrl').val() + 'search/search_for',
					data: { keyword: $(this).val() },
					async: true,
					beforeSend:function(){
						$(element).addClass('ajax-searching');
					},
					success:function(e) {
						hasChanged = true;
						DoSuccess(e,element);
					}
				})

			}

			if($(element).val().length === 0 && $('#featuresContainer').hasClass('search-result')) {
				reset_search(element);
			}
		}	

	});
	
	$('#newidea .mail').change(function(e){
			if ($(this).val() === '') {
				$('#newidea button[type="submit"]').fadeOut();
				if (('#newidea .alert').length) {
					$('#newidea .alert')
						.fadeOut()
						.remove();					
				}
			}

			if ($('.alert').length) {
				$('.alert')
					.fadeOut()
					.remove();
			}

			$.ajax({
				type:'POST', 
				dataType:'JSON', 
				url:$('#baseUrl').val() + 'login/is_email_exists', 
				data: { 'email' : $(this).val() },
				beforeSend:function() {
					$('#newidea .mail').addClass('ajax-loader');
				},
				complete:function() {
					$('#newidea .mail').removeClass('ajax-loader');
				},
				success:function(e) {
					if (e.status === 1 || e.status === 0) {
						$('#newidea input[type="password"]').fadeIn();
						$('#newidea input[type="password"]').keydown(function(e){
							if( $(this).val().length > 3) {
								$('#newidea button[type="submit"]').fadeIn();
							}
							else 
							{
								$('#newidea button[type="submit"]').fadeOut();
							}
							}).change(function(e){
								if ($(this).val().length < 3) {
									$('#newidea button[type="submit"]').fadeOut();
								}	
							});								
							
							if ($('#newidea input[type="password"]').val() === '') {
								$('#newidea button[type="submit"]').fadeOut();
							}
					}

					if (e.status === 1) {
						$('#newidea').attr('action',$('#baseUrl').val() + 'login/authenticate_ajax');
						$('#newidea .alert').hide(); 
						$('#newidea input[type="text"]').before("<p class=\"alert alert-info\"><strong>" + e.msg +  "</strong><button type=\"button\" class=\"close\ data-dismiss=\"alert\">&times;</button></p>")
													.prev()
													.hide()
													.fadeIn();
						$('#newidea').attr('data-action','login');
					}
					else if (e.status === 0) {
						$('#newidea button[type="submit"]').hide(); 

						if (e.valid_email === 0) {
							// Invalid mail
							$('#newidea input[type="text"]').before('<p class="alert alert-danger">'+e.msg+'<button type="button" class="close" data-dismiss="alert">&times;</button></p>')
														    .prev()
														    .hide()
														    .fadeIn();
						}
						else {
							if (e.registrationAllow === 0) {
								$('#newidea input[type="text"]').before("<p class=\"alert alert-info\">"+e.msg+" <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>")
											.prev()
											.hide()
											.fadeIn();

								$('#newidea textarea').fadeOut();
								$('#newidea button[type="submit"]').fadeOut();
								$('#newidea input[type="password"]').fadeOut(); 
								$('#newidea input[type="email"]').fadeOut();
							}
							else
							{
								$('#newidea input[type="text"]').before("<p class=\"alert alert-info\"><strong>"+e.msg+"</strong><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>")
												.prev()
												.hide()
												.fadeIn();	
							}
						}

						$('#newidea').attr('action',$('#baseUrl').val() + 'register/new_member_ajax'); 
						$('#newidea').attr('data-action','register');
					}
				}
			});

		});

$('#newidea input[type="text"]').focusout(function(){
	if ($(this).val() === '') {
		//$('#newidea textarea,#newidea input[type="email"],#newidea select,#newidea .votebuttons').fadeOut();
		hide_controls();
			if ($('#newidea input[type="password"]').length) {
				$('#newidea input[type="password"]').fadeOut();
				$('#newidea .text_cancel, #newidea .cancel').remove();
			}

			// Reset Result
			if (hasChanged) {
				reset_search($(this));
			}
	}
					
});

function onloggeedIn(){

		$('#newidea input[type="text"]').focusout(function(){
				if ($(this).val() == '') {
					$('#newidea textarea,#newidea button[type="submit"],#newidea select').fadeOut();
					$('#newidea .text_cancel, #newidea .cancel').remove();
					$('#newidea .votebuttons').fadeOut();
				}
				else
				{
					$('#newidea textarea,#newidea button[type="submit"],#newidea select').fadeIn();
				}
		});

		$('#newidea').attr('data-action','suggest');

}


$('#newidea .votebtn-value').click(function(e){
    var value = $(this).attr('value');
 	$($(this).parent().children()).each(function(){
 		if ($(this).hasClass('btn-primary')){
 			$(this).removeClass('btn-primary');
 		}
 	});
 	$(this).addClass('btn-primary');
});

$('#newidea').submit(function(e){
	e.preventDefault();
	

	data = $(this).serialize();

	if ($(this).attr('data-action') != 'register' && $(this).attr('data-action') != 'login')

	if ($('#newidea input[type="email"]').text() != "" ) {
		if (!connected) 
		{
			data = {
				'suggestion' : $(this).children('.suggestion').val(),
				'description': $(this).children('textarea').val(),
				'vote'		 : $(this).find('.votebuttons .btn-primary').val(),
				'category'   : $(this).find('select').val()
			};
			console.log('here');
		}
		else 
		{
			$(this).attr('action',$('#baseUrl').val() + 'suggest/add_new_suggestion');
			console.log('here 1 ');
		}
	}
	else
	{
		if (!connected) {
			if (anonymous) {
				$(this).attr('action',get_anonymous_login_url());
				$('#newidea input[type="email"]').hide();
			}
		}
		else
		{
			$(this).attr('action',$('#baseUrl').val() + 'suggest/add_new_suggestion');
			data = {
				'suggestion' : $(this).children('.suggestion').val(),
				'description': $(this).children('textarea').val(),
				'vote'		 : $(this).find('.votebuttons .btn-primary').val(),
				'category'   : $(this).find('select').val()
			};
		}

		console.log('here 5');
	}

	var base = this;
	$.ajax({
			type:'POST', 
			dataType:'JSON', 
			url:$(this).attr('action'), 
			data: data,
			success:function(e){
				$('.alert').remove(); // Remove last alert
				if (e.msg) { // Check if Request has a message
					$('#newidea .alert').slideUp();
					$('#newidea input[type="text"]').before(e.msg)
													.prev()
													.hide()
													.fadeIn();

					if (!$('.feature-row').length) {
						$('#featuresContainer .noideas').remove();
						$('.row .span12')
							.prepend(e.html)
							.find('.feature-row')
							.hide()
							.fadeIn();
					}
					else 
					{
						if ($('#links').length>0) {
							$('#links').before(e.html)
											.prev()
											.hide()
											.fadeIn();
						}
						else {
						$('.row .span12 .feature-row:last-child')
								.after(e.html)
								.next()
								.hide()
								.fadeIn();
						}
					}	

						$('.votebtn').unbind('click'); 
						$('.votebtn-value').unbind('click');
						initEvent(); 

						$(this).find('.votebuttons .btn-primary').removeClass('btn-primary');
					}

				if (e.status === "registrationSuccess") {
						$('#newidea input[type="email"],#newidea input[type="password"]')
																	.fadeOut()
																	.remove();
						$('#newidea').attr('action',$('#baseUrl').val() + 'suggest/add_new_suggestion');
						if (e.loggedin === 1) {
							$('.nav')
								.fadeOut()
								.remove(); 

							$('.masthead').prepend(e.html)
									.children('.nav')
							    	.hide()
						        	.fadeIn(); 

						    show_controls();
						    connected = true;
						    onloggeedIn();

						}
					}
				else if (e.status === "logged_in") {
						$('.mail,#newidea input[type="password"]')
												.fadeOut()
												.remove(); 
						$('.nav')
								.fadeOut()
								.remove(); 
						
						 show_controls();
						 connected = true;
						 onloggeedIn();

						$('.masthead').prepend(e.html)
								.children('.nav')
							    .hide()
						        .fadeIn(); 
						$('#newidea').attr('action',$('#baseUrl').val() + 'suggest/add_new_suggestion');

						$('#newidea .votebuttons button').bind('click',function(){
							$('#newidea .votebuttons button').each(function(){
					            if ($(this).hasClass('btn-primary')) {
					                $(this).removeClass('btn-primary');
					            }
					       	});

					       	$(this).addClass('btn-primary');
						});
				}
				else if (e.status === "needactivation") 
				{
					$('#newidea').attr('action',$('#baseUrl').val() + 'login/authenticate_ajax');
				}
				else if (e.status === 1)
				{
					$('#newidea textarea,#newidea select').fadeOut();
					$('#newidea button[type="submit"]').fadeOut();
					$('#newidea input[type="text"]').val('');
					$('#newidea textarea').val('');
					$('#newidea .text_cancel, #newidea .cancel').remove();
					$('#newidea input[type="password"]').fadeOut();
					$('#newidea textarea, #newidea button').fadeOut();
					$('#newidea .votebuttons button').each(function(){
					if ($(this).hasClass('btn-primary'))
						$(this).removeClass('btn-primary');
					});

					initEvent();
				
				}
				else if (e.status === 2) {
					$(base).attr('action',$('#baseUrl').val() + 'suggest/add_new_suggestion');
					connected = true;
					$(base).submit();
				}
					$('button.close').fadeIn();
				}
				
			});			
		});
});