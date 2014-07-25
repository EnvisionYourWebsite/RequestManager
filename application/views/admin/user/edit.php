	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo lang('users'); ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
        <?php $this->load->view('fragment/nav_bar');  ?>
        <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
             <h1><?php echo empty($user->ID) ? lang('add_new_user') : lang('edit_user'). ' ' . $user->Username; ?></h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
              <li><a href="<?php echo site_url() ?>/admin/user"><i class="fa fa-users"></i>  <?php echo lang('users'); ?></a></li>
              <li class="active"><i class="fa fa-user-edit"> Edit User</i></li>
            </ol>
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-12">
            <?php $this->load->helper('account') ?>
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo validation_errors(); ?>
      </div>
    <?php endif ?>
      <?php if ($this->input->get('saved')): ?>
      <p class="alert alert-success"><?php echo lang('content_saved'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></p>
      <?php endif ?>
      <?php if ($this->input->get('blocked')): ?>
      <p class="alert alert-success"><?php echo lang('user_blocked'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></p>
      <?php elseif ($this->input->get('unblocked')): ?>
      <p class="alert alert-success"><?php echo lang('user_unblocked'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></p>
      <?php elseif($this->input->get('activated')): ?>
    <p class="alert alert-success"><?php echo lang('account_activated'); ?><button type="button" class="close" data-dismiss="alert">&times;</button></p>
      <?php endif ?>
      <div class="row">
        <div class="modal-header">
        <?php echo form_open(); ?>
        <table clas="table">
          <tr>
            <td><?php echo lang('username'); ?></td>
            <td><?php echo form_input('username',set_value('username',empty($user->ID) ? '' : $user->Username)); ?></td>
          </tr>
          <tr>
            <td><?php echo lang('email'); ?></td>
            <td><?php echo form_input('email', set_value('email',empty($user->email) ? '' : $user->email)); ?></td>
          </tr>
          <tr>
            <td><?php echo lang('password'); ?></td>
            <td><?php echo form_password('password'); ?></td>
          </tr>
          <tr>
            <td><?php echo lang('confirm_password'); ?></td>
            <td><?php echo form_password('password_confirm'); ?></td>
          </tr>
          <tr>
            <td><?php echo lang('set_admin_rights'); ?></td>
            <td><?php echo form_checkbox('rights','true',empty($user->ID) ? '' : is_Admin($user->ID)); ?></td>
          </tr>
        <?php if (!empty($user->ID)): ?>
          <tr>
            <td><?php echo lang('account_status');?></td>
            <td><?php echo account_status(get_user_status($user->Username)); ?> <?php if (get_user_status($user->Username) == 1): ?> <a class="btn btn-sm btn-primary" href="<?php echo site_url() ?>/admin/user/activate/<?php echo $user->ID; ?>">Activate</a><?php endif ?></td>
          </tr>
        <?php endif  ?>
          <tr>
            <td><br></td>
          </tr>
        <?php if (!isset($user->ID)): ?>
          <tr>
            <td><?php echo lang('send_credentianl_info');?></td>
            <td><?php echo form_checkbox('sendemail','true'); ?></td>         
          </tr>
        <?php endif ?>
          <tr>
            <td></td>
            <td><?php echo form_submit('submit','Save', 'class="btn btn-default btn-sm btn-primary"'); ?>
              <?php if (isset($user->ID)): ?>
                <?php if (get_user_status($user->Username) == 4): ?>
                  <a href="<?php echo site_url() ?>/admin/user/unblock_user/<?php echo $user->ID; ?>" class="btn btn-default btn-sm"><?php echo lang("unblock_btn"); ?></a>
                <?php else: ?>
                  <a href="<?php echo site_url() ?>/admin/user/block_user/<?php echo $user->ID; ?>" class="btn btn-sm btn-danger"><?php echo lang("block_btn"); ?></a>
                <?php endif ?>
              <?php endif ?>
            </td>
          </tr>
        </table>
        <?php echo form_close(); ?>
      </div>
      </div>
      </div>
     </div>
     </div>
    </div><!-- /#page-wrapper -->
  </div><!-- /#wrapper -->
  