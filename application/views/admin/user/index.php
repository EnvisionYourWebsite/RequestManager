  <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete_modal" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
              <button class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Delete a User!</h4>
            </div>
          <div class="modal-body">
            <p><?php echo lang('delete_record_message'); ?></p>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></a>
            <a id="link" href="#" class="btn btn-danger"><?php echo lang('remove'); ?></a>
          </div>
        </div>
    </div>
</div>

<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo lang('users'); ?></a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
    </nav>
      <div id="page-wrapper">
        <div class="row">
           <div class="col-lg-12">
            <h2><?php echo lang('users'); ?></h2>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
              <li class="active"><i class="fa fa-users"></i> <?php echo lang('users'); ?></li>
            </ol>
            <div class="table-responsive">
            <div class="btn-group">
              <?php echo anchor('admin/user/add','<span class="glyphicon glyphicon-plus"></span> Add a user','class="btn btn-default btn-sm"'); ?>
              <button  class="rm-sel btn btn-default btn-sm<?php echo (!isset($users) || count($users) === 0) ? ' disabled' :'' ?>" type="button"><span class="glyphicon glyphicon-remove"></span> Removes Seleted</button>
            </div>
        
            <table id="users" class="table table-hover tablesorter">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo lang('username'); ?></th>
                <th><?php echo lang('name'); ?></th>
                <th><?php echo lang('email'); ?></th>
                <th><?php echo lang('admin'); ?></th>
                <th><?php echo lang('blocked'); ?></th>
                <th><?php echo lang('edit'); ?></th>
                <th><?php echo lang('delete'); ?></th>
              </tr>
            </thead>
            <tbody>
        <?php  if(isset($users) && count($users)): foreach($users as $user): ?>
              <tr>
                <td><input type="checkbox" name="<?php echo $user->ID; ?>"/></td>
                <td><?php echo $user->Username ?></td>
                <td><?php echo get_profile_name($user->ID); ?></td>
                <td><?php echo anchor('admin/user/edit/' . $user->ID, $user->email); ?></td>
                <td><?php echo is_admin($user->ID) ? 'Yes' : 'No';  ?></td>
                <td><?php echo (get_user_status($user->Username) == 4) ? 'Yes'  : 'No' ?></td>
                <td><?php echo btn_edit('admin/user/edit/' . $user->ID); ?></td>
                <td><?php echo btn_delete(site_url() . '/admin/user/delete/' . $user->ID); ?></td>
              </tr>
              <?php endforeach ?>
        <?php else: ?>
              <tr>
                <td colspan="7"><?php echo lang("no_registred_users"); ?></td>
              </tr>
        <?php endif ?>
            </tbody>
          </table>
            </div>
          </div>
        </div><!-- /.row -->
      </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->

     <script>
     $(document).ready(function(){
       $('.delete').click(function(e){
        var uri = $(this).attr('data-link'); 
        $('#link').attr('href',uri);
        $('.modal-backdrop').css({'z-index': '1020'});
      });

      $('.rm-sel').click(function(e){      
        var checkedusers = $('#users').find('input:checked');
        if (checkedusers.length === 0)
          {
            alert('Please select a user');
          }
        var array = [];
        checkedusers.each(function(e){
          array.push($(this).attr('name'));
        });

        $.ajax({
          type:'POST',
          url:'<?php echo site_url() ?>/admin/user/delete_group',
          data : { 'ids' : array },
          dataType:'JSON',
          success:function(e){
            if (e.status === 1) {
              window.location = window.location.href;
            }
          }
        });
        e.preventDefault();
      });
    });
  </script>