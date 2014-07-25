	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Preferences</a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>
      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1>Preferences</h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
              <li class="active"><i class="icon-file-alt"></i> Admin Settings</li>
            </ol>
          </div>
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->