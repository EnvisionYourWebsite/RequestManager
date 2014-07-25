<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

	
	class Ratings extends AuthenticatedUser_Controller  {
		public function __construct() {
			parent::__construct(); 
			$this->load->library('users/auth'); 
			$this->load->helper('suggestion');
			
		}

		public function index() {
			
		}

		public function vote($id=NULL,$value=NULL) {
			
			$ret = FALSE;
			if ($this->input->post('id')) {
				$id    = $this->input->post('id'); 
				$value = $this->input->post('value'); 
				$ret = TRUE;
			}
			elseif ($id)
			{
				$ret = FALSE;
			}
			else
			{
				return FALSE;
			}
				
			if (is_numeric($id) && is_numeric($value)) {
						$this->load->library('Rating','rating');
						$this->load->helper('account');  
						if (!$this->rating->has_user_voted_for_suggestion(get_connected_user_id(),$id)) {
							if ($this->rating->rate_feature(get_connected_user_id(),$id,$value)) {
									$left_votes = $this->rating->count_user_left_votes(get_connected_user_id());
									if ($ret)
										{ echo json_encode(array('status' => 1,'suggestion_votes' => count_suggestion_votes($id),'user_left_votes' => $left_votes));}
								}
			 					else {
			 						$voteleft = $this->rating->count_user_left_votes(get_connected_user_id());
			 						if ( $voteleft == 0 || ($voteleft - $value) < 0 ) {
			 							if ($ret) {
			 								echo json_encode(array('status' => 0, 'msg' => lang('you_cant_vote')));
			 							}

			 							
			 						}
			 						else {
			 							if ($ret) { echo json_encode(array('status' => 0)); }
			 						}
									
								}
						}
						else {
							if ($this->rating->update_rate(get_connected_user_id(),$id,$value)) {
								$left_votes = $this->rating->count_user_left_votes(get_connected_user_id());
								if ($ret) { echo json_encode(array('status' => 1, 'updated' => 1, 'suggestion_votes' => count_suggestion_votes($id),'user_left_votes' => $left_votes)); }
							}
							else {
								$voteleft = $this->rating->count_user_left_votes(get_connected_user_id());
			 						if ( $voteleft == 0 || ($voteleft - $value) <= 0 ) {
			 							if ($ret) {	echo json_encode(array('status' => 0, 'msg' => lang('you_cant_vote'))); }
			 						}
			 						else {
			 						 if ($ret) { echo json_encode(array('status' => 0)); }
			 						}
							}
						}

					update_suggestion_total_votes($id);		
				}
		}
	}
?>