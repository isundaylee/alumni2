<?php
class Layout extends CI_Model {

    function __construct()
    {
        parent::__construct();
   	}
   	
    function display($data)
    {
    	$this->load->model('membership_model');
    	$this->load->language('membership'); 
    
    	$header_data = array(); 
		
		if (!signed_in()) $header_data['username'] = $this->lang->line('misc_guest'); 
		else
		{
			$users = $this->membership_model->get_records(array("id" => $this->session->userdata('uid'))); 
			$header_data['username'] = $users[0]->name; 
		}
		
    	$data['css'] = $this->config->item('base_url') . $this->config->item('css'); 
    	$data['css_base'] = $this->config->item('base_url') . "stylesheets/"; 
    	$data['header'] = $this->load->view('header_view', $header_data, TRUE); 
    	$data['footer'] = $this->load->view('footer_view', '', TRUE); 
    	
    	$this->load->view('layout_view.php', $data); 
    }
    
    function internal_error($data)
    {
    	$tmp['base'] = $this->config->item('base_url'); 
    	$tmp['css'] = $this->config->item('base_url') . $this->config->item('css'); 
		$tmp['title'] = 'Error: ' . $data['errmsg']; 
		$tmp['content'] = $data['errmsg']; 

		$this->load->view('internal_error_view.php', $tmp); 
    }
    
    function message($subdata)
    {
    	$this->load->model('membership_model');
    	$this->load->language('membership'); 
    
    	$header_data = array(); 
		
		if (!signed_in()) $header_data['username'] = $this->lang->line('misc_guest'); 
		else
		{
			$users = $this->membership_model->get_records(array("id" => $this->session->userdata('uid'))); 
			$header_data['username'] = $users[0]->name; 
		}

    	$data['css'] = $this->config->item('base_url') . $this->config->item('css'); 
    	$data['header'] = $this->load->view('header_view', $header_data, TRUE); 
    	$data['footer'] = $this->load->view('footer_view', '', TRUE); 
    	$data['title'] = "Message"; 
		$data['content'] = $this->load->view('message_view', $subdata, TRUE); 
		    	
    	$this->load->view('layout_view.php', $data); 
    }
}
?>