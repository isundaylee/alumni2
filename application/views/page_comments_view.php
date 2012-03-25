<? if (count($comments) == 0): ?>

<p class="msg"><?= $this->lang->line('page_msg_no_comment'); ?></p>

<? else: ?>

<? foreach ($comments as $rid => $row): ?>

<h2 class="comment_title"><?= anchor('membership/profile/' . $commenters[$rid]->id, $commenters[$rid]->name); ?> <?= $this->lang->line('misc_said'); ?> </h2>
<p class="comment_content"><?= to_html($row->content); ?></p>

<? endforeach; ?>

<div class="navs">

<? if ($page > 1): ?>

<?= anchor("page/comment/$pid/1", $this->lang->line('misc_first_page'), 'class="navigator"'); ?> 
<?= anchor("page/comment/$pid/" . ($page - 1), $this->lang->line('misc_prev_page'), 'class="navigator"'); ?> 

<? endif; ?>

<? if ($page < $totpage): ?>

<?= anchor("page/comment/$pid/" . ($page + 1), $this->lang->line('misc_next_page'), 'class="navigator"'); ?> 
<?= anchor("page/comment/$pid/" .  $totpage, $this->lang->line('misc_last_page'), 'class="navigator"'); ?> 

<? endif; ?>

</div>

<? endif; ?>