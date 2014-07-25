<?php defined('BASEPATH') or exit('No direct script access allowed');

	class InstallerLib {

		private $ci;

		function __construct() {
			$this->ci =& get_instance();
		}

		public function mysql_accept() {
			$server = $this->ci->session->userdata('hostname').':'.$this->ci->session->userdata('port');
			$username = $this->ci->session->userdata('username');
			$password = $this->ci->session->userdata('password');

			if ($db = @mysql_connect($server, $username, $password)) {

				@mysql_close($db);

				return ($this->mysql_server_version >= 5);
			}

			@mysql_close($db);
			return FALSE;

		}

		public function mysql_available() {
			return function_exists('mysql_connect');
		}

		public function test_db_connection()
		{
			$hostname = $this->ci->session->userdata('hostname');
			$username = $this->ci->session->userdata('username');
			$password = $this->ci->session->userdata('password');
			$port	  = $this->ci->session->userdata('port');

			return $this->mysql_available() && @mysql_connect("$hostname:$port", $username, $password);
		}

		public function upgrade() {
			// Load the database Config
			// Upgrade the database

			if (file_exists('../application/config/database.php')) {
				include('../application/config/database.php');
			}
			else {
				 die('database file not found! Please reinstall from scratch.');
			}
			
			$this->db = @mysql_connect($db[$active_group]['hostname'].':'.$db[$active_group]['port'], 
							$db[$active_group]['username'], 
							$db[$active_group]['password']);

			if (!$this->db) {
				return array('status' => FALSE,'message' => 'The installer could not connect to the MySQL server or the database, be sure to enter the correct information.');
			} 

			$sql = file_get_contents('./sql/upgrade.sql'); 
			if ($this->ci->session->userdata('so_fb')) {
				$sql = str_replace('{FB_SOCIAL_CONNECT}', TRUE, $sql);
			}
			else 
			{
				$sql = str_replace('{FB_SOCIAL_CONNECT}', FALSE, $sql);
			}

			$sql = str_replace('{DB_NAME}', $db[$active_group]['database'], $sql);

			if (!mysql_select_db($db[$active_group]['database'],$this->db)) {
					return array(
						'status'	=> FALSE,
						'message'	=> '',
						'code'		=> 101
					);
			}

			if ($this->_process_schema($sql, FALSE)) {
				return array(
						'status'	=> FALSE,
						'message'	=> mysql_error($this->db),
						'code'		=> 104
					);
			}

			if ($this->ci->session->userdata('so_fb')) {
				if(!$this->write_social_file('facebook')) {
					return array(
							'status'	 => FALSE,
							'message'	 => '',
							'code'		 => 106
						);
				}
			}

			// Check Tables

			mysql_query("IF NOT EXISTS( SELECT NULL
	            FROM INFORMATION_SCHEMA.COLUMNS
	            WHERE table_name = 'suggestions'
	            AND table_schema = '".$db[$active_group]['database']."'
	            AND column_name  = 'category_id')  THEN
				ALTER TABLE  `suggestions` ADD `category_id` BIGINT NULL DEFAULT NULL AFTER  `Description`;
				END IF;"
			,$this->db);

			mysql_query("IF NOT EXISTS( SELECT NULL
	            FROM INFORMATION_SCHEMA.COLUMNS
	            WHERE table_name = 'users'
	            AND table_schema = '".$db[$active_group]['database']."'
	            AND column_name  = 'Lastlogout')  THEN
				ALTER TABLE  `users` ADD `Lastlogout` datetime NULL DEFAULT NULL AFTER `LastLogin`;
				END IF;"
			,$this->db);

			mysql_query("IF NOT EXISTS( SELECT NULL
	            FROM INFORMATION_SCHEMA.COLUMNS
	            WHERE table_name = 'votes_log'
	            AND table_schema = '".$db[$active_group]['database']."'
	            AND column_name  = 'Lastlogout')  THEN
				ALTER TABLE  `votes_log` CHANGE  `On`  `at` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00';
				END IF;"
			,$this->db);

			return array('status' => TRUE);

		}



		public function install() {
			// Database 
			$server = $this->ci->session->userdata('hostname').':'.$this->ci->session->userdata('port');
			$username = $this->ci->session->userdata('db_username');
			$password = $this->ci->session->userdata('db_password');
			$database = $this->ci->session->userdata('database');

			// Super Admin account 
			$data['username'] = $this->ci->session->userdata('username');
			$data['password'] = $this->ci->session->userdata('password'); 
			$data['email']	  = $this->ci->session->userdata('email'); 


			$user_salt = substr(sha1(uniqid(rand(), TRUE)), 0, 10);
			$data['password'] = sha1($data['password'].$user_salt);

			if (!$this->db = @mysql_connect($server, $username, $password))
			{
				return array('status' => FALSE,'message' => 'The installer could not connect to the MySQL server or the database, be sure to enter the correct information.');
			}

			$sql = file_get_contents('./sql/default.sql'); 
			$sql = str_replace('{EMAIL}', $data['email'], $sql); 
			$sql = str_replace('{USERNAME}', mysql_real_escape_string($data['username']), $sql); 
			$sql = str_replace('{PASSWORD}', mysql_real_escape_string($data['password']), $sql); 
			$sql = str_replace('{SALT}', $user_salt, $sql); 
			$sql = str_replace('{NOW}', time(), $sql); 
			$sql = str_replace('{IP}', $this->ci->input->ip_address(), $sql);
			$sql = str_replace('{WEBSITE_ADDR}', preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']), $sql); 
			$sql = str_replace('{ADMIN_EMAIL}', $data['email'], $sql); 
			$sql = str_replace('{SERVER_EMAIL}', 'noreply@'.preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']), $sql);

			if ($this->ci->session->userdata('so_fb')) {
				$sql = str_replace('{FB_SOCIAL_CONNECT}', TRUE, $sql);
			}
			else 
			{
				$sql = str_replace('{FB_SOCIAL_CONNECT}', FALSE, $sql);
			}

			// Do we want to create the database using the installer ?
				/*if (!empty($data['create_db'] ))
				{
					mysql_query('CREATE DATABASE IF NOT EXISTS '.$database, $this->db);
				
				}*/

				if (!mysql_select_db($database, $this->db) )
				{
					return array(
						'status'	=> FALSE,
						'message'	=> '',
						'code'		=> 101
					);
				}

				if (!$this->_process_schema($sql, FALSE) )
				{
					return array(
						'status'	=> FALSE,
						'message'	=> mysql_error($this->db),
						'code'		=> 104
					);
				}

				mysql_query(sprintf(
					"INSERT INTO settings (OptionName, OptionValue) VALUES ('%s', '%s');",
					'Website_Address',
					preg_replace('/^www\./', '', $_SERVER['SERVER_NAME'])
				));

				mysql_close($this->db);

				if (!$this->write_db_file($database))
				{
					return array(
								'status'	=> FALSE,
								'message'	=> '',
								'code'		=> 105
							);
				}

				if ($this->ci->session->userdata('so_fb')) {
					if(!$this->write_social_file('facebook')) {
						return array(
								'status'	 => FALSE,
								'message'	 => '',
								'code'		 => 106
							);
					}
				}

				return array('status' => TRUE);
		}

		private function _process_schema($schema_file, $is_file = TRUE)
		{
			if ( $is_file == TRUE )
			{
				$schema 	= file_get_contents('./sql/' . $schema_file . '.sql');
			}
			else
			{
				$schema 	= $schema_file;
			}

			$queries = explode('-- split --', $schema);

			foreach($queries as $query)
			{
				
				$query = rtrim(trim($query), "\n;");
				@mysql_query($query, $this->db);

				if (mysql_errno($this->db) > 0)
				{
					return FALSE;
				}
			}

			return TRUE;
		}

		public function write_db_file($database) {
			$port = $this->ci->session->userdata('port');

			$replace = array(
				'__HOSTNAME__' => $this->ci->session->userdata('hostname'),
				'__USERNAME__' => $this->ci->session->userdata('db_username'),
				'__PASSWORD__' => $this->ci->session->userdata('db_password'),
				'__DATABASE__' => $database,
				'__PORT__'     => $port ? $port : 3306,
				'__DRIVER__'   => class_exists('mysqli') ? 'mysqli' : 'mysql'
			);

			return $this->_write_file_vars('../application/config/database.php', './assets/config/database.php', $replace);
		}

		public function write_social_file($social) {
			if ($social == 'facebook') {
				$replace = array(
					'__FBAPPID__'	 => $this->ci->session->userdata('fb_appid'),
					'__FBAPPSECRET__'=> $this->ci->session->userdata('fb_secret'),
				);

				return $this->_write_file_vars('../application/config/facebook.php','./assets/config/facebook.php',$replace);
			}
		}

		private function _write_file_vars($destination, $template, $replacements)
		{
			return (file_put_contents($destination, str_replace(array_keys($replacements), $replacements, file_get_contents($template))) !== FALSE);
		}

		function write_config_file()
		{
		// Open the template
			$template = file_get_contents('./assets/config/config.php');

			$server_name = $this->ci->session->userdata('http_server');
			$supported_servers = $this->ci->config->item('supported_servers');

			// Able to use clean URLs?
			if ($supported_servers[$server_name]['rewrite_support'] !== FALSE)
			{
				$index_page = '';
			}

			else
			{
				$index_page = 'index.php';
			}

			// Replace the __INDEX__ with index.php or an empty string
			$new_file = str_replace('__INDEX__', $index_page, $template);

			// Open the database.php file, show an error message in case this returns false
			$handle = @fopen('../system/cms/config/config.php','w+');

			// Validate the handle results
			if ($handle !== FALSE)
			{
				return fwrite($handle, $new_file);
			}

			return FALSE;
		}
	}


?>