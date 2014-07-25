<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

	class Comments extends Public_Controller {

		public function __construct() {
			parent::__construct(); 
			$this->load->helper('htmlawed'); 
			$this->load->model('comment_model');
			$this->load->helper('account');

		}

		public function index() {
			$this->Check_Access(); 
		}

		// Check if User is Logged in
		public function add_new_comment() {
			$this->Check_Access(); 

			$content = $this->input->post('content',TRUE); 
			$id = $this->input->post('id',TRUE);
			if (!empty($content) && !empty($id)) {

				$userID = get_connected_user_id();
				if (!$this->comment_model->record_exists(array('UserID' => $userID,'Comment' => $content,'SuggestionID' => $id))) {
					$this->load->helper('account'); 
					$AddedOn = date('Y-m-d H:i:s');
					if($this->comment_model->insert_comment(array('Comment' => $content,
											'UserID' => $userID,
											'SuggestionID' => $id,
											'Status' => 0,
											'AddedOn' => $AddedOn))) 
					{
						$this->data['results'] = array('ID' => $this->comment_model->get_comment_id(array('UserID' => $userID,'Comment' => $content, 'SuggestionID' => $id)),'Comment' => $content,'UserID' =>  get_connected_user_id(),'AddedOn' => $AddedOn);
						$this->data['results'] = array($this->data['results']);
						$html = $this->load->view('fragment/comment_item',$this->data,TRUE);
						/*$this->load->library('notifier'); 
						$event = array('action' => 'new_comment','user_id' => $userID,'suggestionId' => $id,'comment_content' => $content); 
						Events::trigger('notify',$event,'array');*/
						echo json_encode(array('status' => 1,'html' => $html)); 
					}
					else {
						echo json_encode(array('status' => 0));
					}
				}
				else {
					echo json_encode(array('status' => 0, 'msg' => "<p class=\"alert alert-danger\">". lang('comment_already_posted') ."<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
				}
				
			}
		}

		// Get Comments (Everyone can see comments)
		public function get_comments() {
			if ($this->input->is_ajax_request()) {
				$id = $this->input->post('id');
				if ($id || $this->uri->segment(3,0)) {
					$this->load->library('pagination'); 

					$config['base_url']   = site_url() . '/comments/get_comments';
					$config['total_rows'] = $this->comment_model->num_rows_where(array('SuggestionID' => $id)); 
					$config['per_page']	  = 10; 
					$config['full_tag_open'] = '<div class="pagination" id="links">'; 
					$config['full_tag_close'] = '</div>'; 

					$this->pagination->initialize($config); 
					$page = ($this->uri->segment(3)) ? $this->uri->segment(3)  : 0; 

					$this->data['results'] = $this->comment_model->fetch_where($config['per_page'],$page,array('SuggestionID' => $id));
					$this->data['links'] = $this->pagination->create_links(); 
					$this->data['script'] = TRUE;
					
					$html = $this->load->view('fragment/comment_item',$this->data ,TRUE);

					if (empty($this->data['results'])) {
						echo json_encode(array('status' => 0, 'empty' => 1,'html' => $html)); 
						exit;
					}

					echo json_encode(array('status' => 1, 'html' => $html));  
				}
			}
		}

		

	}




?>