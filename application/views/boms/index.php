<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('boms/insert')?>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid">  
	<thead>
		<tr>
	    	<th>&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
			<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
				<?=anchor("boms/index/{$col_name}/".
				(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
	    </tr>
    </thead>
    <tbody>
		<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('boms',$row->id)?></td>
			<td><?=$row->name?></td>
			<td><?=$row->quantity.' '.$row->uname2?></td>
			<td><?=($row->prodname) ? $row->prodname : '-'?></td>
			<td><?=($row->quantity * $row->conversion) ? 
				$row->quantity*$row->conversion.' '.$row->uname: '-'?></td>
			<td><?=uif::actionGroup('boms',$row->id)?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>