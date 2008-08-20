<!-- [Content] start -->
<div class="content wide">

<h1 id="page">Page Admininistration</h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/page/settings')?>"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin/page/create')?>">Create new Page</a></li>
	<li><a href="<?=site_url('admin')?>" class="last">Cancel</a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Title")?></th>
				<th width="27%"><?=__("SEF address")?></th>
				<th width="5%"><?=__("Status")?></th>
				<th width="5%"><?=__("Ordering")?></th>
				<th width="30%" colspan="3"><?=__("Action")?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($pages) : ?>
<?php $i = 1; foreach ($pages as $page): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=($page['level'] > 0) ? "|".str_repeat("__", $page['level']): ""?> <?=(strlen($page['title']) > 20? substr($page['title'], 0,20) . '...': $page['title'])?></td>
				<td><?=$page['uri']?></td>
				<td><?php if ($page['active']==1): echo 'Published'; else: echo 'Draft'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/page/move/up/'. $page['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up")?>" alt="<?=__("Move up")?>"/></a>
				<a href="<?=site_url('admin/page/move/down/'. $page['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down")?>" alt="<?=__("Move down")?>"/></a></td>
				<td><a href="<?=site_url($page['uri'])?>" rel="external">View</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$page['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$page['id'])?>">Delete</a></td>
				<td class="center"><?=$page['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->