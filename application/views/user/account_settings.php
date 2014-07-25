	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Account Settings</a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1>Account Settings</h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard') ?></a></li>
              <li class="active"><i class="fa fa-gear"></i> Account Settings</li>
            </ol>
          </div>
        </div><!-- /.row -->

      <?php if (isset($isAdmin) && $isAdmin === TRUE): ?>
        <div class="row">
          <div class="col-lg-8">
            <form id="settingform" class="formSection form-horizontal" method="post" action="<?php echo site_url(); ?>/admin/dashboard/save_setting">
              <table>
                <thead>
                  <th></th>
                  <th></th>
                </thead>
                <tbody>
                  <tr>
                    <td><label><?php echo lang('receive_new_reg'); ?></label></td>
                    <?php if (check_setting('new_reg',get_connected_username())): ?>
                     <td><input type="checkbox" checked name="new_reg" value="new_reg"></td>
                    <?php else: ?>
                      <td><input type="checkbox" name="new_reg" value="new_reg"></td>
                    <?php endif ?>
                  </tr>
                  <tr>
                    <td><label><?php echo lang('receive_status'); ?></label></td>
                  <?php if (check_setting('new_sug',get_connected_username())): ?>
                    <td><input type="checkbox" checked="" name="new_sug" value="new_sug"></td>
                  <?php else: ?>
                    <td><input type="checkbox" name="new_sug" value="new_sug"></td>
                  <?php endif ?>
                  </tr>
                  <tr>
                    <td><input type="submit" class="btn btn-sm btn-primary" value="Save"></td>
                  </tr>
                </tbody>
              </table>
             </form> 
          </div>
        </div>
     <?php else: ?>
      <div class="row">
        <div class="col-lg-8">
      <?php if ($this->input->get('saved')): ?>
        <div class="alert alert-success"><?php echo lang('settings_saved'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
      <?php endif ?>
          <form id="settingform" class="formSection form-horizontal" method="post" action="<?= site_url(); ?>/dashboard/save_settings">
            <table>
              <thead>
                <th></th>
                <th></th>
              </thead>
              <tbody>
                <tr>
                  <td>
                  <?php $this->load->helper('account'); ?>
                <?php if (check_setting('live_status',get_connected_username())): ?>
                  <input type="checkbox" name="receive" checked value="true">
                <?php else: ?>
                  <input type="checkbox" name="receive" value="true">
                <?php endif ?>
                <?php echo lang('receive_live_status'); ?>
                  </td>
                </tr>
                <tr>
                  <td><input type="submit" class="btn btn-sm btn-primary" value="Save"></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    <?php endif ?>


    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->