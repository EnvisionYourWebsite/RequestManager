	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo lang('profile'); ?></a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>

        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1><?php echo lang('profile'); ?></h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
              <li class="active"><i class="fa fa-user"></i> <?php echo lang('profile'); ?></li>
            </ol>
          </div>
        </div><!-- /.row -->
        <div class="row">
          <div id="messages"></div>
          <div class="col-lg-6">
          <div id="profile_container">
          <form id="basicsform" action="<?php echo site_url() ?>/user/profile/save_basics">
            <h3><?php echo lang('basics'); ?></h3>
            <table class="table">
              <thead>
                <th></th>
                <th></th>
              </thead>
              <tbody>
                <tr>
                  <td><label for="name"><?php echo lang('name') ?>:</label></td>
                  <td><input name="name" value="<?php if (isset($results)) { echo $results->name; } ?>" disabled></td>
                </tr>
                <tr>
                  <td><label for="username"><?php echo lang('username'); ?>:</label></td>
                  <td> <input name="username" value="<?php echo get_username_byId(get_connected_user_id()); ?>"></td>
                </tr>
                <tr>
                  <td><label for="email"><?php echo lang('email'); ?>:</label></td>
                  <td><input name="email" value="<?php if(isset($results)) { echo $results->email; } ?>" disabled></td>
                </tr>
                <tr>
                  <td> <input type="submit" value="<?php echo lang('save_btn'); ?>" class="btn btn-default btn-sm"></td>
                </tr>
              </tbody>
            </table>
          </form>
           <hr>
           <form id="passform" action="<?php echo site_url() ?>/user/profile/create_password">
                  <table class="table">
                  <thead>
                    <th></th>
                    <th></th>
                  </thead>
                    <h3><?php echo lang('password'); ?></h3>
                    <tbody>
                      <tr>
                        <td><label for="password"><?php echo lang('newpass'); ?>:</label></td>
                        <td><input name="password" type="password"></td>
                      </tr>
                      <tr>
                        <td><label for="passagain"><?php echo lang('renewpass'); ?>:</label></td>
                        <td><input name="passagain" type="password"></td>
                      </tr>
                      <tr>
                        <td><input type="submit" value="<?php echo lang('create_pass'); ?>" class="btn btn-default btn-sm"></td>
                      </tr>
                    </tbody>
                  </table>
          </form>
               <hr>
             <!--<div class="linked">
                  <h3>Linked Accounts</h3>
                </div> -->
          </div>
          </div>
        </div>

      </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->
<script type="text/javascript">
        $('.votebtn-x').click(function(){
            $('.IdeaVoteForm').slideDown();
        });


        $(document).ready(function(){

          $('#basicsform').submit(function(e){
              $.ajax({
                url: $(this).attr('action'),
                dataType:'JSON',
                type:'POST',
                data: $(this).serialize(),
                success:function(e) {
                    if (e.status === 1) {
                      $('#messages').empty();
                      $('#messages').prepend('<p class="alert alert-success">' +  e.msg +  '</p>');
                    }
                    else 
                    {
                      $('#messages').empty();
                      $('#messages').prepend(e.msg);
                    }
                }
              })
              e.preventDefault();
          });

          $('#passform').submit(function(e){
              $.ajax({
                  url:$(this).attr('action'),
                  dataType:'JSON',
                  type:'POST',
                  data: $(this).serialize(),
                  success:function(e) {
                    if (e.status === 1) {
                       $('#messages').empty();
                       $('#messages').prepend('<p class="alert alert-success">' +  e.msg +  '</p>');
                    }
                    else 
                    {
                      $('#messages').empty();
                      $('#messages').prepend(e.msg);
                    }
                  }
              });
              e.preventDefault();
          });
            
        });
</script>