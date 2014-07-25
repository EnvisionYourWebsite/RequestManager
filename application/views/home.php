<div class="container-narrow">
      <div class="masthead">
       <?php $this->load->view('fragment/main_nav_bar.php'); ?>
        <h3 class="muted"><?php echo lang("Home_Title") ?></h3>
      </div>
      <hr>
      <div class="jumbotron">
      	 <?php $this->load->view('fragment/new_suggestion_form'); ?>
      </div>
      <?php $this->load->model('category_model'); ?>
      <hr>
      <div class="row">
        <?php if(!isset($empty)): ?>
        <div id="filters">
         <ul>
          <li><a href="<?php echo site_url() ?>/"><?php echo lang("all"); ?></a></li>
          <li><a href="<?php echo site_url() ?>/home/filter/top">Top</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo lang("status") ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo site_url() ?>/home/filter/status/pending"><?php echo lang("pending"); ?>(<?php echo count_suggestion_with_status(1); ?>) </a></li>
                <li><a href="<?php echo site_url() ?>/home/filter/status/planned"><?php echo lang("planned"); ?>(<?php echo count_suggestion_with_status(2); ?>)</a></li>
                <li><a href="<?php echo site_url() ?>/home/filter/status/started"><?php echo lang("started"); ?>(<?php echo count_suggestion_with_status(3); ?>) </a></li>
                <li><a href="<?php echo site_url() ?>/home/filter/status/completed"><?php echo lang("completed"); ?>(<?php echo count_suggestion_with_status(4); ?>)</a></li>
            </ul> 
          </li>
        <?php if ($this->suggestion_model->get_populated_categories()): ?>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Category <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php foreach ($this->suggestion_model->get_populated_categories() as $category_id): ?>
              <?php if ($category_id != NULL): ?>
                <li><a href="<?php echo site_url() ?>/home/filter/category/<?php echo $this->category_model->get_category_slug($category_id); ?>"><?php echo $this->category_model->get_category_name($category_id); ?>(<?php echo $this->suggestion_model->count_populated_category($category_id); ?>)</a></li>
              <?php endif ?>
              <?php endforeach ?>
            </ul>
          </li>
        <?php endif ?>
      <?php if (isset($is_user_logged_in)): ?>
          <li><a href="<?php echo site_url() ?>/home/filter/myideas"><?php echo lang("my_ideas"); ?></a></li>
      <?php endif ?>
        </ul>
      </div>
        <?php endif ?>
        <div id="featuresContainer" class="span12">
          <?php $this->load->view('fragment/feature_item'); ?>   
          <?php if(isset($links))  { echo $links; } ?>
        </div>
     </div>

     <?php $this->load->view('fragment/js_script'); ?>
      
</div> <!-- /container -->