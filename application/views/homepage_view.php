<? 

$D = getdate(time()); 

$ds = date('t');
$today = $D["wday"]; 
$dnum = $D["mday"]; 

$bg = ($today - $dnum + 36) % 7; 
$ed = ($bg + $ds - 1) % 7; 

?>

<p class="msg"><?= $this->lang->line('homepage_msg_what_this_is'); ?></p>

<table id="cal">
<tr>
<th><?= $this->lang->line('homepage_sunday'); ?></th>
<th><?= $this->lang->line('homepage_monday'); ?></th>
<th><?= $this->lang->line('homepage_tuesday'); ?></th>
<th><?= $this->lang->line('homepage_wednesday'); ?></th>
<th><?= $this->lang->line('homepage_thursday'); ?></th>
<th><?= $this->lang->line('homepage_friday'); ?></th>
<th><?= $this->lang->line('homepage_saturday'); ?></th>
</tr>
<tr>
<? for ($i=0; $i<$bg; $i++): ?>
<td></td>
<? endfor; ?>
<? for ($i=1; $i<=$ds; $i++): ?>
<td><?= $i; ?><br /><br />
<? if (isset($days[$i])): ?>
<?= anchor('page/show/' . $days[$i]->id, to_html($days[$i]->title)); ?>
<? endif; ?>
</td>
<? if (($bg + $i - 1) % 7 == 6) echo "</tr><tr>"; ?>
<? endfor; ?>
<? for ($i=$ed+1; $i<7; $i++): ?>
<td></td>
<? endfor; ?>
</tr>
</table>
