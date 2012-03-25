<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->language('index'); 
		$this->load->language('membership'); 
		$this->load->model('membership_model');
	}

	public function index()
	{
		redirect('index/query'); 
	}
	
	public function query()
	{
		if (!isset($_POST['page'])) $_POST['page'] = 1; 
		$data['querydata'] = $_POST; 
		$data['data'] = $this->membership_model->get_records_fuzzy($_POST, ($_POST['page'] - 1) * $this->config->item('index_entries_per_page'), $this->config->item('index_entries_per_page')); 
		$data['totpage'] = floor(($this->membership_model->get_records_count_fuzzy($_POST)- 1) / $this->config->item('index_entries_per_page')) + 1; 
		$this->layout->display(array("title" => $this->lang->line('index_query_alumni'),
									 "extra_css" => array("index_query"), 
									 "content" => $this->load->view('index_query_view', $data, TRUE))); 
	}
	
	public function by($field = 'id', $value = '0')
	{
		// $content = '<html><body onload="document.getElementById(' . "'"
	}
}
