<ul class="nav navbar-nav side-nav">
  <li <?php echo (isset($nav_selected) && $nav_selected === 'Dashboard') ? 'class="active"' : ''; ?>><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
<?php if(isset($isAdmin)): ?>
  <li <?php echo (isset($nav_selected) && $nav_selected === 'Users') ? 'class="active"' : ''; ?>><a href="<?php echo site_url() ?>/admin/user/"><i class="fa fa-users"></i> <?php echo lang('users'); ?></a></li>
  <li <?php echo (isset($nav_selected) && $nav_selected === 'Settings') ? 'class="active"' : ''; ?>><a href="<?php echo site_url() ?>/admin/settings"><i class="fa fa-gear"></i> <?php echo lang('settings'); ?></a></li>
<?php endif ?>
</ul>