<div class="container-narrow">
	      <div class="row">
	      	<?php if (validation_errors() != '')  { echo '<div class="alert alert-danger">' . validation_errors() . '</div>'; } if(isset($error)) { echo '<div class="alert alert-danger">'.  $error . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>'; } ?>
        	<div id="login-wrapper">
        	<?php echo form_open(site_url() . '/login/authenticate',array('class' => 'form login-form')); ?>
				<legend><?php echo lang('SignIn'); ?></legend>
				<div class="main">
					<div class="field">
						<label for="username"><?php echo lang('username'); ?>:</label>
						<?php echo form_input(array('name' => 'username','placeholder' =>  lang('Enter_username_or_email'),'required' => 'required')); ?>
					</div>
					<div class="field">
						<label for="password"><?php echo lang('password'); ?>:</label>
						<?php echo form_password(array('name' => 'password', 'placeholder' =>  lang("Enterpassword"), 'required' => 'required')); ?>
					</div>
				<?php if (CI_Settings::get('FacebookLogin')): ?>
					<p><?php echo lang('sign_in_with'); ?> <a href="<?php echo site_url() ?>/login/facebook">Facebook</a> <?php echo lang('or'); ?> <a href="<?php echo site_url() ?>/register"><?php echo lang('register'); ?></a></p>
				<?php else: ?>
     			 	<p><a href="<?php echo site_url() ?>/register"><?php echo lang('register'); ?></a></p>
     			 <?php endif  ?>
				</div>
				<div class="footer">
					<button type="submit" class="btn btn-success"><?php echo lang('login') ?></button>				
				</div>
			<?php echo form_close(); ?>
		</div>
		<hr>
		<br>
		<br>
      <div class="footer">
        <p>&copy; Company 2013</p>
      </div>

    </div> <!-- /container -->


