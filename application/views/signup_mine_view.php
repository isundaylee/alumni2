<? if (count($pages) == 0): ?>

<p class="msg"><?= $this->lang->line('signup_msg_no_signup'); ?></p>

<? else: ?>

<?php foreach ($pages as $page): ?>

<?= anchor('page/show/' . $page->id, $page->title . "<br />"); ?>

<?php endforeach; ?>

<? endif; ?>