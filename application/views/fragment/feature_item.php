<?php $this->load->helper('account') ?>
<?php $this->load->helper('util') ?>
<?php if (isset($results) && !empty($results)): ?>
        <div id="features">
    <?php foreach ($results as $data): ?>
        <div class="feature-row" id="feature-<?php echo $data->ID; ?>" data-id="<?php echo $data->ID; ?>">
              <div class="votes">
                <div class="subvotes">
                   <span><?php echo $data->votes; ?></span>
                    votes
                </div>
                <?php $this->load->helper('suggestion'); ?>
                <?php $this->load->helper('account'); ?>
                <?php if (count_user_votes_for_suggestion(get_connected_user_id(),$data->ID) == FALSE): ?>
                  <?php if (get_suggestion_status($data->ID) == 2 || get_suggestion_status($data->ID) == 3 || get_suggestion_status($data->ID) == 4): ?>
                    <a class="btn btn-default votebtn" href="#" disabled><?php echo ucfirst(lang('vote_btn')); ?></a>
                  <?php else: ?>
                    <a class="btn btn-default votebtn" href="#"><?php echo ucfirst(lang('vote_btn')); ?></a>
                  <?php endif ?>
                <?php else: ?>
                    <a class="btn btn btn-default votebtn btn-primary" href="#"><?php echo count_user_votes_for_suggestion(get_connected_user_id(),$data->ID); ?></a>
                <?php endif ?>
              </div>
                <div class="content">
                  <h4><a href="<?php echo site_url() ?>/suggestion/details/<?php echo $data->Slug; ?>"><?php echo $data->Title ?></a><?php $status = get_suggestion_status($data->ID); ?></h4>
                    <?php echo  _ui_status($status); ?>
                    <div class="authorinfo">
                      <span>By <a href="#"><?php echo get_author_byId($data->UserID); ?></a></span>
                      <span><?php echo date("F d, Y H:i",strtotime($data->CreatedOn)); ?>
                      <?php if ($data->category_id != NULL): ?>
                      In <a href="<?php echo site_url() ?>/home/filter/category/<?php echo $this->category_model->get_category_slug($data->category_id); ?>">#<?php echo $this->category_model->get_category_name($data->category_id); ?></a>
                      <?php endif ?>
                      </span>
                      <span><a href="<?php echo site_url() ?>/suggestion/details/<?php echo $data->Slug; ?>"><?php echo count_suggestion_comment($data->ID); ?> <?php echo lang('comments'); ?></a></span>
                    </div>
                  <p class="content_text"><?php echo text_limiter($data->Description); ?></p>
              </div>
              <div class="IdeaVoteForm" style="display:none;">
                  <button type="button" class="close close-btn">&times;</button>
                  <fieldset class="IdeaVoteFormFieldset">
                    <legend>Vote</legend>
                    <form id="form-vote" method="post" action="<?php echo site_url() ?>/ratings/vote">
                    <div class="votebuttons">
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="1" name="vote">1 <?php echo lang('vote');  ?></button>
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="2" name="vote">2 <?php echo lang('votes'); ?></button>
                      <button class="btn btn-default btn-sm votebtn-value" type="submit" value="3" name="vote">3 <?php echo lang('votes'); ?></button>  
                    <?php if (count_user_votes_for_suggestion(get_connected_user_id(),$data->ID) == 0): ?>
                      <button id="removebtn" class="btn btn-default btn-sm votebtn-value" style="display:none;" type="submit" value="0" name="vote"><?php echo lang('remove_votes'); ?></button> 
                    <?php else: ?>
                      <button id="removebtn" class="btn btn-default btn-sm votebtn-value" type="submit" value="0" name="vote"><?php echo lang('remove_votes'); ?></button> 
                    <?php endif ?>
                    <?php if (is_user_logged_in()): ?>
                      <?php if (get_user_votes_left(get_connected_username()) != 0): ?>
                        <div class="VotesButtonsMeta">
                          <?php update_suggestion_total_votes($data->ID);?>
                          <span class="votesremaining"><?php echo lang('you_have'); ?> <?php echo get_user_votes_left(get_connected_username()); ?> <?php echo lang('votes_left'); ?></span>
                        </div>
                      <?php else: ?>
                        <div class="VotesButtonsMeta">
                          <span class="votesremaining"><?php echo lang('you_cant_vote'); ?></span>
                        </div>
                      <?php endif ?>  
                    <?php endif ?>
                    </div>
                    <?php if(!isset($is_user_logged_in)): ?>
                        <input type="email" class="mail" name="email" placeholder="<?php echo lang('Enteremail'); ?>" required>
                        <input type="submit" class="btn btn btn-default btn-sm btn-primary" value="Submit" style="display:none;">
                    <?php endif ?>
                    </form>
                  </fieldset>
              </div>
        </div>         
<?php endforeach ?>
        </div>
<?php else: ?>
  <h4 class="noideas"><?php echo lang('noideas'); ?></h4>
<?php endif ?>