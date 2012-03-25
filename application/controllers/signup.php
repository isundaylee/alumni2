<?php
class Signup extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('signup_model'); 
		$this->load->language('signup'); 
		$this->load->language('misc'); 
		$this->load->language('homepage'); 
	}
	
	public function add($id = -1)
	{
		require_permission(1); 
	
		$this->load->model('page_model'); 
		$pages = $this->page_model->get_records(array("id" => $id)); 
		
		if (count($pages) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('misc_pid_not_exists'),	
									 	 "links" => array($this->lang->line('homepage_go_to_homepage') => "homepage"))); 
		}
		else
		{
			$signups = $this->signup_model->get_records(array("pid" => $id, 
															  "owner" => $this->session->userdata('uid')));
															  
			if (count($signups) > 0)
			{
				$this->layout->message(array("content" => $this->lang->line('signup_msg_already_signed_up'),
											 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1);"))); 
			}
			else
			{
				$this->signup_model->insert_record(array("pid" => $id, 
														 "owner" => $this->session->userdata('uid'))); 
				
				$this->layout->message(array("content" => $this->lang->line('signup_msg_done_sign_up'),	
											 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1); "))); 
			}
		}
	}
	
	public function mine()
	{
		require_permission(1); 
		
		$this->load->model('page_model'); 
		
		$data['signups'] = $this->signup_model->get_records(array("owner" => $this->session->userdata('uid'))); 
		$data['pages'] = array(); 
		
		foreach ($data['signups'] as $rid => $row)
		{
			$ps = $this->page_model->get_records(array("id" => $row->pid)); 
			$data['pages'][$rid] = $ps[0]; 
		}
		
		$this->layout->display(array("title" => $this->lang->line('signup_my_signups'), 
								  	 "extra_css" => array("signup_mine"), 
							   		 "content" => $this->load->view('signup_mine_view', $data, TRUE))); 
	}
}
?>