<div id="newIdeaContainer">
	<form id="newidea" class="form" action="<?php echo site_url(); ?>/suggest/add_new_suggestion">
		<input type="hidden" id="baseUrl" value="<?php echo site_url(); ?>/">
		<input type="text" class="suggestion" name="suggestion" placeholder="<?php echo lang("Enteridea"); ?>" required>
		<textarea name="description" placeholder="<?php echo lang("Describeidea"); ?>"></textarea>
	<?php if (isset($categories)): ?>
			<select>
			<option name="category" value="empty"></option>
		<?php foreach ($categories as $category): ?>
			<option name="category" value="<?php echo $category->category_slug; ?>"><?php echo $category->category_name; ?></option>
		<?php endforeach ?>
			</select>
	<?php endif ?>
	<div class="votebuttons">
        <button class="btn btn-default btn-xs votebtn-value" type="button" value="1" name="vote">1 <?php echo lang('vote');  ?></button>
        <button class="btn btn-default btn-xs votebtn-value" type="button" value="2" name="vote">2 <?php echo lang('votes'); ?></button>
        <button class="btn btn-default btn-xs votebtn-value" type="button" value="3" name="vote">3 <?php echo lang('votes'); ?></button>  
    </div>

	<?php if(!isset($is_user_logged_in)): ?>
		<input type="email" class="mail" name="email" placeholder="<?php echo lang("Enteremail"); ?> <?php echo CI_Settings::get('AnonymousPosting') ? '(Optional)' : ''; ?>" required>
		<input type="password" name="password" placeholder="<?php echo lang("Enterpassword"); ?>" required>	
	<?php endif ?>
		<button type="submit" class="btn btn-sm btn-primary btn-submit"><?php echo lang("submit"); ?></button>
	</form>
</div>