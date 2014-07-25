<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class MY_Model extends CI_Model {

		// The database table to use
		
		protected $_table;

		// The primary key, by default set to `id`, for use in some functions.
		protected $primary_key = 'id';

		public function __construct($table = NULL) 
		{
			parent::__construct(); 
			$this->_table = $table;
			$this->load->database();

		}


		public function get_all($type = 0)
		{
			$this->db->from($this->_table);

			$query = $this->db->get();

			if(!empty($query) && $query->num_rows() > 0)
			{
				if($type == 0)
				{
					$return = $query->result();
				}
				else
				{
					$return = $query->result_array();
				}

				return $return;
			}

			return FALSE;
		}

		public function get_where($where)
		{
			$query = $this->db->get_where($this->_table,$where);
			return $query;
		}

		public function get_record($id) {
			$query = $this->db->get_where($this->_table,array($this->primary_key => $id)); 
			if ($query->num_rows()> 0) {
				return $query->row();
			} 
			return FALSE; 
		}

		public function record_exists($where) {
			$this->db->select()->from($this->_table)->where($where);
			$query = $this->db->get();
			return ($query->num_rows() != 0);
		}

		public function set_table_name($name = NULL)
		{
			$this->_table = $name; 
		}

		public function set_primary_key($key)
		{
			$this->primary_key = $key;
		}

		public function get_table()
		{
			return $this->_table;
		}

		public function get_primary_key()
		{
			return $this->primary_key;
		}

		public function update($primary_value,$data)
		{
			return $this->db->where($this->primary_key,$primary_value)
							->set($data)
							->update($this->_table);
		}

		public function where($where,$value)
		{

			if(!empty($where))
			{
				if(is_string($where))
				{
					$this->db->where($where,$value);
				}
				else if (is_array($where))
				{
					$this->db->where($where);
				}
			}

			return $this;
		}

		public function limit($limit=0,$offset=0)
		{
			$this->db->limit($limit,$offset);
			return $this;
		}


		public function delete($id)
		{
			return $this->db->where($this->primary_key,$id)
							->delete($this->_table);
		}

		public function delete_where($where) {

			return $this->db->where($where)
							->delete($this->_table);
		}

		public function delete_many($primary_values)
		{
			return $this->db->where_in($this->primary_key,$primary_values)
							->delete($this->_table);
		}

		public function insert($data)
		{
			return $this->db->insert($this->_table,$data);
		}

		public function getCurrentSelectedTable()
		{
			return $_table;
		}

		public function num_rows() {
			return $this->db->count_all($this->_table);
		}

		public function num_rows_where($where) {
			$this->db->where($where); 
			$query = $this->db->get($this->_table); 
			return $query->num_rows();
		}

		public function fetch($limit,$start) {
			$this->db->limit($limit,$start);
			$query = $this->db->get($this->_table); 

			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$data[] = $row;
				}

				return $data;
			}
			return FALSE;
		}

		public function fetch_where($limit,$start,$where) {
			$this->db->where($where); 
			$this->db->limit($limit,$start); 
			$query = $this->db->get($this->_table); 

			if ($query->num_rows() > 0) {
				foreach( $query->result() as $row) {
					$data[] = $row;
				}
				return $data;
			}
			return FALSE;
		}

		public function isEmpty() {
			if ($this->num_rows() == 0)
				return TRUE; 
			return FALSE;
		}

		public function isEmpty_where($where) {
			$this->db->where($where);
			$query = $this->db->get($this->_table); 
			if ($query->num_rows() == 0)
					return TRUE;
			return FALSE; 
		}



	}
?>