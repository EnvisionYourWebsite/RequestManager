<div class="ContentArea">
	<section>
		<h3>Step 3: Configuration</h3>
	</section>

	<?php if (isset($errors) && !empty($errors)): ?>
		<div class="alert alert-danger">
			<?php echo $errors; ?>
		</div>
	<?php endif ?>
	<?php echo form_open(uri_string(), 'id="install_frm"'); ?>

	<section class="title">
		<h4>Database Settings</h4>
	</section>
	<section>
		<div class="field">
			<label for="database">Database</label>
			<input type="text" id="database" class="input_text" name="database" value="<?php echo set_value('database'); ?>" />
		</div>
		<h4>Register Admin Account</h4>
		<div class="field">
			<label for="username">Username</label>
			<?php echo form_input(array('id' => 'username', 'name' => 'username'), set_value('username')); ?>
		</div>
		<div class="field">
			<label for="email">Email</label>
			<?php echo form_input(array('id' => 'email', 'name' => 'email'), set_value('email')); ?>
		</div>
		<div class="field">
			<label for="password">Password</label>
			<?php echo form_password(array('id' => 'password', 'name' => 'password'), set_value('password')); ?>
		</div>
		<input type="hidden" name="installation_step" value="step_3"/>
		<input type="submit" id="next_step" value="Next" class="btn"/>
	</section>

	<?php echo form_close(); ?>
</div>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('.alert').hide()
				   .slideDown();
	});
	
</script>