<?php 

if (!function_exists('contain_object')) {
	function contain_object($input) {
		if (!is_array($input))
			return FALSE; 


		foreach ($input as $row) {
			if (is_object($row)) {
				return TRUE; 
			}
		}

		return FALSE; 
	}
}


function btn_edit($uri) {
	return anchor($uri, '<span class="glyphicon glyphicon-edit"></span>'); 
}
function btn_delete($uri) {
	return '<a class="delete" data-link="'. $uri .'" data-toggle="modal" href="#delete-modal"><span class="glyphicon glyphicon-remove"></span></a>';
}

function _ui_status($status) {
	switch($status)
	{
		case 1:
			return '<span class="label label-info"><em>' .lang('pending')  .'</em></span>';
		case 2:
			return '<span class="label label-warning"><em>'.lang('planned') .'</em></span>';
		case 3:
			return '<span class="label label-primary"><em>' .lang('started') .'</em></span>';
		case 4:
			return '<span class="label label-success"><em>' .lang('completed') .'</em></span>';
		case 5:
			return '<span class="label label-danger"><em>' .lang('declined') .'</em></span>';
	}
                    
}


if (!function_exists('get_mail_templates_names')) {
	function get_mail_templates_names($withDelimiter=TRUE) {
		$CI =& get_instance(); 
		$query = $CI->db->get('emailtemplates'); 
		$data = NULL;
		if ($query->num_rows() > 0) {
			if ($withDelimiter) {
				foreach ($query->result() as $row) {
					$data[] = '<option>' . $row->Template_Name  . '</option>';
				}
			}
			else {
				return $query->result();
			}
			
			return $data;
		}
		return FALSE;
	}
}

function account_status($id) {
	if ($id == 0) {
		return 'Active';
	}
	elseif ($id == 1) {
		return 'Email Not Validated yet.'; 
	} 
	elseif ($id == 4) {
		return 'Account Blocked';
	}

	return FALSE;
}

function get_no_reply_address() {
	$ci =& get_instance(); 
	$query = $ci->db->get('core_sites');
	if ($query->num_rows() > 0) {
		return 'noreply@'.$query->row()->domain;
	}
	return FALSE;
}


?>