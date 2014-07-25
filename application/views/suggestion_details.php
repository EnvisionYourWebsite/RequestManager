<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete_modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete a suggestion!</h4>
        </div>
        <div class="modal-body">
          <p><?php echo lang("delete_sugg_message"); ?></p>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang("close"); ?></a>
          <a id="link" href="#" class="btn btn-sm btn-danger"><?php echo lang("remove"); ?></a>
        </div>
      </div>
    </div>
</div>
<div class="container-narrow">
      <div class="masthead">
       <?php $this->load->view('fragment/main_nav_bar.php'); ?>
        <h3 class="muted"><?php echo lang("Home_Title") ?></h3>
      </div>
      <hr>
      <?php if (isset($empty)):?>
          <?php redirect('/home'); ?>
      <?php endif ?>
      <div class="jumbotron">
      	 <div class="feature-row" id="feature-<?php echo $results->ID; ?>" data-id="<?php echo $results->ID; ?>">
              <div class="votes">
                <div class="subvotes">
                   <span><?php echo $results->votes; ?></span>
                    votes
                </div>
                <?php $this->load->helper('suggestion'); ?>
                <?php $this->load->helper('account'); ?>
                <?php if (count_user_votes_for_suggestion(get_connected_user_id(),$results->ID) == FALSE): ?>
                  <?php if (get_suggestion_status($results->ID) >= 2): ?>
                    <a class="btn btn-default votebtn" href="#" disabled>Vote</a>
                  <?php else: ?>
                    <a class="btn btn-default votebtn" href="#">Vote</a>
                  <?php endif ?>
                <?php else: ?>
                    <a class="btn btn btn-default votebtn btn-primary" href="#"><?php echo count_user_votes_for_suggestion(get_connected_user_id(),$results->ID); ?></a>
                <?php endif ?>
              </div>
                <div class="content">
                  <h4><a href="<?php echo site_url() ?>/suggestion/details/<?php echo $results->Slug; ?>"><?php echo $results->Title ?></a></h4>
                    <div class="authorinfo">
                      <span>By <a href="#"><?php echo get_author_byId($results->UserID); ?></a></span>
                      <span><?php echo date("F d, Y H:i",strtotime($results->CreatedOn)); ?>
                      <?php if ($results->category_id != NULL): ?>
                      In <a href="<?php echo site_url() ?>/home/filter/category/<?php echo $this->category_model->get_category_slug($results->category_id); ?>">#<?php echo $this->category_model->get_category_name($results->category_id); ?></a>
                      <?php endif ?>
                      </span>
                      <span><a href="#"><?php echo count_suggestion_comment($results->ID); ?> Comments</a></span>
                      <?php $status = get_suggestion_status($results->ID); ?>
                    </div>
                  <p><?php echo $results->Description; ?></p>
                  <?php if (isset($isAdmin)): ?>
                  <form id="statusform" data-id="<?php echo $results->ID; ?>" action="<?php echo site_url() ?>/admin/dashboard/set_status/<?php echo $results->ID; ?>" method="post">
                    <fieldset>
                    <?php if (isset($categories)): ?>
                        <select>
                        <option name="category" value="empty"></option>
                      <?php foreach ($categories as $category): ?>
                        <option name="category" value="<?php echo $category->category_slug; ?>"><?php echo $category->category_name; ?></option>
                      <?php endforeach ?>
                        </select>
                    <?php endif ?>
                    <select style="margin-top:10px;" id="select_status" name="status">
                      <option data-status="pending"   value="pending"><?php echo lang('pending'); ?></option>
                      <option data-status="planned"   value="planned"><?php echo lang('planned'); ?></option>
                      <option data-status="started"   value="started"><?php echo lang('started'); ?></option>
                      <option data-status="completed" value="completed"><?php echo lang('completed'); ?></option>
                      <option data-status="declined"  value="declined"><?php echo lang('declined'); ?></option>
                    </select>
                    <button class="btnsave btn-sm btn btn-primary"><?php echo lang('save_btn'); ?></button>
                    <a class="btn btn-sm btn-danger delete" href="#delete-modal" data-toggle="modal" data-link="<?php echo site_url() ?>/admin/dashboard/remove/<?php echo $results->Slug; ?>"><?php echo lang('remove'); ?></a>
                    </fieldset>
                  </form>
                  <?php endif ?>
                  <div class="bar-container">
                    <div class="status-on">
                      <?php echo _ui_status($status); ?>
                       <p> 
                         <?php if($results->status_updated_on != null) { echo date("F d, Y",strtotime($results->status_updated_on)); } ?>
                        </p>
                    </div>
                    <div class="social" style="display:none;">
                      <a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
                      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                      <div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
                    </div>
                  </div>
                  </div>
              <div class="IdeaVoteForm" style="display:none;">
                  <button type="button" class="close close-btn">&times;</button>
                  <fieldset class="IdeaVoteFormFieldset">
                    <legend>Vote</legend>
                    <form id="form-vote" method="post" action="<?php echo site_url() ?>/ratings/vote">
                    <div class="votebuttons">
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="1" name="vote">1 <?php echo lang('vote'); ?></button>
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="2" name="vote">2 <?php echo lang('votes');?></button>
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="3" name="vote">3 <?php echo lang('votes');?></button>  
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="0" name="vote"><?php echo lang('remove_votes'); ?></button> 
                      <div class="VotesButtonsMeta">
                        <span class="votesremaining"><?php //echo $results->votesRemaining; ?></span>
                      </div>  
                    </div>
                    </form>
                  </fieldset>
              </div>
        </div>  
        <hr>
        <br>
        <div id="comments">
           <?php if (isset($is_user_logged_in)): ?>
              <?php $this->load->view('fragment/new_comment.php'); ?>           
           <?php endif ?>
      </div> 
