<?php defined('BASEPATH') or exit('No direct script access allowed');

	class Upgrader extends CI_Controller {
		public function __construct() {
			parent::__construct();
		}

		private function render($data) {
			$this->load->view('global',$data);
		}

		public function index() {

			if ($this->input->post('fbid') && $this->input->post('fbsecret')) {
				$this->session->set_userdata(array('so_fb' => TRUE,'fb_appid' => $this->input->post('fbid'),'fb_secret' => $this->input->post('fbsecret')));
			}
			$this->load->library('form_validation');
			$this->form_validation->set_rules(array(
				array(
					'field' => 'fbid',
					'label' => 'App ID',
					'rules' => 'trim|xss_clean'
				),
				array(
					'field' => 'fbsecret',
					'label' => 'App Secret',
					'rules' => 'trim|xss_clean'
				)
			));

			if ($this->form_validation->run() == FALSE) {
				$this->render(array( 'sub_view' =>  'upstep_1','errors' => validation_errors()));
			}
			else
			{

				$this->session->set_userdata('step1_passed',TRUE);
				redirect('upgrader/complete');
			}

			
		}

		public function complete() {
			if ($this->session->userdata('step1_passed')) {

				$this->load->library('installerlib'); 
				$this->installerlib->upgrade();
				$this->render(array( 'sub_view' =>  'upgradecomplete'));
			}
				
		}


	}

?>