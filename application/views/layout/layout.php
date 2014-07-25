<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo config_item('charset');?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="text/javascript">
    var connected = <?php echo (is_user_logged_in()) ? 'true;' : 'false;';  ?>
    var anonymous = <?php echo (CI_Settings::get('AnonymousPosting')) ? 'true;' : 'false;'; ?>
    </script>
	<title><?php echo $Title; ?></title>
	<?php 
		$this->template->print_array($includes); 
		echo $this->template->generate_in_file_code();
	?>
</head>
<body>
	
	<?php if(isset($view)) { $this->load->view($view); } ?>


</body>
<footer></footer>
</html>