</div>
     <script type="text/javascript">

       $('#commentform').submit(function(e){
        var content = $('#comments #content').val();
          e.preventDefault();
          if (content === '') {
            return;
          }
            
          $.ajax({
            url:$(this).attr('action'), 
            type:'POST', 
            dataType:'JSON', 
            data: {
              'id' : $('.feature-row').attr('data-id'), 
              'content': content
            },
            success:function(e) {
                  $('#comments .alert').remove();
                  if (e.status === 1) {
                      if ($('#comments .comment').length) {
                        $('#comments .comment:last').after(e.html);
                      }
                      else {
                        $('.newComment').before(e.html);
                      }
                      
                      $('#commentform #content').val('');
                           
                  }
                  else if (e.status === 0) {
                    if (e.msg) {
                      $('#comments').prepend(e.msg);
                    }
                  }
            }
          })
          
       });

       $(document).ready(function(e){

      $('.delete').click(function(e){
        var uri = $(this).attr('data-link'); 
        $('#link').attr('href',uri);
      });
          $.ajax({
            url:'<?php echo site_url() ?>/comments/get_comments', 
            type:'POST',
            dataType:'JSON',
            data: {
              'id': $('.feature-row').attr('data-id')
            },
            beforeSend:function(e) {
                $('#comments').prepend('<div class="ajax-loader-comments"></div>')
                              .children()
                              .hide()
                              .fadeIn();       
            },
            success:function(e) {
                <?php if (!isset($is_user_logged_in)): ?>
               if (e.empty  === 1) {
                      $('#comments').prepend('<h4>No Comment</h4>');
                  }
                <?php endif ?>
               if (e.status === 1) {
                  $('#comments').prepend(e.html);
               }
            },
            complete:function(e) {
              $('.ajax-loader-comments')
                    .fadeOut()
                    .remove();
               setTimeout(function() {
                 $('.social').fadeIn();     
               }, 650)
              
            }
          });          
       });

      $('.btnsave').click(function(e){
          var status = $('#select_status').val();              
           $.ajax({
              url:$('#statusform').attr('action'), 
              type:'POST',
              dataType:'JSON',
              data :{
                'status' : status,
                'id'  : $('#statusform').attr('data-id')
              },
              success: function(e) {
                if (e.status === 1) {
                  location.reload();
                }
              }
            })
          e.preventDefault();
      });
     </script>
      <hr>
      <br>
      <br>
 <?php $this->load->view('fragment/js_script'); ?>
</div> <!-- /container -->