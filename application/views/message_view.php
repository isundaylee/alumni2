<?php

if (!isset($links)) $links = array(); 
if (!isset($barelinks)) $barelinks = array(); 

?>

<div class="message" id="message"><?= $content; ?></div>

<div class="navs">

<? foreach($links as $a => $b): ?>

<?= anchor($b, $a, 'class="navigator"'); ?>

<? endforeach; ?>

<? foreach($barelinks as $a => $b): ?>

<a href="<?= $b; ?>" class="navigator"><?= $a; ?></a>

<? endforeach; ?>

</div>