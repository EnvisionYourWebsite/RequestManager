<ul class="nav nav-pills pull-right">
  <li <?php if (isset($nav_selected) && $nav_selected == 'Home') { echo 'class="active"'; } ?>><a href="<?php echo site_url() ?>/"><?php echo lang('home'); ?></a></li>
<?php if (isset($is_user_logged_in) && $is_user_logged_in && (!isset($is_anonymous)) === TRUE): ?>
  <li><a href="<?php echo site_url() ?>/dashboard"><?php echo lang('dashboard'); ?></a></li>
  <li><a href="<?php echo site_url() ?>/user/profile"><?php echo lang('profile'); ?></a></li>
  <li><a href="<?php echo site_url() ?>/logout"><?php echo lang('logout'); ?></a></li>
<?php else: ?>
  <li><a href="<?php echo site_url() ?>/login"><?php echo lang('login'); ?></a></li>
  <li><a href="<?php echo site_url() ?>/register"><?php echo lang('register'); ?></a></li>
  	<?php if (isset($is_anonymous) && $is_anonymous === TRUE): ?>
  		<p style="font-size:10px; margin-left:10px;margin-bottom:5px;">You're logged in as Anonymous <a href="<?php echo site_url() ?>/logout">Logout</a></p>
  	<?php endif ?>
<?php endif ?>
</ul>