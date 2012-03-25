<?= form_open('membership/do_reset_password'); ?>

<?= form_input(array("name" => "email",
					 "id" => "email",
					 "value" => "", 
					 "placeholder" => $this->lang->line('membership_ph_email'))); ?>
					 
<?= form_submit(array("name" => "submit_button", 
					  "id" => "submit_button",
					  "value" => $this->lang->line('membership_reset_password'))); ?>

<?= form_close(); ?>