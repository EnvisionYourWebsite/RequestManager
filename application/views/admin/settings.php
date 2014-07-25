  <div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo lang('settings'); ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>
      <style type="text/css">
        label {
          font-weight: normal;
          font-size: 16px;
        }

        .nav-tabs {
          margin-bottom: 15px;
        }

        #categories select
        {
          display: inherit;
          margin-top: 15px;
        }
      </style>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1><?php echo lang('settings'); ?></h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
              <li class="active"><i class="fa fa-gear"></i> <?php echo lang('settings'); ?></li>
            </ol>
          </div>
        </div><!-- /.row -->

        <div id="main" class="row">
          <div class="col-lg-12">
          <?php if (isset($_GET['cat']) && $_GET['cat'] === '1' ): ?>
            <p class="alert alert-success">Category has been deleted successfully <button type="button" class="close" data-dismiss="alert">&times;</button></p>
          <?php endif ?>
          <?php if(isset($_GET['status']) && $_GET['status'] ==='1'): ?>
            <p class="alert alert-success">Category has been created successfully <button type="button" class="close" data-dismiss="alert">&times;</button></p>
          <?php endif ?>

          <?php if (isset($_GET['saved']) && $_GET['saved'] === 'true'): ?>
            <p class="alert alert-success">Settings saved <button type="button" class="close" data-dismiss="alert">&times;</button></p>
          <?php endif ?>
          <div class="tabs">
            <ul class="nav nav-tabs">
              <li><a href="#general" data-toggle="tab">General</a></li>
              <li><a href="#categories" data-toggle="tab">Categories</a></li>
              <li><a href="#mail" data-toggle="tab">Mail templates</a></li>
            </ul>
            <div id="tabs" class="tab-content">
              <div id="general" class="tab-pane fade">
                <form class="formSection" action="<?php echo site_url(); ?>/admin/dashboard/save_general_settings" method="post">
               <div id="general-settings">
               <table>
                 <thead>
                   <tr>
                     <th></th>
                     <th></th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <td><label for="maxvotes">Max User Votes:</label></td>
                     <td><input id="maxvotes" name="maxvotes" type="text"  value="<?php echo CI_Settings::get('Max_votes'); ?>">  </td>
                   </tr>
                   <tr>
                     <td><label for="allow_new_reg">Allow new registration</label></td>
                     <td><input id="allow_new_reg" type="checkbox" <?php echo CI_Settings::get('Allow_New_Reg') ?  'checked' : ''; ?> name="allow_new_reg" value="allow_new_reg"></td>
                   </tr>
                   <tr>
                     <td><label for="allow_anonymous">Allow Anonymous Users</label></td>
                     <td><input id="allow_anonymous" type="checkbox" <?php echo CI_Settings::get('Allow_New_Reg') ?  'checked' : ''; ?> name="allow_anonymous" value="allow_anonymous"></td>
                   </tr>
                   <tr>
                     <td><label for="allow_so_reg">Allow signing in form Facebook</label></td>
                     <td><input id="allow_so_reg" type="checkbox" <?php echo CI_Settings::get('FacebookLogin') ? 'checked' : '' ?> name="allow_so_reg"></td>
                   </tr>
                   <tr>
                     <td><label id="fbappid">FB AppID</label>
                      <?php if (CI_Settings::get('FacebookLogin')): ?>
                        <?php $array = $this->config->item('facebook'); ?>
                      <?php endif ?></td>
                      <td><input id="fbappad" name="fbappid" type="text" value="<?php echo isset($array) ? $array['fb_appid'] : ''; ?>"></td>
                   </tr>
                    <tr>
                     <td><label id="fbsecret">FB Secret</label></td>
                     <td><input id="fbsecret" name="fbsecret" type="text" value="<?php echo isset($array) ?  $array['fb_secret'] : ''; ?>"> </td>
                   </tr>
                   <tr>
                     <td><label for="email_val">Email Validation</label></td>
                     <td><input id="email_val" type="checkbox" <?php echo CI_Settings::get('Email_Val') ?  'checked' : ''; ?> name="email_val" value="email_val"></td>
                   </tr>
                    <tr>
                      <td><label for="notify_admin">Notify Administrator <span>for Super Admin</span></label></td>
                      <td> <input id="notify_admin" type="checkbox" <?php echo CI_Settings::get('NotifyAdmin') ?  'checked' : ''; ?> name="notify_admin" value="notify_admin"></td>
                    </tr>
                  
                   <tr>
                     <td><label for="default_lang">Default language</label></td>
                     <td><select id="default_lang" name="default_lang">
                      <?php foreach (CI_Languages::get_available_languages() as $language ): ?>
                        <option <?php if (ucfirst($language) == CI_Settings::get('DefaultLanguage')) { echo 'selected'; } ?> ><?php echo ucfirst($language); ?></option>
                      <?php endforeach ?>
                       </select>
                    </td>
                    <tr>
                      <td><input type="submit" class="btn btn-default btn-sm btn-primary" value="Save"></td>
                    </tr>
                   </tr>
                 </tbody>
               </table>
              </div>
          </form>
              </div> <!-- /#general -->
              <div id="categories" class="tab-pane fade">
                 <form role="form">
                <?php if ($this->category->category_exsits() > 0): ?>
                  <div class="btn-group">
                   <a href="<?php echo site_url() ?>/admin/settings/new_category"><span class="glyphicon glyphicon-plus"></span> Add a new Category</a>
                  </div>
                  <table class="table">
                    <thead>
                      <th>Category</th>
                      <th>Remove</th>
                    </thead>
                    <tbody>
                      <?php foreach ($this->category->get_categories() as $category): ?>
                        <tr>
                          <td data-name="<?php echo $category->category_slug; ?>"><?php echo $category->category_name; ?></td>
                          <td><a href="<?php echo site_url() ?>/admin/settings/remove_category/<?php echo $category->category_slug; ?>" class="remove-cat"><span class="glyphicon glyphicon-remove"></span></a></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                <?php else: ?>
                     <p>No Category found <a href="<?php echo site_url() ?>/admin/settings/new_category">Create a new one</a> </p>
                <?php endif ?>
                </form>
              </div>
              <div id="mail" class="tab-pane fade">
                <div id="mailtemplates">
                  <form id="mails" method="post" action="<?php echo site_url() ?>/admin/dashboard/save_mail">
                    <table cellpadding="5" class="content_table_inner">
                      <tr>
                        <th><label for="mailtemplate">Template Name:</label></th>
                        <td>
                          <select id="template" name="mailtemplate">
                              <?php $this->load->helper('util'); ?>
                              <?php foreach(get_mail_templates_names() as $row): ?>
                              <?php echo $row; ?>
                              <?php endforeach ?>
                          </select> 
                        </td>
                      </tr>
                      <tr>
                        <th><label for="subject">Subject: </label></th>
                        <td><input id="subject" type="text" name="subject"/></td>
                      </tr>
                      <tr>
                        <th><label for="content">Content: </label></th>
                        <td><textarea cols="64" rows="12" id="content"  name="content"></textarea></td>
                      </tr>
                      <tr>
                        <td><input type="submit" class="btn btn-sm btn-primary" value="Save"/></td>
                        <td></td>
                      </tr>
                    </table>
                  </form>
                </div>    
              </div>
            </div>
          </div>

          </div> 
        </div> <!-- / .row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <script type="text/javascript">
    $(document).ready(function(){

          $('#basicsform').submit(function(e){
              $.ajax({
                url: $(this).attr('action'),
                dataType:'JSON',
                type:'POST',
                data: $(this).serialize(),
                success:function(e) {
                    if (e.status === 1) {
                      $('#profile_container #messages').empty();
                      $('#profile_container #messages').prepend('<p class="alert alert-success">' +  e.msg +  '<button type="button" class="close" data-dismiss="alert">&times;</button></p>');
                    }
                    else 
                    {
                      $('#profile_container #messages').empty();
                      $('#profile_container #messages').prepend(e.msg);
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
                       $('#profile_container #messages').empty();
                       $('#profile_container #messages').prepend('<p class="alert alert-success">' +  e.msg +  '<button type="button" class="close" data-dismiss="alert">&times;</button></p>');
                    }
                    else 
                    {
                      $('#profile_container #messages').empty();
                      $('#profile_container #messages').prepend(e.msg);
                    }
                  }
              });
              e.preventDefault();
          });

           function get_templates_names() {
              $.ajax({
                type:'POST',
                url:'<?php echo site_url() ?>/admin/dashboard/ajax_get_message',
                dataType:'JSON',
                data: {
                  'templatename' : $('#template').val(),    
                },
                success:function(e) {
                  if (e.status === 1) {
                    $('#subject').val(e.subject);
                    $('#content').val(e.content);
                  }
                }

              });
            }

            get_templates_names();
            $('#template').change(function() {
              get_templates_names();        
            });

            $('#mails').submit(function(e){
               $.ajax({
                url:$(this).attr('action'),
                data: $(this).serialize(),
                type:'POST',
                dataType:'JSON',
                success:function(e){
                  $('.alert').remove();
                  if (e.status === 1) {
                    $('#main').prepend('<p class="alert alert-success">Template Saved! <button type="button" class="close" data-dismiss="alert">&times;</button></p>');
                  } else {
                    $('#main').prepend('<p class="alert alert-danger">There\'s was an error <button type="button" class="close" data-dismiss="alert">&times;</button></p>');
                  }
                }
               });
               e.preventDefault();
            });
            
        });
    </script>