<div class="ContentArea">
	<section>
		<h3>Step 1: Database configuration</h3>
	</section>

	<?php if (isset($errors) && $errors != ''): ?>
		<div class="alert alert-danger"><?php echo $errors; ?></div> 
	<?php endif ?>


	<?php echo form_open(uri_string(), 'id="install_frm"'); ?>

	<section class="title">
		<h4>Database Settings</h4>
	</section>
	<section>
		<div class="field">
			<label for="hostname">MySQL Hostname</label>
			<?php echo form_input(array('id' => 'hostname', 'name' => 'hostname'), set_value('hostname', 'localhost')); ?>
		</div>
		<div class="field">
			<label for="username">MySQL Username</label>
			<?php echo form_input(array('id' => 'username', 'name' => 'username'), set_value('username')); ?>
		</div>
		<div class="field">
			<label for="password">MySQL Password</label>
			<?php echo form_password(array('id' => 'password', 'name' => 'password'), set_value('password')); ?>
		</div>
		<div class="field">
			<label for="port">MySQL Port</label>
			<?php echo form_input(array('id' => 'port', 'name' => 'port'), '3306'); ?>
		</div>
		<input type="hidden" name="installation_step" value="step_1"/>
		<input type="submit" id="next_step" value="Next" class="btn"/>
	</section>
	<?php echo form_close(); ?>
	<div id="confirm_db_msg"></div>
</div>	

<script type="text/javascript">
	$('input[name=password]').bind('keyup focus', function() {

	$.post('<?php echo site_url(); ?>/ajax/confirm_database', {
			server: $('input[name=hostname]').val(),
            port: $('input[name=port]').val(),
			username: $('input[name=username]').val(),
			password: $('input[name=password]').val()
		}, function(data) {
			if (data.status == 1) {
				 $('#confirm_db_msg')
				 		.html(data.msg)
				 		.removeClass('alert alert-danger')
				 		.addClass('alert alert-success');
			} else {
				$('#confirm_db_msg')
						.html(data.msg)
						.removeClass('alert alert-success')
						.addClass('alert alert-danger');
			}
		}, 'json'
	);

});
</script>