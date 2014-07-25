<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	class Suggestion extends Public_Controller {
		protected $data;
		public function __construct() {
			parent::__construct();
			$this->load->model('suggestion_model');
			$this->load->model('category_model');
			$this->load->helper('suggestion');
			$this->load->helper('util');

			$this->load->helper('account');
			if (is_user_logged_in()) {
				$this->data['is_user_logged_in'] = TRUE;
				if (is_admin_connected()) {
					$this->data['isAdmin'] = TRUE;
				}
			}
		}

		public function index(){
			

		}

		public function details() {
			$this->template->set_page_title('Details')
						   ->add_css_file('main.css') 
						   ->set_view('suggestion_details');

			$slug = $this->uri->segment(3,0); 
			if ($this->suggestion_model->get_suggestion_with_slug($slug) != FALSE) {
				$this->data['results'] = $this->suggestion_model->get_suggestion_with_slug($slug);
			}
			else {
				$this->data['empty'] = TRUE;

			}
			
			$this->data['results']->votes = count_suggestion_votes($this->data['results']->ID);

			
			$this->template->set_additional_data($this->data);			   

			$this->template->render(); 
		}

		public function category($slug) {
			// Check if slug exsits 
			
		}
		
	}
?>