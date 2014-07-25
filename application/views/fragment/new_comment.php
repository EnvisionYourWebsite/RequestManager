<div class="newComment">
    <form id="commentform" method="post" action="<?php echo site_url() ?>/comments/add_new_comment">
        <textarea id="content" name="comment" placeholder="<?php echo lang('add_comment'); ?>"></textarea>
         <input class="btn btn-default commentbtn" type="submit" value="<?php echo lang('submit'); ?>">
    </form>
</div> 