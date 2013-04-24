<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<?=uif::linkInsertButton('resources/insert')?>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid"> 
	<thead>
		<tr>
			<th>Title</th>
			<th>Parent</th>
			<th>Controller</th>
			<th>Method</th>
			<th>Order</th>
			<th>Permalink</th>
			<th>&nbsp;</th>
		</tr>
	</thead> 
	<tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td><?=$row->ctitle;?></td>
			<td><?=(!$row->ptitle)?'-':$row->ptitle;?></td>
			<td><?=$row->controller;?></td>
			<td><?=(!$row->method)?'-':$row->method;?></td>
			<td><?=(!$row->order)?'-':$row->order;?></td>
			<td><?=(!$row->permalink)?'-':$row->permalink;?></td>
			<td><?=uif::actionGroup('resources',$row->id)?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>