    <style type="text/css">
    .mini {
      font-size:10px;
    }
    </style>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url() ?>/dashboard"><?php echo lang('dashboard'); ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1><?php echo lang('dashboard'); ?> </h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></li>
            </ol>
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-3">
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                    <i class="fa fa-comments fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                    <p class="announcement-heading"><?php echo count_last_suggestions(); ?></p>
                    <p class="announcement-text">New Suggestions! </p>
                    <p><span class="mini">Since your last logout<span></p>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                    <div class="col-xs-6">
                    </div>
                    <div class="col-xs-6 text-right">
                      <i class="fa fa-arrow-circle-right" style="visibility: hidden;"></i>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                    <i class="fa fa-bars fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                    <p class="announcement-heading"><?php echo count_suggestions_by_status(1); ?></p>
                    <p class="announcement-text">Pending Suggestions</p>
                    <p><span class="mini"><?php echo count_suggestions_by_status(3); ?> Started suggestions</span></p>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                    <div class="col-xs-6">
                    </div>
                    <div class="col-xs-6 text-right">
                      <i class="fa fa-arrow-circle-right" style="visibility: hidden;"></i>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                    <i class="fa fa-users fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                    <p class="announcement-heading"><?php echo (count_total_users() -1); // Minus 1 because of Superadmin is not counted with other users ?></p>
                    <p class="announcement-text">Total Users</p>
                    <p><span class="mini"> <?php echo count_blocked_users(); ?> Blocked Users </span></p>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                  <a href="<?php echo site_url() ?>/admin/user">
                    <div class="col-xs-6">
                      Users
                    </div>
                    <div class="col-xs-6 text-right">
                      <i class="fa fa-arrow-circle-right"></i>
                    </div>
                    </a>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="panel panel-success">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                    <i class="fa fa-comments fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                    <p class="announcement-heading"><?php echo count_last_comments(); ?></p>
                    <p class="announcement-text">New Comments</p>
                    <p><span class="mini">Since your last logout</span></p>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                  <a href="<?php echo site_url() ?>">
                    <div class="col-xs-6">
                     View all suggestions 
                    </div>
                    <div class="col-xs-6 text-right">
                      <i class="fa fa-arrow-circle-right"></i>
                    </div>
                    </a> 
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div><!-- /.row -->
      </div>
    </div><!-- /.row -->
  </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<!-- Page Specific Plugins -->