 var initEvent = function() {
      $('.votebtn').click(function(e){
            e.preventDefault();
            if ($(this).attr('disabled'))
                return;

            var id = $(this).parents('.feature-row').attr('data-id'); 
            var x,y; 
            x = $(this).position().left;
            y = $(this).position().top;
            if ($('.IdeaVoteForm').is(':visible')) {
              $('.IdeaVoteForm').fadeOut();
            }
            $('#feature-' +  id  +' .IdeaVoteForm')
                    .css({'left' : x+10,'top': y+11})
                    .fadeIn();
        });

        $('#featuresContainer .votebtn-value').click(function(e){
            var value = $(this).attr('value');
            var id = $(this).parents('.feature-row').attr('data-id');
            $('#feature-' + id + ' .IdeaVoteForm button').each(function(){
               if ($(this).hasClass('btn-primary')) {
                  $(this).removeClass('btn-primary');
                }
            });
            if (value != 0) {
                $('#feature-'+ id +' .votebtn').text(value)
                           .addClass('btn-primary');
            }else {
              $('#feature-'+ id + ' .votebtn').text('Vote')
                           .removeClass('btn-primary');
            }

             $(this).addClass('btn-primary');
             <?php if (!isset($is_user_logged_in)): ?>
                  if ($('#feature-'+ id + ' #form-vote .alert').length === 0) {
                    $(this).parent().prepend("<p class=\"alert alert-info\"><?php echo lang('please_login'); ?><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>");
                     $('#feature-'+ id + ' .votebtn').text('Vote')
                           .removeClass('btn-primary');
                  }
             <?php else: ?>
            
            $.ajax({
                url:'<?php echo site_url() ?>/ratings/vote', 
                type:'POST',
                dataType: 'JSON', 
                data: {
                  'id'   : id,
                  'value' : value
                },
                beforeSend:function(e) {
                  $('#feature-' + id + ' #form-vote .votebuttons button:last-child').after('<div class="ajax-loader"></div>');
                }, 
                success:function(e) {
                    $('#feature-'+id +' .votes span').text(e.suggestion_votes);
                    if($('#feature-'+ id +' #removebtn').filter(':visible').length === 0) {
                        $('#feature-'+ id +' #removebtn').slideDown();
                    }
                    else {
                      if(value == 0) {
                        $('#feature-'+ id +' #removebtn').slideUp();
                      }
                    }
                    if ($('.votesremaining').length) {
                      if (e.user_left_votes) {
                        $('.votesremaining').text("<?php echo lang("you_have"); ?> " + e.user_left_votes + " <?php echo lang("votes_left"); ?>");
                      }

                      if(e.msg) {
                        $('.votesremaining').text(e.msg);
                      }
                      
                    }

                    $(this).parents('.IdeaVoteForm').hide();
                    
                },
                complete:function(e) {
                  $('.ajax-loader').hide()
                                   .remove();
                }
            });

            
            <?php endif  ?> 
            e.preventDefault();
        });


            $('.close').click(function(e){
               $(this).parent().fadeOut();
               e.preventDefault();
           });

    }


initEvent(); 