<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('products/insert')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('products/search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','ptname_fk',[$types])?>
			<?=uif::formElement('dropdown','','pcname_fk',[$categories])?>
			<?=uif::formElement('dropdown','','wname_fk',[$warehouses])?>
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
    			<?=anchor("products/index/{$query_id}/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
		<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('products',$row->id)?></td>
			<td><?=$row->prodname?></td>
			<td><?=$row->ptname?></td>
			<td><?=$row->pcname?></td>
			<td><?=$row->wname?></td>
			<td><?=uif::isNull($row->base_unit,' '.$row->uname)?></td>
			<td><?=uif::isNull($row->alert_quantity)?></td>
			<td><?=uif::isNull($row->retail_price)?></td>
			<td><?=uif::isNull($row->whole_price1)?></td>
			<td><?=uif::isNull($row->commision)?></td>	
			<td><?=$row->rate?></td>
			<td><?=uif::actionGroup('products',$row->id)?></td>
		</tr>
		<?php endforeach;?>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=pcname_fk]","<?=uif::lng('attr.category')?>");
		cd.dd("select[name=ptname_fk]","<?=uif::lng('attr.type')?>");
		cd.dd("select[name=wname_fk]","<?=uif::lng('attr.warehouse')?>");
	});	
</script>