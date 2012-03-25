<?php
class Membership_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
   	}
   	
   	function get_records($delim = array(), $arr = FALSE)
   	{
   		$this->db->where($delim); 
   		$query = $this->db->get('memberships'); 
	   	if ($arr) return $query->result_array(); 
	   	else return $query->result(); 
   	}
   	
   	function get_records_count_fuzzy($delim = array())
   	{
   		foreach ($this->config->item('membership_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->like($field, $delim[$field]); 
   		return $this->db->count_all_results('memberships'); 
   	}
   	
   	function get_records_fuzzy($delim = array(), $offset = 0, $limit = 10000)
   	{
   		foreach ($this->config->item('membership_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->like($field, $delim[$field]); 
   		$this->db->limit($limit, $offset); 
   		$query = $this->db->get('memberships'); 
   		return $query->result(); 
   	}
   	
   	function insert_record($data)
   	{ 
   		foreach ($this->config->item('membership_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]);
   		$this->db->insert('memberships'); 
   		return $this->db->insert_id();
   	}
   	
   	function update_record($id, $data)
   	{
   		$this->db->where('id', $id); 
   		foreach ($this->config->item('membership_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]);
   		$this->db->update('memberships'); 
   		return; 
   	}
   	
   	function validate_signin($email, $pass)
	{
		$this->db->where(array("email" => $email, "password" => $pass)); 
		if (count($this->db->get('memberships')->result())) return TRUE; 
		return FALSE; 
	}
	
	function change_password($id, $pass)
	{
		$this->db->where(array("id" => $id)); 
		$this->db->set("password", $pass); 
		$this->db->update('memberships'); 
	}
	
}
?>