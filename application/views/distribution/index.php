<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span12" id="content-main-buttons">
		<?=uif::linkButton('distribution/insert_inbound','icon-download-alt')?>
		<?=uif::linkButton('distribution/insert_outbound','icon-upload-alt')?>
		<?=uif::linkButton('distribution/insert_return','icon-retweet')?>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid"> 
	<thead> 
	<tr>
		<th>&nbsp;</th>
		<th>Производ</th>
		<th>Салдо</th>
		<th>Последна Промена</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
		<tr <?=($row->quantity <= 0) ? ' class="error" '  : ''?>>
			<td><?=uif::linkIcon('distribution/digg/'.$row->pid,'icon-folder-open')?></td>
			<td><?=$row->prodname?></td>
			<td><?=$row->quantity.' '.$row->uname?></td>
			<td><?=uif::date($row->dateofentry)?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>