<?php defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	* // Reserverd For Next Version
	*/
	/*class CI_Anonymous
	{
		
		protected $ci;


		function __construct()
		{
			$this->ci =& get_instance();
			Events::register('rate_suggestion',array($this,'Rate_suggestion'));
			Events::register('post_suggestion',array($this,'Post_suggestion'));
			Events::register('register_user',array($this,'Register_user'));

			$this->ci()->load->model('suggestion_model');
			$this->ci()->load->helper('suggestion');

			//Events::register()
			# code...
		}

		public function ci() {
			return $this->ci;
		}

		public function Rate_suggestion($data) {
			$this->session->set_userdata(array('suggestion_id' => $data['suggestion_id'],'votes' => $data['votes']));
		}

		public function Post_suggestion($data) {
			$this->session->set_userdata(array('suggestion_id' => $data['suggestion_id']));
		}

		public function Register_user($data) {
			$suggestion_id = $this->session->userdata('suggestion_id');
			$votes 		   = $this->session->userdata('votes');

			$user_id = $data['user_id'];

		}






	}*/

	
 ?>