<div class="ContentArea">
	<section>
		<h3>Step 4: Social Login</h3>
	</section>

	<?php if (isset($errors) && !empty($errors)): ?>
		<div class="alert alert-danger">
			<?php echo $errors; ?>
		</div>
	<?php endif ?>
	<?php echo form_open(uri_string(), 'id="install_frm"'); ?>
	<p class="alert alert-info">If you don't want to use facebook Login just leave blank.</p>
	<section>
		<h4>Facebook</h4>
		
		<div class="field">
			<label for="fbid">App ID</label>
			<?php echo form_input(array('id' => 'fbid', 'name' => 'fbid'), set_value('fbid')); ?>
		</div>
		<div class="field">
			<label for="fbsecret">App Secret</label>
			<?php echo form_input(array('id' => 'fbsecret', 'name' => 'fbsecret'), set_value('fbsecret')); ?>
		</div>
		<input type="hidden" name="installation_step" value="step_4"/>
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