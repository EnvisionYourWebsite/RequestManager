<?php defined('BASEPATH') or exit('No direct script access allowed');

class CI_Mail {

 	protected $ci;

 	public function __construct() {
 		$this->ci =& get_instance(); 
 		$this->ci->load->model('emailtemplates/emailTemplate_Model');
 		$this->ci->load->library('email');


 	}

 	public function send_email($data = array()) {

 		$TemplateName = $data['TemplateName']; 
 		unset($data['TemplateName']); 
 		
 		$template = $this->ci->emailTemplate_Model->get_template_mail($TemplateName);

 		if ($template != FALSE) {

 			$from 		= isset($data['from']) ? $data['from'] : CI_Settings::get('SERVER_EMAIL'); 
 			$from_name  = isset($data['name']) ? $data['name'] : NULL;
 			$to 		= isset($data['to'])   ? $data['to']   : NULL; 

 			$subject = $template->Subject;
 			$body    = $template->Content;

 			$body = str_replace($data['Hooks']['SEARCH'],$data['Hooks']['SUBJECTS'],$body); 

 			$this->ci->email->from($from,$from_name); 
 			$this->ci->email->to($to); 
 			$this->ci->email->subject($subject); 
 			$this->ci->email->message($body);

 			
 		 	if ($subject != "" && $body != "") {
 				return (bool)$this->ci->email->send();
 			}
 			
 		}

 		return FALSE; 

 	}
 }




?>