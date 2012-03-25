<?= form_open('membership/do_change_password'); ?>

<?= form_password(array("name" => "old_password", 
						"id" => "old_password",
						"value" => "",
						"placeholder" => $this->lang->line('membership_ph_old_password'))); ?>

<?= form_password(array("name" => "new_password", 
						"id" => "new_password",
						"value" => "",
						"placeholder" => $this->lang->line('membership_ph_new_password'))); ?>
					 
<?= form_submit(array("name" => "submit_button",
					  "id" => "submit_button",
					  "value" => $this->lang->line('membership_change_password'))); ?>

<?= form_close(); ?>