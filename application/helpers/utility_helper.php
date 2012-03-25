<?php

function require_permission($perm)
{
	$CI =& get_instance(); 
	
	$CI->load->model('membership_model'); 
	$CI->load->language('membership'); 

	if ($perm == 0) return; 
	
	if ($perm > 0 && ($CI->session->userdata('uid') == FALSE || $CI->session->userdata('uid') == 0))
	{					
		redirect('membership/not_signed_in'); 
	}
	else
	{
		$user = $CI->membership_model->get_records(array("id" => $CI->session->userdata('uid'))); 
		if ($user[0]->permission < $perm)
		{
			redirect('membership/permission_denied'); 
		}
	}
}

function to_html($text)
{
	$text = htmlentities($text, ENT_COMPAT, 'UTF-8'); 
	// $text = str_replace(" ", "&nbsp;", $text); 
	$text = str_replace("\n", "<br />", $text); 
	return $text; 
}
   	

function to_html_rich($text, $data = array())
{
	$CI =& get_instance(); 

	$text = htmlentities($text, ENT_COMPAT, 'UTF-8'); 
	$text = preg_replace('/\\\\link{([^}]+),([^}]+)}/', '<a href="$1">$2</a>', $text); 
	$text = preg_replace('/\\\\link{([^}]+)}/', '<a href="$1">$1</a>', $text);  
	$text = preg_replace('/\\\\section{([^}]+)}/', '<h3 class="section">$1</h3>', $text); 
	$text = preg_replace('/\\\\image{([^}]+)}/', '<img src="$1" width="' . $CI->config->item('page_image_width') . '"/>', $text); 
	$text = preg_replace('/\\\\flash{([^}]+)}/', '<embed src="$1" quality="high" width="' . $CI->config->item('page_flash_width') . '" height="' . $CI->config->item('page_flash_height') . '"align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>', $text); 
	$CI->load->language('signup'); 
	$text = preg_replace('/\\\\signup/', '<a href="' . $CI->config->item('base_url') . $CI->config->item('index_page') . '/signup/add/' . $data["pid"] . '" ><div class="signup_button">' . $CI->lang->line('signup_signup') . '</div></a>', $text); 
	// $text = str_replace(" ", "&nbsp; ", $text); 
	$text = str_replace("\n", "<br />", $text); 
	return $text; 
}

function signed_in()
{
	$CI =& get_instance(); 
	return $CI->session->userdata('uid') != FALSE && $CI->session->userdata('uid') != 0; 
}

function safe_data($i)
{
	if (!isset($i))
	{
		return ""; 
	}
	else
	{
		return $i; 
	}
}

?>