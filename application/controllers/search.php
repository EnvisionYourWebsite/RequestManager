<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Search extends Public_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('suggestion_model');
			$this->load->model('category_model');
		}

		public function search_for() {
			if ($this->input->is_ajax_request()) {
				$keyword = $this->input->post('keyword',TRUE); 
				if (!empty($keyword)) {
					$query = $this->suggestion_model->search_limit($keyword,3);
					if ($query->num_rows() > 0) {
						$data = array('results' => $query->result());
						$this->load->helper('suggestion');
						foreach ($data['results'] as $row) {
							$row->votes = count_suggestion_votes($row->ID);
						}
						return $this->_send_response($data,1);
					}
					else
					{
						return $this->_send_response(NULL, 0);
					}
				}
			}
		}

		private function _preparehtml($results) {
			if (is_array($results)) {
				return $this->load->view('fragment/feature_item',$results,TRUE);
			}
		}

		private function _send_response($data,$status) {
			if ($data == null) 
			{
				echo json_encode(array('status' => $status));
				exit();
			}

			$html = $this->_preparehtml($data);

			echo json_encode(array('html' => $html,'status' => $status));
		}



		
	}



?>