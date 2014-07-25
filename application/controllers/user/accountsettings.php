<?php  
	
	class AccountSettings extends AuthenticatedUser_Controller
	{
		
		function __construct()
		{
			parent::__construct();
		}

		public function index(){
			$this->template->set_page_title('Account Settings')
						   ->set_view('user/account_settings')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');
            
						   

			$this->template->set_additional_data($this->data);
			$this->template->render();

		}
	}


?>