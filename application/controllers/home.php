<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Public_Controller {

	//protected $data;

	public function __construct() {
		parent::__construct();
		$this->load->model('suggestion_model');
		$this->load->model('category_model');
		$this->load->helper('suggestion');
		$this->load->helper('util');
		if ($this->uri->segment(2) && ! $this->uri->segment(3)) {
			redirect('home/sugestions/'.$this->uri->segment(2));
		}
		
	}

	public function sugestions() {
		$this->index();
	}

	public function index()
	{
			$this->template->set_view('home')
						   ->set_page_title('Home')
						   ->add_js_file('new_suggestion.js')
						   ->add_css_file('main.css'); 			   

			$this->data['nav_selected'] = 'Home';
			
			if ($this->suggestion_model->is_Empty()) {
				$this->data['empty'] = TRUE;
			}
			else
			{
				if ($this->category_model->category_exsits())
				{
					$this->data['categories'] = $this->category_model->get_categories(); 
				}
				
				$this->load->library('pagination'); 
				$config['base_url'] 	= site_url() . '/home/'; 
				$config['total_rows']	= $this->suggestion_model->num_rows(); 
				$config['per_page']		= 5;
				$config['full_tag_open']  = '<div class="pagination" id="links">'; 
				$config['full_tag_close'] = '</div>'; 

				$this->pagination->initialize($config); 
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				$this->data['results'] = $this->suggestion_model->fetch($config['per_page'],$page);
				if (empty($this->data['results'])) {
					$this->template->set_additional_data($this->data);
					$this->template->render();
					return FALSE;
				}
			
				$this->data['links'] = $this->pagination->create_links();
				$this->load->helper('suggestion');
				foreach ($this->data['results'] as $row) {
					$row->votes = count_suggestion_votes($row->ID);
				}
			}



		if ($this->input->is_ajax_request()) {
			$html = $this->load->view('fragment/feature_item', $this->data, TRUE);
			echo json_encode(array('status' => 1, 'html' => $html));
		}
		else
		{
			$this->template->set_additional_data($this->data); 
			$this->template->render();	
		}
	}

	public function filter($filter) {
		if ($filter == NULL)
			redirect('home'); 

		$this->template->set_view('home')
					   ->set_page_title('Home')
					   ->add_js_file('new_suggestion.js')
					   ->add_css_file('main.css'); 
		$this->data['nav_selected'] = 'Home';
		
		$this->load->library('pagination'); 			   
		$config['base_url'] = site_url() . '/home/filter/'.$filter.'/'; 
		$config['per_page'] = 5;
		$config['full_tag_open'] = '<div class="pagination" id="links">'; 
		$config['full_tag_close'] = '</div>'; 
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		if ($filter == 'myideas') {
			$userID = get_connected_user_id();
			$config['total_rows'] = $this->suggestion_model->num_rows_where(array('UserID' => $userID)); 
			$this->pagination->initialize($config); 
			$this->data['results'] = $this->suggestion_model->fetch_where($config['per_page'],$page,array('UserID' => $userID)); 
			$this->data['links']   = $this->pagination->create_links(); 
			$this->load->helper('suggestion');
			if (!empty($this->data['results'])) {
				foreach ($this->data['results'] as $row) {
					$row->votes = count_suggestion_votes($row->ID);
				}	
			}
			
		} else if ($filter == 'top') {
			$config['total_rows'] = $this->suggestion_model->num_rows(); 
			$this->pagination->initialize($config); 
			$this->data['results']  = $this->suggestion_model->order_by('Total_votes');
			foreach ($this->data['results'] as $row) {
				$row->votes =  $row->Total_votes;
			}
			$this->data['links']  = $this->pagination->create_links(); 
		} 
		else if ($filter == 'new') {
			$config['total_rows'] = $this->suggestion_model->num_rows();
			$this->pagination->initialize($config); 
			$this->data['links']  = $this->pagination->create_links(); 
		} else if ($filter == 'status') {
			$status = $this->uri->segment(4); 
			if ($status == NULL)
				redirect('home'); 

			$this->load->helper('suggestion');
			$statusid = get_status_id($status); 
			$config['total_rows'] = $this->suggestion_model->num_rows_where(array('Status' => $statusid)); 
			$this->pagination->initialize($config); 
			$this->data['results'] = $this->suggestion_model->fetch_where($config['per_page'],$page,array('Status' => $statusid));
			if (!empty($this->data['results'])) {
				foreach ($this->data['results'] as $row) {
					$row->votes = count_suggestion_votes($row->ID);
				}	
			}
			$this->data['links']   = $this->pagination->create_links();

		} else if ($filter = 'category')
		{
			$category = $this->uri->segment(4);
			$category_id = $this->category_model->get_category_id_by_slug($category);
			$config['total_rows'] = $this->suggestion_model->num_rows_where(array('category_id' => $category_id));
			$this->pagination->initialize($config);
			$this->data['results'] = $this->suggestion_model->fetch_where($config['per_page'],$page,array('category_id'=>$category_id));
			if (!empty($this->data['results'])) {
				foreach($this->data['results'] as $row) {
					$row->votes = count_suggestion_votes($row->ID);
				}
			}

			$this->data['links'] = $this->pagination->create_links();
		}

			$this->template->set_additional_data($this->data); 
			$this->template->render();	
	

				   

	}

	
}