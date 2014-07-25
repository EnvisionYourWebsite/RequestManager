	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo lang('dashboard'); ?></a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1><?php echo lang('dashboard'); ?></h1>
            <ol class="breadcrumb">
              <li class="active"><a href="#"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
            </ol>
            <?php if (isset($_GET['saved']) && $_GET['saved'] === 'true'): ?>
            <p class="alert alert-success">Settings saved <button type="button" class="close" data-dismiss="alert">&times;</button></p>
            <?php endif ?>
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-12">
          <?php if (has_current_user_suggested_ideas()): ?>
           <div id="myideas">
              <h3>My Ideas</h3>
              <?php foreach ($results as $row): ?>
                  <div class="feature-item">
                    <p><a href="<?php echo site_url() ?>/suggestion/details/<?php echo $row->Slug; ?>"><?php echo $row->Title; ?></a></p>
                  </div>
              <?php endforeach ?>
           </div>
          <?php else: ?>
            <p>You haven't suggested any idea <a href="<?php echo site_url() ?>">Suggest new one</a></p>
          <?php endif ?> 
          </div>
        </div>
      </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->