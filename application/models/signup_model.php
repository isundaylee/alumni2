<?php
class Signup_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
   	}
   	
   	function get_records($delim = array(), $arr = FALSE)
   	{
   		$this->db->where($delim); 
   		$query = $this->db->get('signups'); 
	   	if ($arr) return $query->result_array(); 
	   	else return $query->result(); 
   	}
   	
   	function insert_record($data)
   	{
   		foreach ($this->config->item('signup_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]); 
   		$this->db->insert('signups');
   		return $this->db->insert_id();  
   	}
}
?>