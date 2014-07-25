	<div id="wrapper">
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">New Category</a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php $this->load->view('fragment/nav_bar'); ?>
          <?php $this->load->view('fragment/user_nav'); ?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1>New Category </h1>
            <ol class="breadcrumb">
              <li><a href="<?php echo site_url() ?>/dashboard"><i class="fa fa-dashboard"></i> <?php echo lang('dashboard'); ?></a></li>
              <li><a href="<?php echo site_url() ?>/admin/settings"><i class="fa fa-gear"></i> <?php echo lang('settings'); ?></a></li>
              <li><a href="<?php echo site_url() ?>/admin/settings#categories"><i></i>Categories</a></li>
              <li class="active"><i></i>New Category</li>
            </ol>
          </div>
        </div><!-- /.row -->

        <div class="row">
          <div class="col-lg-4">
            <div id="messages"></div>
            <form id="new-category" method="post" action="<?php echo site_url() ?>/admin/settings/new_category">
              <div class="form-group">
                <label for="category">Category Name: </label>
                <input class="form-control" type="text" name="category"/>
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
          </div>
        </div>

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->


    <script type="text/javascript">
        $(document).ready(function(){
            $('#new-category').submit(function(e){ 
                $('#messages').empty();
                if ($(this).find('input.form-control').val() === '')
                { 
                  // Message
                  $('#messages').append('<p class="alert alert-danger">Please insert a name for the category</p>');
                  e.preventDefault();
                  return false;
                }
            });
        });
    </script>