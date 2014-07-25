
<?php if (isset($results) && !empty($results)): ?>
	<?php $results = json_decode(json_encode($results), true);?>
	<?php $this->load->helper('util'); ?>
	<?php $this->load->helper('account'); ?>
	<?php foreach ($results as $row): ?>
		<div class="comment">
			<span>By <?php echo  $this->auth->get_user_name($row['UserID']); ?> <?php echo date("F d, Y H:i",strtotime($row['AddedOn'])); ?> </span>
			<p><?php echo $row['Comment']; ?></p>
		<?php if (isset($is_user_logged_in)): ?>
			<div id="actions">
				<ul>
					<!-- <li><a href="<?php echo site_url(); ?>/comments/reply_comment">Reply</a></li> -->
				<?php if (is_admin_connected()): ?>
					<li><a href="<?php echo site_url() ?>/admin/dashboard/delete_comment/<?php echo $row['ID']; ?>" onclick="return confirm("<?php echo lang('delete_comment_message'); ?>");"><?php echo lang('delete'); ?></a></li>
				<?php endif ?>
				</ul>
			</div>
		<?php endif ?>
		</div>
	<?php endforeach?>
<?php endif ?>





<?php if (isset($new_comment_form) && $new_comment_form == TRUE): ?>
	<?php $this->load->view('new_comment'); ?>
<?php endif ?>

<script type="text/javascript">
$(document).ready(function(e){
    $('#comments .comment p').mouseenter(function(e){
              $(this)
                    .next('div#actions')
                    .children()
                    .children()
                    .css({'display' : 'inline-block'})
                    .fadeIn(); 
       });

    $('#comments .comment').mouseleave(function(e){
              $(this)
                    .find('div#actions')
                    .children()
                    .children()
                    .delay(500)
                    .fadeOut(); 
       });
  });

$('#links a').on('click',function(e){
           var link = $(this).attr('href'); 
            $.ajax({
                  url:link,
                  type:'POST',
                  dataType:'JSON',
                  data:{
                    'id' : $('.feature-row').attr('data-id')
                  },
                  beforeSend:function(e) {
			            $('#feature-' + $('.feature-row')
			                    .attr('data-id'))
			                    .after('<div class="ajax-loader-comments"></div>');
           		  },
                  success:function(e) {

	                    $('#comments .comment').remove();
	                    $('#comments #links').remove();
	                    $('#comments script').remove();
	                    $('#comments').prepend(e.html);
                    },
                    complete:function(e) {
			            $('.ajax-loader-comments')
			                    .fadeOut()
			                    .remove();
			         }
                    

                })
                    e.preventDefault();
     });
</script>

