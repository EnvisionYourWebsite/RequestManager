<?php defined('BASEPATH') OR exit('No direct script access allowed');

	if(!function_exists('generate_slug')) {
		function generate_slug($title) {
	          $slug = strtolower($title);
	               
	          $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
	          $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
	          $slug = preg_replace("/\s/", "-", $slug);

	          return uniqid().'-'.$slug;
	       
		}
	}

	if (!function_exists('count_suggestion_votes')) {
		function count_suggestion_votes($suggestionID) {
			$CI =& get_instance(); 
			$status = get_suggestion_status($suggestionID); 
			if($status != 0 && $status != 1 && $status != 2) {
				$CI->db->where(array('ID' => $suggestionID));
				$query = $CI->db->get('suggestions'); 
				if ($query->num_rows() > 0) {
					return $query->row()->Total_votes;
				}
				return FALSE;
			}
			$query = $CI->db->get_where("votes_log",array('SuggestionID' => $suggestionID)); 
			$total = 0; 
			if($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$total += $row->Votes; 
				}
			}
			return $total;
		}
	}

	if (!function_exists('count_user_votes_for_suggestion')) {
		function count_user_votes_for_suggestion($UserId,$suggestionID) {
			if ($UserId === FALSE)
				return FALSE;
			$CI =& get_instance(); 
			$query = $CI->db->get_where('votes_log',array('UserID' => $UserId,'SuggestionID' => $suggestionID)); 
			if ($query->num_rows() > 0) 
				return $query->row()->Votes;
			return FALSE; 
		}
	}

	if (!function_exists('count_suggestion_comment')) {
		function count_suggestion_comment($sugestionId) {
			$CI =& get_instance(); 
			$query = $CI->db->get_where('comments',array('SuggestionID' => $sugestionId)); 
			return $query->num_rows(); 
		}
	}

 	if (!function_exists('update_suggestion_total_votes')) {
 		function update_suggestion_total_votes($id) {
 			if ($id == NULL) 
 				return;

 			$votes = count_suggestion_votes($id); 
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id)); 
 			return $CI->db->update('suggestions',array('Total_votes' => $votes));
 		}	
 	}

 	// 0 => New
 	// 1 => Pending
 	// 2 => Planned
 	// 3 => Started 
 	// 4 => Completed
 	// 5 => Declined

 	if (!function_exists('set_suggestion_status')) {
 		function set_suggestion_status($id,$status) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id));
 			return $CI->db->update('suggestions',array('Status' => $status));	

 		}
 	}

 	if(!function_exists('count_suggestion_with_status')) {
 		function count_suggestion_with_status($status) {
 			$CI =& get_instance();
 			$CI->db->where(array('Status' => $status)); 
 			$query = $CI->db->get('suggestions'); 
 			return $query->num_rows();
 		}
 	}


 	if (!function_exists('get_suggestion_status')) {
 		function get_suggestion_status($id) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id)); 
 			$query = $CI->db->get('suggestions'); 
 			if ($query->num_rows() > 0) {
 				return $query->row()->Status;
 			}
 			return FALSE; 
 		} 
 	}

 	if (!function_exists('get_suggestion_laststatus')) {
 		function get_suggestion_laststatus($id) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id)); 
 			$query = $CI->db->get('suggestions'); 
 			if ($query->num_rows() > 0) {
 				return $query->row()->Last_status;
 			}
 			return FALSE; 
 		} 
 	}

 	if (!function_exists('get_suggestion_title')) {
 		function get_suggestion_title($id) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id)); 
 			$query = $CI->db->get('suggestions'); 
 			if ($query->num_rows() > 0) {
 				return $query->row()->Title;
 			}
 			return FALSE;
 		}
 	}



 	if (!function_exists('remove_suggestion')) {
 		function remove_suggestion($id) {
 			// remove suggestion 
 			// remove votes
 			// remove comments

 			$CI =& get_instance(); 
 			$CI->db->delete('comments',array('SuggestionID' => $id)); 
 			$CI->db->delete('votes_log',array('SuggestionID' => $id)); 
 			$CI->db->delete('suggestions',array('ID' => $id)); 

 		}
 	}

 	if (!function_exists('get_suggestion_slug_with_comment_id')) {
 		function get_suggestion_slug_with_comment_id($id) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $id)); 
 			$query = $CI->db->get('comments'); 
 			if ($query->num_rows() > 0) {
 				$SuggestionID = $query->row()->SuggestionID; 
 				$CI->load->model('suggestion_model'); 
 				$slug = $CI->suggestion_model->get_suggestion_slug($SuggestionID);
 				return $slug;
 			}
 			return FALSE;
 		}
 	}


 	if (!function_exists('get_status_id')) {
 		function get_status_id($status) {
 			$status = strtolower($status);

 			if ($status == 'pending') {
 				return 1;
 			} else if ($status == 'planned') {
 				return 2;
 			} else if ($status == 'started') {
 				return 3;
 			} else if ($status == 'completed') {
 				return 4;
 			}
 			else if ($status == 'declined') {
 				return 5;
 			}
 			return FALSE;
 		}
 	}

 	if(!function_exists('get_status')) {
 		function get_status($id) {
 			switch($id) {
 				case 1:
 					return 'Pending';
 				case 2: 
 					return 'Planned'; 
 				case 3:
 					return 'Started'; 
 				case 4: 
 					return 'Completed';
 				case 5:
 					return 'Declined';
 			}
 		}
 	}

 	if( !function_exists('get_suggestion_publisher_id')) {
 		function get_suggestion_publisher_id($suggestionID) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $suggestionID)); 
 			$query = $CI->db->get('suggestions'); 
 			if ($query->num_rows() > 0) {
 				return $query->row()->UserID;
 			}
 			return FALSE; 
 		}
 	}

 	if (!function_exists('get_suggestion_publisher')) {
 		function get_suggestion_publisher($suggestionID) {
 			$user_id = get_suggestion_publisher_id($suggestionID); 
 			if ($user_id == FALSE) 
 				return FALSE; 

 			$CI =& get_instance(); 
 			$CI->db->where(array('ID' => $user_id)); 
 			$query = $CI->db->get('users');
 			if ($query->num_rows > 0) {
 				return $query->row(); 
 			}
 			return FALSE; 
 		}
 	}

 	if (!function_exists('get_commented_users')) {
 		function get_commented_users($id) {
 			$CI =& get_instance(); 
 			$CI->db->where(array('SuggestionID' => $id )); 
 			$query = $CI->db->get('comments'); 
 			if( $query->num_rows() > 0) {
 				$users = $query->result();
				$ret = array();
				foreach ($users as $user) {
					$CI->db->where(array('ID' => $user->UserID)); 
					$query = $CI->db->get('users'); 
					if ($query->num_rows() > 0) {
						$ret = array_merge($ret,$query->result());
					}
				}
				
				return $ret;
 			}
 			return FALSE;
 		}
 	}

 	if( !function_exists('text_limiter')) {
 		function text_limiter($text) {
 			if (strlen($text) < 200) {
 				return $text;
 			}
 			
 			$limited = '';
 			$end = '...';
 			for ($i = 0; $i < 200; $i++) {
 				$limited .= $text[$i];
 			}

 			$limited .= $end;
 			return $limited;
 		}
 	}


 	if (!function_exists('get_voters_list')) {
		function get_voters_list($suggestionID) {
			$CI =& get_instance(); 
			$CI->db->where(array('SuggestionID' => $suggestionID)); 
			$query = $CI->db->get('votes_log'); 
			if ($query->num_rows() > 0) {
				$users = $query->result();
				$ret = array();
				foreach ($users as $user) {
					$CI->db->where(array('ID' => $user->UserID)); 
					$query = $CI->db->get('users'); 
					if ($query->num_rows() > 0) {
						$ret = array_merge($ret,$query->result());
					}
				}
				
				return $ret;
			}
			return FALSE;
		} 
	}

	
?>