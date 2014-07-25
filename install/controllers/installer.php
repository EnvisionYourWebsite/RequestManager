<?php defined('BASEPATH') or exit('No direct script access allowed');


class Installer extends CI_Controller {

	private $writable_files = array(
			'application/config/config.php'
		);

	public function __construct() {
		parent::__construct(); 
		$this->load->library('form_validation');

	}

	private function render($data) {
		$this->load->view('global',$data);
	}

	public function step_1() {
		$data = new stdCLass(); 

		$this->session->set_userdata(array('hostname' => $this->input->post('hostname'),
										   'db_username' => $this->input->post('username'),
										   'db_password' => $this->input->post('password'), 
										   'port'	  => $this->input->post('port'),
										   'http_server' => $this->input->post('http_server')
									)); 


		$this->form_validation->set_rules(array(
			array(
				'field' => 'hostname',
				'label' => 'MySQL Host',
				'rules' => 'trim|required|'
			),
			array(
				'field' => 'username',
				'label' => 'MySQL Username',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'password',
				'label' => 'MySQL Password',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'port',
				'label' => 'Port',
				'rules' => 'trim|required'
			)
		));

		if ($this->form_validation->run()) {
			$this->session->set_userdata('step1_passed',TRUE); 
			redirect('installer/step_2');
		} else {

			$this->render(array( 'sub_view' =>  'step_1', 'errors' => validation_errors()));
		}



	}

	public function validate_mysql_db_name($dbname) {
		$this->form_validation->set_message('validate_mysql_db_name', 'Invalid Database name');
		return ! (preg_match('/[^A-Za-z0-9_-]+/', $dbname) > 0);
	}

	public function test_db_connection() {
		if (!$this->installerlib->mysql_available()) {

			return FALSE;
		}

		if (!$this->installerlib->test_db_connection()) {

			return FALSE;
		}

		return TRUE;
	}

	public function step_2() {
		if (!$this->session->userdata('step1_passed')) {
			redirect('');
		}

		$data = new stdClass;

		$this->session->set_userdata('step2_passed',TRUE);

		redirect('installer/step_3');
	
	}

	public function step_3() {
		if (!$this->session->userdata('step1_passed') OR ! $this->session->userdata('step2_passed')) {
			redirect('installer/step_2');
		}



		$this->session->set_userdata(array('database' => $this->input->post('database'),
										   'username' => $this->input->post('username'), 
										   'email'	  => $this->input->post('email'), 
										   'password' => $this->input->post('password')));

		$this->form_validation->set_rules(array(
			array(
				'field' => 'database',
				'label' => 'Database',
				'rules' => 'trim|required|callback_validate_mysql_db_name'
			),
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|min_length[6]|max_length[20]|required'
			),
		));

		if ($this->form_validation->run() == FALSE) {
			$this->render(array( 'sub_view' =>  'step_3','errors' => validation_errors()));
		}
		else {

			$this->session->set_userdata(array('database' => $this->input->post('database')));


			$this->session->set_userdata('step3_passed',TRUE);

			redirect('installer/step_4');	
			//redirect('installer/complete');

		}
	}

	public function step_4() {
		if (!$this->session->userdata('step2_passed') OR ! $this->session->userdata('step3_passed')) {
			redirect('installer/step_3');
		}

		if ($this->input->post('fbid') && $this->input->post('fbsecret')) {
			$this->session->set_userdata(array('so_fb' => TRUE,'fb_appid' => $this->input->post('fbid'),'fb_secret' => $this->input->post('fbsecret')));
		}

		$this->form_validation->set_rules(array(
			array(
				'field' => 'fbid',
				'label' => 'App ID',
				'rules' => 'trim|xx_clean'
			),
			array(
				'field' => 'fbsecret',
				'label' => 'App Secret',
				'rules' => 'trim|xss_clean'
			)
		));

		if ($this->form_validation->run() == FALSE) {
			$this->render(array( 'sub_view' =>  'step_4','errors' => validation_errors()));
		}
		else
		{
			$install = $this->installerlib->install();
			if ($install['status'] == FALSE) {
				$this->session->set_flashdata('message','There\'s was an error');
				die(mysql_error());
			}

			$this->session->set_userdata('step4_passed',TRUE);
			redirect('installer/complete');

		}

		

	}

	public function complete() {
		if (!$this->session->userdata('step4_passed')) {
			redirect(site_url());
		}

		// Destroy session 
		$this->session->sess_destroy(); 

		$this->render(array( 'sub_view' =>  'complete'));
	}

	public function index() {
		redirect('installer/step_1');
	}
}



?>