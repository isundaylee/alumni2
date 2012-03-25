<?php

$essence = array("title", "content", "author"); 

if (!isset($querydata)) $querydata = array(); 

foreach ($essence as $field) if (!isset($querydata[$field])) $querydata[$field] = ""; 

if (!isset($querydata["type"])) $querydata["type"] = ""; 

?>


<script language="javascript">
function goto_page(i)
{
	document.getElementById('page').value = i; 
	document.getElementById('queryform').submit();  
}
</script>

<?= form_open('page/query', array("id" => "queryform")); ?>

<input type="hidden" name="page" id="page" value="<?= $querydata['page']; ?>" />

<div class="fields">

<?= form_input(array("name" => "title", 
					 "id" => "title", 
					 "value" => $querydata['title'], 
					 "placeholder" => $this->lang->line('page_ph_title'))); ?>
					 
<?= form_input(array("name" => "author", 
					 "id" => "author", 
					 "value" => $querydata['author'], 
					 "placeholder" => $this->lang->line('page_ph_author'))); ?>

<?= form_input(array("name" => "content", 
					 "id" => "content", 
					 "value" => $querydata['content'], 
					 "placeholder" => $this->lang->line('page_ph_content'))); ?>
					 
<?= form_dropdown("type", 
				  array_merge($this->config->item('page_type_list'), array("" => $this->lang->line('page_any'))), 
				  $querydata['type'],
				  'id="type"'); ?>

</div>

<?= form_submit(array("name" => "submit_button", 
					  "id" => "submit_button", 
					  "value" => $this->lang->line('page_query_page'),
					  "onclick" => "document.getElementById('page').value = '1'")); ?>

<?= form_close(); ?>

<hr />

<? if(count($data) == 0): ?>

<p class="msg"><?= $this->lang->line('misc_msg_no_match_found'); ?></p>

<? else: ?>

<? foreach ($data as $row): ?>

<h2><?= anchor("page/show/" . $row->id, to_html($row->title)) . " "; ?></h2>
<p class="author"><?= to_html($row->author); ?></p>
<p class="preview">
<?
	$preview = to_html($row->content); 
	if (strlen($preview) > $this->config->item('page_preview_length')) $preview = substr($preview, 0, $this->config->item('page_preview_length')) . "……"; 
?>
<?= $preview; ?>
</p>

<? endforeach; ?>

<div class="navs">

<? if ($querydata['page'] > 1): ?>

<a href="#" onclick="goto_page(1); " class="navigator"><?= $this->lang->line('misc_first_page'); ?></a>
<a href="#" onclick="goto_page(<?= $querydata['page'] - 1; ?>); " class="navigator"><?= $this->lang->line('misc_prev_page'); ?></a> 

<? endif; ?>

<? if ($querydata['page'] < $totpage): ?>

<a href="#" onclick="goto_page(<?= $querydata['page'] + 1; ?>); " class="navigator"><?= $this->lang->line('misc_next_page'); ?></a> 
<a href="#" onclick="goto_page(<?= $totpage; ?>); " class="navigator"><?= $this->lang->line('misc_last_page'); ?></a>

<? endif; ?>

</div>

<? endif; ?>