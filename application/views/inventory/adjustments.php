<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('inventory/insert_adj')?>
	</div>
	<div class="text-right" id="content-main-filters">
		<?=form_open('inventory/adj_search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','prodname_fk',[$products])?>
			<?=uif::formElement('dropdown','','pcname_fk',[$categories])?>
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
	    			<?=anchor("inventory/adjustments/{$query_id}/{$col_name}/".
	    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
		    <?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($results as $row):?>
		<tr>
			<td><?=uif::viewIcon('inventory',$row->id,'view/adj')?></td>
			<td><?=uif::date($row->dateofentry)?></td>		
			<td><?=$row->prodname?></td>
			<td><?=$row->pcname?></td>
			<td><?=$row->quantity.' '.$row->uname?></td>
			<td><?=uif::linkIcon("inventory/delete/adj/{$row->id}",'icon-trash confirm-delete')?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=prodname_fk]",'Артикл');
		cd.dd("select[name=pcname_fk]",'Категорија');
	});
</script>