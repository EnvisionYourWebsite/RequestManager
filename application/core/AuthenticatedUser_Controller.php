<?php defined('BASEPATH') OR exit('No direct script access allowed');



 class AuthenticatedUser_Controller extends MY_Controller {

 	public function __construct() 
 	{
 		parent::__construct(); 
 	 	$this->Check_Access();
 	}

 	

 }






?>