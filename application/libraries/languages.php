<?php defined('BASEPATH') or exit('No direct script access allowed');

	class CI_Languages {
		protected $defaultlang;
		protected $currentlang;

		public function __construct() {
			$this->defaultlang = strtolower(CI_Settings::get('DefaultLanguage'));
			if ($this->defaultlang == FALSE) {
				CI_Settings::insert('DefaultLanguage','English');
				$this->defaultlang == "english";
			}
		}


		// Change Language only for this session
		public function change_current_language($lang) {
			if(!is_dir('./application/language/'.strtolower($lang))) 
				return FALSE;

			$this->currentlang = $lang;
		}


		// Change default Language 
		public function change_default_language($lang) {
			if(!is_dir('./application/language/'.strtolower($lang))) 
				return FALSE;

			$this->currentlang = $lang;
			CI_Settings::set('DefaultLanguage',$lang);
		}

		// Get Current language 
		public function get_current_language() {
			return ($this->currentlang == '') ? $this->defaultlang : $this->currentlang;
		}

		public static function get_available_languages() {
			$list =array();
			if ($handle = opendir('./application/language/')) {
				while (FALSE != ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$list[] = $entry;
					}
				}
			}
			closedir($handle);
			return $list;
		}
	}

?>