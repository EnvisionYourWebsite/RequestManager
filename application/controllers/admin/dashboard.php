<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dashboard extends Admin_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->helper('suggestion');
			$this->load->helper('stats');
			$this->load->model('suggestion_model');
			
		}

		public function index() {
			$this->template->set_page_title('Dashboard')
						   ->set_view('admin/dashboard')
						   ->add_js_file('tablesorter/jquery.tablesorter.js')
						   ->add_js_file('tablesorter/tables.js')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');

			$this->data['nav_selected'] = 'Dashboard';

			if ($this->suggestion_model->isEmpty_where(array('UserID' =>  get_connected_user_id()))) {
				$this->data['empty'] = TRUE;
			}
			else 
			{
				$this->load->library('pagination'); 

				$config['base_url']   = site_url() . '/dashboard';
				$config['total_rows'] = $this->suggestion_model->num_rows_where(array('UserID' =>  get_connected_user_id())); 
				$config['per_page']	  = 10; 

				$this->pagination->initialize($config); 

				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0; 
				$this->data['results'] = $this->suggestion_model->fetch_where($config['per_page'],$page,array('UserID' =>  get_connected_user_id())); 
			}

			$this->template->set_additional_data($this->data);
			$this->template->render();
		}

		public function save_setting() {
			$this->load->model('settings/users/usersettings_model'); 
			$this->load->helper('account');
			$UserID = get_connected_user_id();
			if ($this->input->post('new_reg')) {
				$this->usersettings_model->insert_or_update(array('new_reg' => 'TRUE'),$UserID);
			}
			else {
				$this->usersettings_model->insert_or_update(array('new_reg' => 'FALSE'),$UserID);
			}

			if ($this->input->post('new_sug')) {
				$this->usersettings_model->insert_or_update(array('new_sug' => 'TRUE'),$UserID); 
			} else {
				$this->usersettings_model->insert_or_update(array('new_sug' => 'FALSE'),$UserID);
			}
			
			redirect('admin/settings?saved=true');
		}

		public function save_general_settings() {	
			if ($this->input->post('maxvotes')) {
				CI_Settings::set('Max_votes',$this->input->post('maxvotes'));
			}else {
				CI_Settings::set('Max_votes',0);
			}
			if ($this->input->post('allow_new_reg')) {
				CI_Settings::set('Allow_New_Reg',TRUE);
			}
			else {
				CI_Settings::set('Allow_New_Reg',FALSE);
			}

			if ($this->input->post('email_val')) {
				CI_Settings::set('Email_Val',TRUE);
			}
			else {
				CI_Settings::set('Email_Val',FALSE);
			}

			if ($this->input->post('notify_admin')) {
				CI_Settings::set('NotifyAdmin',TRUE);
			}
			else {
				CI_Settings::set('NotifyAdmin', FALSE);
			}

			if ($this->input->post('allow_anonymous')) {
				CI_Settings::set('AnonymousPosting', TRUE);
			}
			else
			{
				CI_Settings::set('AnonymousPosting', FALSE);
			}

			if ($this->input->post('allow_so_reg')) {
				if ($this->input->post('fbappid') && $this->input->post('fbsecret')) {
				// File Configuration 
				$replace = array(
					'__FBAPPID__'	 => $this->input->post('fbappid'),
					'__FBAPPSECRET__'=> $this->input->post('fbsecret'),
				);

				CI_Settings::set('FacebookLogin',TRUE);

				$this->_write_file_vars('./application/config/facebook.php','./assets/config/facebook.php',$replace);
				}
			}
			else
			{
				CI_Settings::set('FacebookLogin',FALSE);
			}

			if ($this->input->post('default_lang')) {
				$this->languages->change_default_language($this->input->post('default_lang'));
			}

			redirect('admin/settings?saved=true');
		}


		private function _write_file_vars($destination, $template, $replacements)
		{
			return (file_put_contents($destination, str_replace(array_keys($replacements), $replacements, file_get_contents($template))) !== FALSE);
		}
		
		public function remove($slug) {
			if ($slug == NULL) 
				redirect('/');
			if ($this->suggestion_model->slug_exists($slug)) {
				 $id = $this->suggestion_model->get_suggestion_id($slug); 
				 remove_suggestion($id);
				}
			redirect('/');
		}

	// 0 => New
 	// 1 => Pending
 	// 2 => Planned
 	// 3 => Started 
 	// 4 => Completed	
	// 5 => Declined 
		public function set_status() {
			if ($this->input->is_ajax_request()) {
				$id = $this->input->post('id');
				$status = $this->input->post('status');
				$this->load->helper('suggestion');
				$status = get_status_id($status);
			

				if ($status == FALSE) {
					json_encode(array('status' => 0)); 
				}


				// Update Last Status
				$last_status = $this->suggestion_model->get_suggestion_status($id); 
				$this->suggestion_model->update_suggestion_last_status($id,$last_status);
				
				// Update Suggestion total votes
				// Update_suggestion_total_votes($id);

				if ($this->suggestion_model->set_suggestion_status($id,$status)) {

					if ($status != 0 && $status != 1 && $status != 2) {
						// Gives Vote to their users
						$this->load->library('Rating','rating');
						$this->rating->give_votes_to_users($id);	
					}
					
					$this->load->library('notifier');
					$data = array('action' => 'status_changed','suggestionId' => $id,'status' => $status);
					Events::trigger('notify',$data,'array');
					
					echo json_encode(array('status' => 1));
				}
				
			}
		}

		public function delete_comment($id) {
			if ($id == NULL)
				redirect('/');

			$suggestionSlug = get_suggestion_slug_with_comment_id($id); 
			$this->load->model('comment_model'); 
			$this->comment_model->delete_comment($id); 

			redirect('suggestion/details/'.$suggestionSlug);

		}

		public function ajax_get_message() {
			if ($this->input->is_ajax_request())  {
			if ($this->input->post('templatename')) {
				$TemplateName = $this->input->post('templatename');
				$this->load->model('emailtemplates/emailTemplate_Model'); 
				$data = $this->emailTemplate_Model->get_template_mail($TemplateName); 
				echo json_encode(array('status' => 1,'subject' => $data->Subject,'content' => $data->Content)); 
			}
			}
		}

		public function save_mail() {
			$this->load->library('form_validation'); 
			$this->validation_rules = array(
					array(
						'field' => 'mailtemplate',
						'label'	=> 'Mail Template',
						'rules'	=> 'required'
					),
					array(
						'field' => 'content',
						'label' => 'Content',
						'rules'	=> 'required'
					),
					array(
						'field' => 'subject', 
						'label' => 'Subject',
						'rules' => 'required'
					));

			$this->form_validation->set_rules($this->validation_rules); 
			if ($this->form_validation->run() != FALSE) {
					$this->load->model('emailtemplates/emailTemplate_Model'); 
					$this->load->helper('htmlawed');
					$htmLawed = htmLawed($this->input->post('content'));

					if ($this->emailTemplate_Model->set_template_mail_content($this->input->post('mailtemplate',TRUE),$this->input->post('subject',TRUE),$htmLawed)) {
						echo json_encode(array('status' => 1)); 
					}
					else {
						echo json_encode(array('status' => 0));
					}
			}
			else {
				echo json_encode(array('status' => 0,'msg' => validation_errors()));
			}
		}

		
	}
?>