<?php 
	  $query = $this->user_model->get_where(array('ID' => get_connected_user_id())); 
		if ($query->num_rows() > 0) {
			$user = $query->row();
		}
?>
<ul class="nav navbar-nav navbar-right navbar-user">
    <li>
      <a href="<?php echo site_url() ?>">Site Home</a>
    </li>
    <li class="dropdown user-dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $user->Username; ?> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo site_url() ?>/user/profile"><i class="fa fa-user"></i> <?php echo lang('profile') ?></a></li>
        <li><a href="<?php echo site_url() ?>/user/accountsettings"><i class="fa fa-gear"></i> <?php echo lang('account_settings'); ?> </a></li>
        <li class="divider"></li>
        <li><a href="<?php echo site_url() ?>/logout"><i class="fa fa-power-off"></i> <?php echo lang('logout'); ?></a></li>
      </ul>
    </li>
</ul>