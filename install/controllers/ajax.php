<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Ajax extends CI_Controller {
		public function __construct() {
			parent::__construct();
		}

		public function index() {

		}

		public function confirm_database() {
			$server = $this->input->post('server'); 
			$username = $this->input->post('username'); 
			$password = $this->input->post('password');
			$port 	  = $this->input->post('port'); 

			$host = $server . ':' . $port;

			$link = @mysql_connect($host,$username,$password,TRUE); 

			if (!$link) {
				$data['status'] = 0; 
				$data['msg']    = mysql_error();
			}
			else {
				$data['status'] = 1; 
				$data['msg']    = "The database settings are tested and working fine."; 
			}

			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');

			echo json_encode($data);
			
		}
	}


?>