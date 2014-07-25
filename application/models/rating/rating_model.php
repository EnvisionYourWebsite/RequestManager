<?php defined('BASEPATH') OR exit('No direct script access allowed');

	
	class Rating_model extends MY_Model {

		protected $table = 'votes_log';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			$this->set_primary_key('SuggestionID');

		}

		public function rate_feature($input) {
			$input['IP'] = $this->input->ip_address();
			$input['at'] = date("Y-m-d H:i:s");
			return parent::insert($input);
		}
		public function update_rate($userId,$SuggestionID,$newrate) {
			$this->db->where(array('UserID' => $userId,'SuggestionID' => $SuggestionID));
			return $this->db->update($this->table,array('Votes' => $newrate,'Updated' =>  date("Y-m-d H:i:s"),'IP' => $this->input->ip_address()));
		}

		public function get_feature_rate($id) {
			$query = parent::get_where(array('SuggestionID' => $id)); 
			if ($query->num_rows() > 0) {
				return $query->row()->Votes;
			}
			return 0;
		}

		public function remove_user_votes($userId) {
			return parent::delete_where(array('UserID' => $userId));
		}

			
		
	}







?>