<?php

$essence = array("title", "author", "content"); 

$ptlist = $this->config->item('page_type_list'); 

if (!isset($formdata)) $formdata = array(); 

foreach ($essence as $field) if (!isset($formdata[$field])) $formdata[$field] = ""; 

if (!isset($formdata["type"])) $formdata["type"] = $this->config->item('default_page_type'); 
if (!isset($errmsg)) $errmsg = ""; 

?>

<div class="errmsg"><?= $errmsg; ?></div>

<?= form_open('page/do_update'); ?>

<input type="hidden" name="owner" id="owner" value="<?= $this->session->userdata('uid'); ?>" />

<?= form_hidden('id', $formdata["id"]); ?>

<?= form_input(array("name" => "title", 
					 "id" => "title", 
					 "value" => $formdata['title'], 
					 "placeholder" => $this->lang->line('page_ph_title'))); ?>

<?= form_input(array("name" => "author", 
					 "id" => "author", 
					 "value" => $formdata['author'], 
					 "placeholder" => $this->lang->line('page_ph_author'))); ?>
<? if ($formdata['id'] == 0): ?>
					 
<?= form_dropdown('type',
				  array_diff($ptlist, array("PP" => $ptlist["PP"])), 
				  $formdata['type'], 
				  'id="type"'); ?>

<? endif; ?>

<?= form_textarea(array("name" => "content", 
					 	"id" => "content", 
					 	"value" => $formdata['content'], 
					 	"placeholder" => $this->lang->line('page_ph_content'))); ?>
					 	
<?= form_submit(array("name" => "submit_button", 
					  "id" => "submit_button", 
					  "value" => $this->lang->line('page_update_page'))); ?>

<?= form_close(); ?>