<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Settings extends Admin_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('category_model','category');

		}

		public function index() {
			$this->template->set_page_title('Settings')
						   ->set_view('admin/settings')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');

			$this->data['nav_selected'] = 'Settings';

			$this->template->set_additional_data($this->data);
			$this->template->render();
		}


		public function categories() {

			$this->template->set_page_title('Categories')
						   ->set_view('admin/categories')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');

			$this->data['nav_selected'] = 'Settings';

			$this->template->set_additional_data($this->data);
			$this->template->render();
		}

		public function new_category() {
			if ($this->input->post('category')) {

				$category = $this->input->post('category');
				if ($this->category->add_new_category($category))
				{
					redirect('admin/settings?status=1#categories');
				}
				else
				{
					redirect('admin/settings?status=0#categories');
				}

			}

			$this->template->set_page_title('New category')
						   ->set_view('admin/new_category')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');

			$this->data['nav_selected'] = 'Settings';
			
			$this->template->set_additional_data($this->data);
			$this->template->render();			   
		}

		public function remove_category($slug=null){
			if($slug != null)
			{	
				$_id = $this->category->get_category_id_by_slug($slug);
				$this->load->model('suggestion_model');
				$this->suggestion_model->reset_category_id($_id);
				$this->category->remove_category($_id);
			}

			redirect('admin/settings?cat=1');
		}
	} 
 ?>