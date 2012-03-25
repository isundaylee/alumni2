<?php
class Page_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
   	}
   	
   	function get_records($delim = array(), $arr = FALSE)
   	{
   		$this->db->order_by("time", "desc"); 
   		$this->db->where($delim); 
   		$query = $this->db->get('pages'); 
	   	if ($arr) return $query->result_array(); 
	   	else return $query->result(); 
   	}
   	
   	function get_records_fuzzy($delim = array(), $offset = 0, $limit = 10000)
   	{
   		$this->db->order_by("time", "desc"); 
   		foreach ($this->config->item('page_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->like($field, $delim[$field]); 
   		$this->db->limit($limit, $offset);
   		$query = $this->db->get('pages'); 
   		return $query->result();  
   	}
   	
   	function get_record_for_day($day)
   	{
   		$this->db->order_by("time", "desc"); 
   		$this->db->like("time", $day); 
   		$this->db->not_like("type", "PP"); 
   		$this->db->limit(1);
   		$query = $this->db->get('pages'); 
   		return $query->result();  
   	}
   	
   	function get_records_count_fuzzy($delim = array())
   	{
   		foreach ($this->config->item('page_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->like($field, $delim[$field]); 
   		return $this->db->count_all_results('pages'); 
   	}
   	
   	function insert_record($data)
   	{
   		foreach ($this->config->item('page_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]); 
   		$this->db->insert('pages');
   		return $this->db->insert_id();  
   	}
   	
   	function update_record($id, $data)
   	{
   		$this->db->where('id', $id); 
   		foreach ($this->config->item('page_fields') as $field) if (isset($data[$field])) $this->db->set($field, $data[$field]); 
   		$this->db->update('pages');
   		return;  
   	}
   	
   	function delete_record($delim = array())
   	{
   		foreach ($this->config->item('page_fields') as $field) if (isset($delim[$field]) && $delim[$field] != "") $this->db->where($field, $delim[$field]); 
   		$query = $this->db->delete('pages'); 
   		return; 
   	}
}
?>