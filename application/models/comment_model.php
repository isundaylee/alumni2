<?php
class Comment_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
   	}
   	
   	function get_records($delim = array(), $arr = FALSE)
   	{
   		$this->db->order_by("time", "desc"); 
   		$this->db->where($delim); 
   		$query = $this->db->get('comments'); 
	   	if ($arr) return $query->result_array(); 
	   	else return $query->result(); 
   	}
   	
   	function get_records_limit($delim = array(), $offset = 0, $limit = 10000)
   	{
   		$this->db->order_by("time", "desc"); 
   		$this->db->where($delim); 
   		$this->db->limit($limit, $offset); 
   		$query = $this->db->get('comments'); 
	   	return $query->result(); 
   	}
   	
   	function get_records_count($delim = array())
   	{
   		foreach ($this->config->item('comment_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->where($field, $delim[$field]); 
   		return $this->db->count_all_results('comments'); 
   	}
   	
   	function insert_record($data)
   	{
   		foreach ($this->config->item('comment_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]); 
   		$this->db->insert('comments');
   		return $this->db->insert_id();  
   	}
}
?>