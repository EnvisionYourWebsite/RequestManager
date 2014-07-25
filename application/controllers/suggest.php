<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	require('ratings.php');

	class Suggest extends AuthenticatedUser_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('suggestion_model');
			$this->load->helper('suggestion');
			$this->load->helper('util');
			$this->load->model('category_model');
		}

		public function index(){
			$this->template->set_page_title('Suggest')
						   ->set_view('suggest'); 

			$this->template->add_css_file('main.css')
						   ->render();

		}

		public function add_new_suggestion() {
			if ($this->input->is_ajax_request()) {
				$this->load->library('form_validation'); 
				$this->validation_rules =  array(
					array(
						'field' 	=> "description", 
						'label'		=> 'Description', 
						'rules'		=> 'required'
					),
					array(
						'field'		=> 'suggestion',
						'label'		=> 'suggestion',
						'rules'		=> 'required|max_length[60]|xss_clean'
					));
				

				$this->form_validation->set_rules($this->validation_rules);
				$this->form_validation->set_error_delimiters("<p class=\"alert alert-danger\">","<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button</p>");

				if ($this->form_validation->run() == TRUE) {

					$vote = (is_numeric($this->input->post('vote')) ? $vote = $this->input->post('vote') : 0 );

					$userID = get_connected_user_id(); 
					if (!$this->suggestion_model->record_exists(array('UserID' => $userID,
																	  'Title'  => $this->input->post('suggestion'),
																	  'Description' => $this->input->post('description')))) 
					{

						$this->load->helper('account');
						$this->load->helper('suggestion');
						$slug = generate_slug($this->input->post('suggestion'));
						$input = array(
							'UserID'	  => $userID,
							'Title' 	  => $this->input->post('suggestion',TRUE), 
							'Slug'		  => $slug,
							'Description' => $this->input->post('description',TRUE),
							'Status'	  => 0,
							);

						if ($this->input->post('category')){
							$category_id =	$this->category_model->get_category_id_by_slug($this->input->post('category'));
							if($category_id != FALSE)
							{
								$input = array_merge($input,array('category_id' => $category_id));
							}
						}

						if ($this->suggestion_model->insert_suggestion($input)) {
							$row = $this->suggestion_model->get_suggestion_with_slug($slug);
							// Vote TODO
							Ratings::vote($row->ID,$vote);
							// Generate the HTML
							$row->votes = count_suggestion_votes($row->ID);
							$data['results'][] = $row;
							$html = $this->load->view('fragment/feature_item',$data,TRUE);
							// Event 
							$this->load->library('notifier');
							$data = array('action' => 'new_suggestion','suggestionId' => $row->ID,'suggestionUrl' => site_url().'/suggestion/details/'.$slug);
							Events::trigger('notify',$data,'array');

							$msg = lang("suggestion_added"); 

							echo json_encode(array('status' => 1,'msg' => "<p class=\"alert alert-success\">" . $msg ."<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>",'html' => $html));
						}

					}
					else {
						echo json_encode(array('status' => 0, 'msg' => "<p class=\"alert alert-danger\">". lang('idea_already_posted') ."<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
					}

					
				}
				else {
					echo json_encode(array('status' => 0,'msg' => validation_errors()));
				}

			}
			else {
				echo '<p>Access Denied</p>';
			}
		}


		
	}

?>