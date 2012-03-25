<?php

$essence = array("email", "password"); 

if (!isset($formdata)) $formdata = array(); 

foreach ($essence as $field) if (!isset($formdata[$field])) $formdata[$field] = ""; 

if (!isset($errmsg)) $errmsg = ""; 

?>

<?= form_open('membership/do_signin'); ?>

<div class="errmsg"><?= $errmsg; ?></div>

<?= form_input(array("name" => "email",
					 "id" => "email",
					 "value" => $formdata["email"],
					 "placeholder" => $this->lang->line('membership_ph_email'))); ?>

<?= form_password(array("name" => "password",
						"id" => "password",
						"value" => $formdata["password"],
						"placeholder" => $this->lang->line('membership_ph_password'))); ?>

<?= form_submit(array("name" => "submit_button",
					  "id" => "submit_button",
					  "value" => $this->lang->line('membership_signin'))); ?>

<?= form_close(); ?>

<div class="navs">
<?= anchor('membership/reset_password', $this->lang->line('membership_reset_password'), 'class="navigator"'); ?>
</div>