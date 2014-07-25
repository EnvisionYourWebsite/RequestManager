<?php defined('BASEPATH') or exit('No direct script access allowed');

	class CI_Notifier {

		protected $ci; 
		
		public function __construct() {
			$this->ci =& get_instance();
			$this->ci()->load->model('settings/users/usersettings_model'); 
			$this->ci()->load->library('Mail'); 
			$this->ci()->load->helper('account'); 
			$this->ci()->load->helper('suggestion');

			Events::register('notify',array($this,'notify_users'));

		}

		public function ci() {
			return $this->ci;
		}

		public function send_mails($users,$mail_template,$other=NULL) {
			foreach ($users as $user) {
				$data['TemplateName'] = $mail_template; 
				$data['to'] 	= $user->email;
				$data['Hooks'] 	= $other;
				$this->ci()->mail->send_email($data);
				unset($data);
			}

		}

		public function notify_users($data) {
			if (is_array($data)) {
				if($data['action'] == 'status_changed') {
					$suggestionId = $data['suggestionId']; 
					$status 	  = get_status($data['status']);
					$pub_user  	  = get_suggestion_publisher($suggestionId); 
					$users        = get_voters_list($suggestionId);
					$users        = array_merge((array)$users,(array)$pub_user);
					$users	      = get_only_subscribed_users('live_status',$users); 

					if (empty($users) || $users == FALSE) {
						return FALSE;
					}

					$this->ci()->load->model('suggestion_model');
					$slug = $this->ci()->suggestion_model->get_suggestion_slug($suggestionId);
					// Send Mail;


					if (!is_array($users)) {
						$users[] = $users;
					}

					
					foreach ($users as $user) {
							$data['to'] = $user->email;
							$data['TemplateName'] = 'Status Changed';
							$data['Hooks'] = array("SEARCH"   => array("{username}","{suggestioname}",'{laststatus}','{status}','{link}'),
										   		   "SUBJECTS" => array($user->Username,get_suggestion_title($suggestionId),get_status(get_suggestion_laststatus($suggestionId)),$status,site_url() . '/suggestion/details/'.$slug));

							$this->ci()->mail->send_email($data);
							unset($data);
					}
						
					
				}
				elseif ($data['action'] == 'new_suggestion') {
					// Notify Super Admins & Simple Admins 
					if (CI_Settings::get('NotifyAdmin')) {
						$suggestionId = $data['suggestionId']; 
						$link 		  = $data['suggestionUrl'];

						if(is_admin(get_suggestion_publisher_id($suggestionId))) {
							return FALSE;
						}

						// Send Mail
						$data['to'] = CI_Settings::get('Admin_Email'); // Super Admin Email
						$data['TemplateName'] = "New Suggestion";
						$data['Hooks']		  = array("SEARCH" 	 => array('{authorname}','{link}'),
								  			      	  "SUBJECTS" => array(get_username_byId(get_suggestion_publisher_id($suggestionId)),$data['suggestionUrl']));

						$this->ci()->mail->send_email($data);
						unset($data);
					}

				}
				elseif ($data['action'] == 'new_registration')
				{
					if (CI_Settings::get('NotifyAdmin'))
					{
						$username = $data['username']; 
						$email    = $data['email']; 

						$data['to'] = CI_Settings::get('Admin_Email'); 
						$data['TemplateName'] = "New Registration";
						$data['Hooks'] = array("SEARCH"   => array('{username}','{email}'),
											   "SUBJECTS" => array($username,$email)); 

						$this->ci()->mail->send_email($data); 
						unset($data);
					}
				}	
				/*elseif ($data['action'] == 'new_comment') {
					// Notify Interested Users 
					 
					$suggestionId = $data['suggestionId'];
					$pub_user  	  = get_suggestion_publisher($suggestionId);
					$content 	  = $data['comment_content']; 
					$this->ci()->load->model('suggestion_model');
					$slug 		  = $this->ci()->suggestion_model->get_suggestion_slug($suggestionId);
					
					$users = get_commented_users($suggestionId); 
					$users = array_merge((array)$users,(array)$pub_user);
					$users = get_only_subscribed_users('live_status',$users);	

					// Send Mails
					if(empty($users) || $users == FALSE)
						return FALSE;

					foreach($users as $user) {
						$data['to'] 			= $user->email; 
						$data['TemplateName']	= 'New Comment'; 
						$data['Hooks']			= array("SEARCH" 	=> array("{username}","{SuggestionTitle}",'{comment_author}','{link}'),
													 	"SUBJECTS"	=> array($user->Username,get_suggestion_title($suggestionId),get_username_byId($data['user_id']),site_url() . '/suggestion/details/'.$slug));

						$this->ci()->mail->send_email($data);
						unset($data);
					}
				}*/
				elseif ($data['action'] == 'admin_added_user') {
					$username = $data['Username'];
					$mail['to'] 			= $data['email']; 
					$mail['TemplateName']	= 'New User Added by Admin';
					$mail['Hooks']			= array('SEARCH' 	=> array('{username}','{password}','{website_addr}'),
													'SUBJECTS'	=> array($username,$data['password'],CI_Settings::get('Website_Address')));

					$this->ci()->mail->send_email($mail); 
					unset($mail); 
					unset($data);
				}
			}
		
		}



	}

?>