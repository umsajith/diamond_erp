<?=uif::contentHeader($heading)?>
<div class="row-fluid">
		<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('tasks/insert')?>
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
	    			<?=anchor("tasks/index/{$col_name}/".
	    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($results as $row):?>
			<tr>
				<td><?=uif::viewIcon('tasks',$row->id)?></td>
				<td><?=$row->taskname;?></td>
				<td><?=($row->is_production == 1) ? $row->name:'-'?></td>
				<td><?=$row->base_unit.' '.$row->uname?></td>
				<td><?=$row->rate_per_unit.$G_currency?></td>
				<td><?=$row->rate_per_unit_bonus.$G_currency?></td>
				<td><?=uif::actionGroup('tasks',$row->id)?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>