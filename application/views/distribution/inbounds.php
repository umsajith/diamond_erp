<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('distribution/insert_inbound')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('distribution/in_search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','prodname_fk',[$products])?>
			<?=uif::filterButton()?>
    	<?=form_close()?>
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
	    			<?=anchor("distribution/inbounds/{$query_id}/{$col_name}/".
	    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('distribution',$row->id,'view/in')?></td>
			<td><?=uif::date($row->dateoforigin)?></td>	
			<td><?=$row->prodname;?></td>
			<td><?=$row->qty_current.' '.$row->uname;?></td>
			<td><?=$row->quantity.' '.$row->uname;?></td>	
			<td><?=$row->quantity + $row->qty_current.' '.$row->uname;?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=uif::actionGroup('distribution',$row->id,'edit/in','delete/in')?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=prodname_fk]","Артикл");
	});	
</script>