<?php defined('BASEPATH') or exit('No direct script access allowed');
	Class CI_Rating {
		protected $CI; 

		protected $voteslog_table   = "votes_log";
		protected $userStatus_table = "users_info";

		public function __construct() {
			$this->CI =& get_instance();
			$this->ci()->load->model('rating/rating_model');
			$this->ci()->load->helper('account'); 
		}

		public function ci() {
			return $this->CI;
		}

		public function approve($id) {
			return $this->ci()->rating_model->approve($id);
		}

		public function has_user_voted_for_suggestion($userId,$SuggestionId) {
			$query = $this->ci()->db->get_where($this->voteslog_table,array('UserID' => $userId,'SuggestionId' => $SuggestionId)); 
			if ($query->num_rows() > 0)
				return TRUE;
			return FALSE;
		}
 
		public function user_left_vote($userId) {
			return get_user_votes_left_by_id($userId);
		}

		public  function update_user_left_vote($userId,$new) {
			$left = $this->user_left_vote(); 
			$left += $new; 
			$this->ci()->db->update('users_info',array('UserID' => $userId),array('votesleft' => $left));
		}

		public function count_user_left_votes($userId) {

			// Anonymous Users
			if ($userId === 0) {
				return CI_Settings::get('Max_votes');
			}

			$max_votes = CI_Settings::get('Max_votes'); 
			$left_votes = $max_votes - count_user_votes($userId); 
			$query = $this->ci()->db->get_where('users_info',array('UserId' => $userId));
			if ($query->num_rows() > 0) {
				$this->ci()->db->where('UserId',$userId);
				$this->ci()->db->update('users_info',array('votesleft' => $left_votes));
			}
			else
			{
				$this->ci()->db->insert('users_info',array('UserId' => $userId,'votesleft' => $left_votes));
			}

			return $left_votes;
		}

		public function rate_feature($userId,$id,$rate) {
				$leftvotes = $this->count_user_left_votes($userId);
			if ((($leftvotes + count_user_votes_for_suggestion($userId,$id)) - $rate) < 0)
			{
				return FALSE;
			}

			$result = $this->ci()->rating_model->rate_feature(array('UserId' => $userId,'SuggestionId' => $id,'Votes' => $rate));
			return $result; 
		}

		public function update_rate($userId,$SuggestionId,$newrate) {
			if ($newrate == 0) {
				return $this->_remove_user_rate_log($userId,$SuggestionId);
			}
				$leftvotes = $this->count_user_left_votes($userId);
			if((($leftvotes + count_user_votes_for_suggestion($userId,$SuggestionId)) - $newrate) < 0)
			{
				return FALSE;
				
			}

			$result = $this->ci()->rating_model->update_rate($userId,$SuggestionId,$newrate);
			return $result;
		}

		public function get_feature_rate($id) {
			return $this->ci()->rating_model->get_feature_rate($id);
		}

		public function remove_rate($id,$rate) {
			$votes = $this->get_feature_rate($id); 
			if ($votes != FALSE) {
				return $this->ci->rating_model->rate_feature($id,array('Votes' => ($votes-$rate)));
			}
			return FALSE;
		}

		public function remove_user_rate($userId,$fetureId,$rate) {
			$this->remove_rate($featureId,$rate); 
			$this->update_user_left_vote($userId,$rate);
			$this->_remove_user_rate_log($userId,$featureId);
		}

		public function _log_vote($input) {
			$input['On'] 	= date();
			$input['IP'] 	= $this->input->ip_address();  
			return $this-ci()->db->insert($this->voteslog_table,$input);
		}

		public function _remove_user_rate_log($userId,$SuggestionId)  {
			$this->ci()->db->where(array('UserID' => $userId,'SuggestionId' => $SuggestionId))
							->from($this->voteslog_table); 
			return $this->ci()->db->delete();
		}

		public function give_votes_to_users($SuggestionId) {
			return $this->ci()->db->delete('votes_log',array('SuggestionID' => $SuggestionId));
		}





	}
?>