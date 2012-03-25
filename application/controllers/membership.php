<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('membership_model'); 
		$this->load->language('membership');
	}

	public function index()
	{
		redirect('membership/profile'); 
	}
	
	public function signup()
	{
		$subdata = array('formdata' => array('id' => 0)); 
	
		$data = array('title' => $this->lang->line('membership_signup'),
					  'extra_css' => array("membership_update"), 
					  'content' => $this->load->view('membership_update_view', $subdata, TRUE)
					 ); 
		
		$this->layout->display($data); 
	}
	
	public function do_update()
	{
		$this->load->library('form_validation'); 
		
		if ($_POST['id'] == 0) $this->form_validation->set_rules('email', $this->lang->line('membership_email'), 'required|max_length[128]|valid_email|is_unique[memberships.email]');
		$this->form_validation->set_rules('name', $this->lang->line('membership_name'), 'required|min_length[1]|max_length[128]');
		$this->form_validation->set_rules('year', $this->lang->line('membership_year'), 'required|greater_than[1900]|less_than[3000]');
		$this->form_validation->set_rules('class', $this->lang->line('membership_class'), 'required|greater_than[0]|less_than[100]');
		$this->form_validation->set_rules('mobile', $this->lang->line('membership_mobile'), 'required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('state', $this->lang->line('membership_state'), 'min_length[2]|max_length[128]');
		$this->form_validation->set_rules('city', $this->lang->line('membership_city'), 'min_length[2]|max_length[128]');
		$this->form_validation->set_rules('intro', $this->lang->line('membership_intro'), 'required|min_length[1]'); 
		$this->form_validation->set_rules('university', $this->lang->line('membership_university'), 'required|min_length[1]|max_length[128]'); 
		$this->form_validation->set_rules('birthday', $this->lang->line('membership_birthday'), 'required|exact_length[8]'); 
		
		if ($this->form_validation->run() == FALSE)
		{
			$subdata = array('formdata' => $_POST,
							 'errmsg' => validation_errors()); 
			
			$data = array('title' => $this->lang->line('membership_update_profile'),
						  'extra_css' => array('membership_update'), 
						  'content' => $this->load->view('membership_update_view', $subdata, TRUE)); 
			
			$this->layout->display($data); 
		}
		else
		{
			$id = $_POST['id']; 
			$nid = 0; 
			unset($_POST['id']); 
			
			if ($id == 0)
			{
				$nid = $this->membership_model->insert_record($_POST); 
				$this->load->model('page_model'); 
				$this->load->language('page'); 
				$this->page_model->insert_record(array("title" => $this->lang->line('page_pt_personal_page'),
													   "content" => $this->lang->line('page_pc_personal_page'),
													   "owner" => $nid, 
													   "type" => "PP", 
													   "author" => $_POST['name'])); 
			}
			else $this->membership_model->update_record($id, $_POST); 
		
			if ($id == 0) redirect('membership/do_generate_password/' . $nid); 
			else redirect('membership/profile'); 
		}
	}
	
	public function signin()
	{
		if (signed_in()) redirect('membership/profile'); 
		
		$data = array('title' => $this->lang->line('membership_signin'),
					  'extra_css' => array("membership_signin"),
					  'content' => $this->load->view('membership_signin_view', array(),TRUE)
					 ); 
		
		$this->layout->display($data); 
	}
	
	public function profile($id = 0)
	{
		if ($id == 0) {
			require_permission(1); 
			$id = $this->session->userdata('uid'); 
		}
		
		$users = $this->membership_model->get_records(array("id" => $id));
		
		
		if (count($users) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('misc_msg_uid_not_exists'),
										 "links" => array($this->lang->line('membership_go_to_profile') => "membership/profile"))); 
		}
		else
		{
			$data = array("userdata" => $users[0]); 
		
			$this->layout->display(array("title" => $this->lang->line('membership_profile'),
										 "extra_css" => array("membership_profile"), 
										 "content" => $this->load->view('membership_profile_view', $data, TRUE))); 
		}
	}
	
	public function do_signin()
	{
		if (signed_in()) redirect('membership/profile'); 
		
		if (!$this->membership_model->validate_signin($_POST['email'], $_POST['password']))
		{
		
			$data = array("formdata" => $_POST,
						  "errmsg" => $this->lang->line('membership_msg_combination_not_recognized')); 
						  
			$mdata = array("title" => $this->lang->line('membership_signin'), 
						   "content" => $this->load->view('membership_signin_view', $data, TRUE)); 
			
			$this->layout->display($mdata); 
		}
		else
		{
			$user = $this->membership_model->get_records(array("email" => $_POST['email'])); 
			$this->session->set_userdata(array("uid" => $user[0]->id)); 
			
			redirect('membership/profile'); 
		}
	}
	
	public function do_signout()
	{
		// require_permission(1); 
		
		if (signed_in()) $this->session->unset_userdata(array("uid" => 0)); 
		
		redirect("homepage"); 
	}
	
	public function do_generate_password($id)
	{
		if (count($this->membership_model->get_records(array("id" => $id))) == 0) $this->layout->internal_error(array("errmsg" => 'No such user. ')); 
		else
		{
			$password = random_string('alpha', $this->config->item('default_password_length')); 
			$this->membership_model->update_record($id, array("password" => $password)); 
			
			$user = $this->membership_model->get_records(array("id" => $id)); 
			
			$this->load->library('email'); 
			$this->email->from($this->config->item('webmaster_email')); 
			$this->email->to($user[0]->email); 
			$this->email->subject($this->lang->line('membership_es_password_generated')); 
			$this->email->message(str_replace('|PASSWORD|', $password, $this->lang->line('membership_ec_password_generated')));
			
			if (!$this->email->send()) $this->layout->internal_error(array("errmsg" => "Cannot send email. ")); 
			else
			{
				$this->layout->message(array('content' => $this->lang->line('membership_msg_password_generated'), 
											 'links' => array($this->lang->line('membership_signin') => "membership/signin")));  
			}
		}
	}
	
	public function do_reset_password()
	{
		$email = ""; 
		if (isset($_POST['email'])) $email = $_POST['email']; 
		
		$user = $this->membership_model->get_records(array("email" => $email)); 
		
		if (count($user) == 0)
		{
			$this->layout->message(array("content" => $this->lang->line('misc_msg_email_not_exists'),
										 "links" => array($this->lang->line('misc_retry') => "membership/reset_password"))); 
		}
		else
		{
			redirect("membership/do_generate_password/" . $user[0]->id); 
		}
	}
	
	public function not_signed_in()
	{
		$this->layout->message(array("content" => $this->lang->line('misc_msg_not_signed_in'),
								     "links" => array($this->lang->line('membership_signin') => "membership/signin"))); 

	}
	
	public function change_password()
	{
		require_permission(1); 
		
		$this->layout->display(array("title" => $this->lang->line(''),
									 "extra_css" => array("membership_change_password"), 
									 "content" => $this->load->view('membership_change_password_view', array(), TRUE))); 
	}
	
	public function do_change_password()
	{
		require_permission(1); 
	
		$opw = "";
		$npw = "";
		
		if (isset($_POST['old_password'])) $opw = $_POST['old_password']; 
		if (isset($_POST['new_password'])) $npw = $_POST['new_password']; 
		
		$user = $this->membership_model->get_records(array("id" => $this->session->userdata('uid'))); 
		
		$email = $user[0]->email; 
		
		if (!$this->membership_model->validate_signin($email, $opw))
		{
			$this->layout->message(array("content" => $this->lang->line('membership_msg_password_not_recognized'),
										 "links" => array($this->lang->line('misc_retry') => 'membership/change_password'))); 
		}
		else
		{
			$this->membership_model->change_password($user[0]->id, $npw); 
			$this->layout->message(array("content" => $this->lang->line('membership_msg_password_changed'),
										 "links" => array($this->lang->line('membership_go_to_profile') => "membership/profile"))); 
		}
	}
	
	public function permission_denied()
	{
		$this->layout->message(array("content" => $this->lang->line('misc_msg_permission_not_enough'),
								     "links" => array($this->lang->line('membership_signout') => "membership/do_signout"))); 
	}
	
	public function reset_password()
	{
		$this->layout->display(array("title" => $this->lang->line('membership_reset_password'), 
									 "extra_css" => array("membership_reset_password"), 
									 "content" => $this->load->view('membership_reset_password_view', array(), TRUE))); 	
	}

	public function update_profile($id = 0)
	{
		require_permission(1); 
		
		if ($id == 0) $id = $this->session->userdata('uid'); 
		
		if ($this->session->userdata('uid') != $id) require_permission(3); 
		
		$users = $this->membership_model->get_records(array("id" => $id), TRUE); 
		
		if (count($users) == 0) 
		{
			$this->layout->message(array("content" => $this->lang->line('misc_msg_uid_not_exists'),
										 "links" => array($this->lang->line('membership_go_to_profile') => "membership/profile"))); 
		}
		else
		{
			$subdata = array("formdata" => $users[0]); 
			
			$data = array('title' => $this->lang->line('membership_update_profile'),
						  'extra_css' => array("membership_update"),
						  'content' => $this->load->view('membership_update_view', $subdata, TRUE)
						 ); 
		
			$this->layout->display($data); 
		}	
	}
}
