<?php

$essence = array("email", "name", "year", "class", "mobile", "mobile_visible", "intro", "state", "city", "university", "birthday"); 

foreach ($essence as $item)
{
	if (!isset($formdata[$item]))
	{
		$formdata[$item] = ""; 
	}
}

if (!isset($formdata["mobile_visible"])) $formdata["mobile_visible"] = "1"; 
if (!isset($formdata["country"])) $formdata["country"] = $this->config->item('default_country'); 
if (!isset($formdata["gender"])) $formdata["gender"] = "M"; 
if (!isset($formdata["major"])) $formdata["major"] = "OT"; 

if (!isset($errmsg)) $errmsg = ""; 

$submitname = ""; 

if ($formdata["id"] == 0) $submitname = $this->lang->line("membership_signup"); 
else $submitname = $this->lang->line("membership_update_profile"); 

?>

<div class="errmsg"><?= $errmsg; ?></div>

<?= form_open('membership/do_update'); ?>

<?= form_hidden("id", $formdata['id']); ?>

<? if ($formdata['id'] == 0): ?>

<?= form_input(array("name" => "email", 
					 "id" => "email", 
					 "value" => $formdata['email'], 
					 "placeholder" => $this->lang->line('membership_ph_email'))); ?>
					 
<? endif; ?>
					 
<?= form_input(array("name" => "name", 
					 "id" => "name", 
					 "value" => $formdata["name"], 
					 "placeholder" => $this->lang->line('membership_ph_name'))); ?>
					 
<?= form_dropdown('gender', 
				  array("M" => $this->lang->line('membership_male'),
				  		"F" => $this->lang->line('membership_female')), 
				  $formdata['gender'], 
				  'id="gender"'); ?>
					 
<?= form_input(array("name" => "year", 
					 "id" => "year", 
					 "value" => $formdata["year"], 
					 "placeholder" => $this->lang->line('membership_ph_year'))); ?>
					 
<?= form_input(array("name" => "class", 
					 "id" => "class", 
					 "value" => $formdata["class"], 
					 "placeholder" => $this->lang->line('membership_ph_class'))); ?>

<?= form_input(array("name" => "birthday",
					 "id" => "birthday",
					 "value" => $formdata["birthday"],
					 "placeholder" => $this->lang->line('membership_ph_birthday'))); ?>

<?= form_input(array("name" => "mobile", 
					 "id" => "mobile", 
					 "value" => $formdata["mobile"], 
					 "placeholder" => $this->lang->line('membership_ph_mobile'))); ?>
					 
<?= form_dropdown('mobile_visible',
				  array("1" => $this->lang->line('membership_opt_mobile_visible'), 
				  		"0" => $this->lang->line('membership_opt_mobile_invisible')),
				  $formdata['mobile_visible'],
				  'id = "mobile_visible"'); ?>

<?= form_dropdown('country',
				  $this->config->item('country_list'), 
				  $formdata['country'], 
				  'id="country"'); ?>

<?= form_input(array("name" => "state", 
					 "id" => "state", 
					 "value" => $formdata["state"], 
					 "placeholder" => $this->lang->line('membership_ph_state'))); ?>

<?= form_input(array("name" => "city", 
					 "id" => "city", 
					 "value" => $formdata["city"], 
					 "placeholder" => $this->lang->line('membership_ph_city'))); ?>
					 
<?= form_input(array("name" => "university",
					 "id" => "university",
					 "value" => $formdata["university"],
					 "placeholder" => $this->lang->line('membership_ph_university'))); ?>
					 
<?= form_dropdown('major',
				  $this->config->item('major_list'), 
				  $formdata['major'],
				  'id="major"'); ?>
					 
<?= form_textarea(array("name" => "intro", 
						"id" => "intro",
						"value" => $formdata["intro"],
						"placeholder" => $this->lang->line('membership_ph_intro'))); ?>
					 
<?= form_submit(array("name" => "submit_button",
					  "id" => "submit_button",
					  "value" => $submitname)); ?>

<?= form_close(); ?>