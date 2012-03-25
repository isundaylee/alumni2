<?

$ptlist = $this->config->item('page_type_list'); 

$this->load->language('homepage'); 
$this->load->language('membership'); 
$this->load->language('index'); 

?>

<div id="title_banner">
	<nav>
		<ul>
			<li><?= anchor('homepage', $this->lang->line('homepage_homepage')); ?></li>
			<li><?= anchor('membership/signup', $this->lang->line('membership_signup')); ?></li>
			<li><?= anchor('membership/profile', $this->lang->line('membership_profile')); ?></li>
			<li><?= anchor('index', $this->lang->line('index_alumni_index')); ?></li>
			<li><?= anchor('page/type/EV', $ptlist['EV']); ?></li>
			<li><?= anchor('page/type/AT', $ptlist['AT']); ?></li>
			<li><?= anchor('page/type/VI', $ptlist['VI']); ?></li>
			<li><?= $this->lang->line('homepage_about_us'); ?></li>
		</ul>
	</nav>
</div>

<div id="status_bar">
	<?= $this->lang->line('misc_welcome'); ?>, <?= $username; ?>. 
	<? if(!signed_in()): ?>
	<?= anchor('membership/signin', $this->lang->line('membership_signin')); ?>
	<? else: ?>
	<?= anchor('membership/do_signout', $this->lang->line('membership_signout')); ?>
	<? endif; ?>
</div>