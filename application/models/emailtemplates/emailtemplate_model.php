<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class emailTemplate_Model extends MY_Model {

		private $table = 'emailtemplates';
		public function __construct() {
			parent::__construct();
			$this->set_table_name($this->table);
			$this->set_primary_key('Template_Name');
		}


		public function get_template_mail($template_name) {
			$query = $this->get_where(array('Template_Name' => $template_name)); 
			if ($query->num_rows() > 0)
				return $query->row();
			return FALSE;
		}

		public function set_template_mail_content($template_name,$subject,$content) {
			return $this->update($template_name,array('Subject' => $subject,'Content' => $content)); 
		}

		public function get_template_names() {
			$data = parent::get_all(); 
			if ($data == FALSE) {return FALSE;}
		
			foreach ($data as $row) {
				$ret[] = $row->Template_Name;
			}

			return $ret;
		}

		

	}

?>