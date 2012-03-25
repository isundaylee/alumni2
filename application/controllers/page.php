<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('page_model'); 
		$this->load->language('page'); 
		$this->load->language('misc'); 
		$this->load->language('homepage'); 
	}
	
	public function show($id)
	{
		$pages = $this->page_model->get_records(array("id" => $id)); 
		
		if (count($pages) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('misc_msg_pid_not_exists'),
										 "links" => array($this->lang->line("homepage_go_to_homepage") => "homepage"))); 
		}
		else
		{
			$page = $pages[0]; 
			
			$data['id'] = $page->id; 
			$data['title'] = $page->title; 
			$data['author'] = $page->author; 
			$data['content'] = $page->content; 
			$data['type'] = $page->type; 
			$data['owner'] = $page->owner; 
			
			$editable = FALSE; 
			
			if ($this->session->userdata('uid') == $page->owner) $editable = TRUE; 
			
			$this->load->model('comment_model'); 
			$comments = $this->comment_model->get_records_limit(array("pid" => $id), 0, $this->config->item('comment_entries_per_page')); 
			
			$commenters = array(); 
			
			$this->load->model('membership_model'); 
			$this->load->language('homepage'); 
			
			$editors = $this->membership_model->get_records(array("id" => $page->owner)); 
			
			if (count($editors) == 0)
			{
				$this->layout->message(array("content" => $this->lang->page('page_msg_editor_id_not_exists'), 
											 "links" => array($this->lang->line('homepage_go_to_homepage') => "homepage"))); 
			}
			else
			{
				$data['editor'] = $editors[0]; 
			}
			
			foreach ($comments as $rid => $row)
			{
				$tmp = $this->membership_model->get_records(array("id" => $row->owner)); 
				$commenters[$rid] = $tmp[0]; 
			}
			
			$data['comments'] = $comments; 
			$data['commenters'] = $commenters; 
			$data['more'] = ($this->comment_model->get_records_count(array("pid" => $id)) > $this->config->item('comment_entries_per_page') ? TRUE : FALSE); 
			
			if (signed_in())
			{
				$this->load->model('membership_model'); 
				$user = $this->membership_model->get_records(array("id" => $this->session->userdata('uid'))); 
				
				$user = $user[0]; 
				
				if ($user->permission >= 3) $editable = TRUE; 
			}
			
			$data['editable'] = $editable; 
			
			$this->layout->display(array("title" => $page->title,
										 "extra_css" => array("page_show"), 
										 "content" => $this->load->view('page_show_view', $data, TRUE))); 
		}
	}
	
	public function personal($id)
	{
		$pages = $this->page_model->get_records(array("type" => "PP", 
													  "owner" => $id)); 
		
		
		if (count($pages) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('page_personal_page_not_exists'),
										 "links" => array($this->lang->line("homepage_go_to_homepage") => "homepage"))); 
		}
		else
		{
			$page = $pages[0]; 
			redirect('page/show/' . $page->id); 
		}
	}
	
	public function query()
	{
		if (!isset($_POST['page'])) $_POST['page'] = 1; 
		$data['querydata'] = $_POST; 
		$data['data'] = $this->page_model->get_records_fuzzy($_POST, ($_POST['page'] - 1) * $this->config->item('page_entries_per_page'), $this->config->item('page_entries_per_page')); 
		$data['totpage'] = floor(($this->page_model->get_records_count_fuzzy($_POST)- 1) / $this->config->item('page_entries_per_page')) + 1; 
		$this->layout->display(array("title" => $this->lang->line('page_query_page'),
									 "extra_css" => array("page_query"), 
									 "content" => $this->load->view('page_query_view', $data, TRUE))); 
	}
	
	public function type($type = "PP")
	{
		echo '<html><head></head><body onload="' . "document.getElementById('queryform').submit(); ". '">' . form_open('page/query', array('id' => 'queryform')) . form_hidden('type', $type) . form_close() . '</body>'; 
	}
	
	public function create()
	{
		require_permission(2); 
	
		$data = array("formdata" => array("id" => 0)); 
		
		$this->layout->display(array("title" => $this->lang->line('page_create_page'),
									 "extra_css" => array("page_update"), 
									 "content" => $this->load->view('page_update_view', $data, TRUE))); 
	}
	
	public function do_update()
	{
		$this->load->library('form_validation'); 
		
		$this->form_validation->set_rules('title', $this->lang->line('page_title'), 'required|min_length[1]|max_length[128]');
		$this->form_validation->set_rules('author', $this->lang->line('page_author'), 'required|min_length[1]|max_length[128]'); 		
		if (!$this->form_validation->run())
		{
			$data['errmsg'] = validation_errors(); 
			$data['formdata'] = $_POST; 
			
			$subv = $this->load->view('page_update_view', $data, TRUE); 
			
			$this->layout->display(array("title" => $this->lang->line('page_update_page'),	
									 	 "extra_css" => array("page_update"), 
										 "content" => $subv)); 
		}
		else
		{
			$id = $_POST['id']; 
			unset($_POST['id']); 
			
			if ($id == 0) $id = $this->page_model->insert_record($_POST); 
			else $this->page_model->update_record($id, $_POST); 
			
			redirect('page/show/' . $id); 
		}
	}
	
	public function do_delete_page($id = -1)
	{
		$pages = $this->page_model->get_records(array("id" => $id)); 
		
		if (count($pages) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('misc_msg_pid_not_exists'),
										 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1); "))); 
		}
		else
		{
			$page = $pages[0]; 
			
			if ($page->type == "PP")
			{
				$this->layout->message(array("content" => $this->lang->line('page_msg_cannot_delete_personal_page'),
										 	 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1); "))); 
			}
			else
			{
				if (!(signed_in() && $this->session->userdata('uid') == $page->owner)) require_permission(3); 
				
				$this->page_model->delete_record(array("id" => $id)); 
				
				$this->layout->message(array("content" => $this->lang->line('page_msg_page_deleted'),
										 	 "links" => array($this->lang->line('homepage_go_to_homepage') => "homepage"))); 
			}
		}
	}
	
	public function delete_page($id = 0)
	{
		$this->layout->message(array("content" => $this->lang->line('page_msg_confirm_delete_page'),
									 "links" => array($this->lang->line('misc_yes') => 'page/do_delete_page/' . $id),
									 "barelinks" => array($this->lang->line('misc_no') => "javascript:history.go(-1); "))); 
	}
	
	public function update_page($id = 0)
	{
		if ($id == 0)
		{
			require_permission(1); 
			
			$pages = $this->page_model->get_records(array("type" => "PP", 
														  "owner" => $this->session->userdata('uid'))); 
			
			if (count($pages) == 0)
			{
				$this->layout->message(array("content" => $this->lang->line('page_personal_page_not_exists'),
											 "links" => array($this->lang->line("homepage_go_to_homepage") => "homepage"))); 
			}
			else
			{
				$id = $pages[0]->id; 
			}
		}
		
		if ($id != 0)
		{
			$pages = $this->page_model->get_records(array("id" => $id), TRUE);
			
			if (count($pages) == 0)
			{
				$this->layout->message(array("content" => $this->lang->line('misc_msg_pid_not_exists'),
											 "links" => array($this->lang->line("homepage_go_to_homepage") => "homepage"))); 
			}
			else
			{
				if (!(signed_in() && $this->session->userdata('uid') == $pages[0]['owner'])) require_permission(3); 
			
				$data['formdata'] = $pages[0]; 
				
				$this->layout->display(array("title" => $this->lang->line('page_update_page'), 
											 "extra_css" => array("page_update"), 
											 "content" => $this->load->view('page_update_view', $data, TRUE))); 
			}
		}
	}
	
	public function do_comment()
	{
		if (!isset($_POST['content']) || $_POST['content'] == '')
		{
			$this->layout->message(array("content" => $this->lang->line('page_msg_comment_empty'),
										 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1); "))); 
		}
		else
		{
			$this->load->model('comment_model'); 
			
			$this->comment_model->insert_record($_POST); 
			
			$this->layout->message(array("content" => $this->lang->line('page_msg_comment_added'),
										 "barelinks" => array($this->lang->line('misc_go_back') => "javascript:history.go(-1); "))); 
		}
	}
	
	public function comment($pid, $page = 1)
	{
		$this->load->model('comment_model'); 
		$this->load->model('membership_model'); 
		$data['pid'] = $pid; 
		$data['page'] = $page; 
		$data['comments'] = $this->comment_model->get_records_limit(array("pid" => $pid), ($page - 1) * $this->config->item('comment_entries_per_page'), $this->config->item('comment_entries_per_page')); 
		foreach ($data['comments'] as $rid => $row)
		{
			$tmp = $this->membership_model->get_records(array("id" => $row->owner));
			$data['commenters'][$rid] = $tmp[0]; 
		}
		$data['totpage'] = floor(($this->comment_model->get_records_count(array("pid" => $pid)) - 1) / $this->config->item('comment_entries_per_page')) + 1; 
		
		$this->layout->display(array("title" => $this->lang->line('page_comments'),
									 "extra_css" => array("page_comments"), 
									 "content" => $this->load->view('page_comments_view', $data, TRUE))); 
	}
}
