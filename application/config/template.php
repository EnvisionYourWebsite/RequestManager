<?php defined('BASEPATH') OR exit('No direct script access allowed');


$config['default_css_files']  = array(
		'bootstrap.min.css',
	); 


$config['css_code'] = array('<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	',
	'<!--[if IE 6]>
	<script type="text/javascript" src="js/jquery.nyroModal-ie6.min.js"></script>
	<![endif]-->'
	);

$config['default_js_files']   = array(
	
		'jquery-1.10.2.min.js',
		'bootstrap.min.js'

	); 

$config['use_jquery']  = FALSE;

$config['jquery_verison'] = '1.10.2';

$config['use_default_css_files'] = TRUE; 

$config['use_default_js_files']  = TRUE;

$config['meta_data'] = NULL; 






?>