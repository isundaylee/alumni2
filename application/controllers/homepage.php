<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends CI_Controller {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->language('homepage'); 
	}

	public function index()
	{
		$data = array(); 
		
		$data['days'] = array(); 
		
		$this->load->model('page_model'); 
		
		for ($i=1; $i<=date('t'); $i++) {
			$D = date('Y-m-' . ($i < 10 ? "0" . $i : $i)); 
			$pages = $this->page_model->get_record_for_day($D); 
			if (count($pages) > 0) $data['days'][$i] = $pages[0]; 
		}
	
		$this->layout->display(array('title' => $this->lang->line('misc_welcome'),
									 'extra_css' => array('homepage'), 
									 'content' => $this->load->view('homepage_view', $data, TRUE))); 
	}
}
