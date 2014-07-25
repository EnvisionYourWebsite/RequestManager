<div class="container-narrow">
		<div class="container">
			<?php if (isset($errors)):  ?>
				<div class="message alert alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo $errors; ?>
				</div>
			<?php elseif (isset($infos)): ?>
				<div class="message alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo $infos; ?>
				</div>
			<?php endif ?>
		<div class="row">
	      	<div id="register-wrapper">
				<form method="post" action="<?php echo site_url() ?>/register/new_member" class="form register-form">
					<legend><?php echo lang('register'); ?></legend>	
					<div class="main">
						<div class="field">
							<label for="username"><?php echo lang('username'); ?>:</label>
							<input type="text" name="username" placeholder="<?php echo lang('username'); ?>" required>
						</div>
						<div class="field">
							<label for="email"><?php echo lang('email'); ?>:</label>	
							<input type="email" name="email" placeholder="Email" required>
						</div>
						<div class="field">
							<label for="password"><?php echo lang('password'); ?>:</label>
							<input type="password" name="password" placeholder="<?php echo lang('password'); ?>" required>
						</div>
					</div>
					<div class="footer">
						<span class="accept">
							<label class="choice"><input type="checkbox" class="field login-checkbox" required value="First Choice">I Have read and agree with the Terms of Use.</label>
						</span>
						<button type="submit" class="btn btn-success"><?php echo lang('register'); ?></button>
					</div>
				</form>
			</div>
		</div>
		</div>

		<hr>
		<br>
		<br>
      <div class="footer">
        <p>&copy; Company 2013</p>
      </div>

    </div> <!-- /container -->

	<script type="text/javascript">
		$(document).ready(function(){
			$('.message').hide()
						 .slideDown();	
		});
	</script>