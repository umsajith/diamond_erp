<?=uif::contentHeader($heading)?>
<div class="row-fluid">
		<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('positions/insert')?>
		<hr>
	</div>
</div>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
			<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
    			<?=anchor("positions/index/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    	<?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($results as $row):?>
		<tr>
			<td><?=uif::viewIcon('positions',$row->id)?></td>
			<td><?=$row->position?></td>
			<td><?=$row->department?></td>
			<td><?=uif::isNull($row->base_salary)?></td>
            <td><?=uif::isNull($row->bonus,' %')?></td>
            <td><?=uif::isNull($row->commision,' %')?></td>
			<td><?=uif::actionGroup('positions',$row->id)?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>