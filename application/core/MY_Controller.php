<?php defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Controller extends CI_Controller {
	protected $data;
	public function __construct()
	{
		 parent::__construct();
		 $this->load->helper('account');
		if (is_user_logged_in()) {
			$this->data['is_user_logged_in'] = TRUE;
			if (is_admin_connected()) {
				$this->data['isAdmin'] = TRUE;
			}
			if (is_anonymous_logged_in()) {
				$this->data['is_anonymous'] = TRUE;
			}
		}

		if (CI_Settings::get('FacebookLogin')) {
			$this->config->load('facebook');
		}

		if (CI_Settings::get('AnonymousPosting')) {
			$this->data['AcceptAnonymous'] = TRUE;
		}
		else 
		{
			$this->data['AcceptAnonymous'] = FALSE;
		}

		$currentlang = $this->languages->get_current_language();

		$this->lang->load('general',$currentlang);
		$this->lang->load('form',$currentlang);
		$this->lang->load('messages',$currentlang);
	}

	public function CI()
	{
		return get_instance();
	}

	public function isAdmin()  {
 		$this->load->helper('url');

		if ($this->session->userdata('Isadmin'))
				return TRUE;
		return FALSE;
	}

	public function Check_Access()
 	{
 		$is_logged_in = $this->session->userdata('logged_in');
 		$this->load->helper('url');

		if ((!isset($is_logged_in) || $is_logged_in != true))
		{
			redirect('login');
		}

		return TRUE;
 	}
}

?>