<?php

$clist = $this->config->item('country_list'); 
$mlist = $this->config->item('major_list'); 
$plist = $this->config->item('permission_list'); 

$this->load->language('signup'); 
$this->load->language('page'); 

$own = FALSE;

if ($this->session->userdata('uid') == $userdata->id) $own = TRUE; 

$tdata = array(
	array("", ""), 
	array($this->lang->line('membership_uid'), to_html($userdata->id)), 
	array($this->lang->line('membership_email'),  to_html($userdata->email)),
	array($this->lang->line('membership_gender'),  to_html($userdata->gender == "M" ? $this->lang->line('membership_male') : $this->lang->line('membership_female'))),
	array($this->lang->line('membership_name'),  to_html($userdata->name)),
	array($this->lang->line('membership_birthday'),  to_html($userdata->birthday)),
	array($this->lang->line('membership_year'),  to_html($userdata->year)),
	array($this->lang->line('membership_class'),  to_html($userdata->class)),
	array($this->lang->line('membership_mobile'),  to_html($own ? ($userdata->mobile . " (" . ($userdata->mobile_visible == "1" ? $this->lang->line('membership_mobile_visible') . ")" : $this->lang->line('membership_mobile_invisible') . ")")) : ($userdata->mobile_visible == "1" ? $userdata->mobile : $this->lang->line('membership_mobile_invisible')))), 
	array($this->lang->line('membership_address'), to_html($clist[$userdata->country] . " " . $userdata->state . " " . $userdata->city)), 
	array($this->lang->line('membership_university'), to_html($userdata->university)), 
	array($this->lang->line('membership_major'), to_html($mlist[$userdata->major])), 
	array($this->lang->line('membership_permission'), to_html($plist[$userdata->permission])), 
	array($this->lang->line('membership_intro'), to_html($userdata->intro))
); 

?>

<?= $this->table->generate($tdata); ?>

<div class="navs">

<? echo anchor('page/personal/' . $userdata->id, $this->lang->line('membership_visit_personal_page'), 'class="navigator"'); ?>

<? if ($own) echo anchor('page/create', $this->lang->line('page_create_page'), 'class="navigator"'); ?>

<? if ($own) echo anchor('signup/mine', $this->lang->line('signup_my_signups'), 'class="navigator"'); ?>

<? if ($own) echo anchor('membership/change_password', $this->lang->line('membership_change_password'), 'class="navigator"'); ?>

<? if ($own) echo anchor('membership/update_profile', $this->lang->line('membership_update_profile'), 'class="navigator"'); ?>

</div>