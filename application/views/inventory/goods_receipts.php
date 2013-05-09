<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('inventory/insert_gr')?>
	</div>
	<div class="text-right" id="content-main-filters">
		<?=form_open('inventory/gr_search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','prodname_fk',[$products])?>
			<?=uif::formElement('dropdown','','partner_fk',[$vendors])?>
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
	    			<?=anchor("inventory/goods_receipts/{$query_id}/{$col_name}/".
	    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>Вкупно</th>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('inventory',$row->id,'view/gr')?></td>
			<td><?=uif::date($row->datereceived)?></td>		
			<td><?=$row->prodname?></td>
			<td><?=uif::isNull($row->company)?></td>
			<td><?=$row->quantity.' '.$row->uname?></td>	
			<td>
				<?php 
					switch ($row->purchase_method) 
					{
					    case 'cash':
					    	echo uif::lng('attr.cash_sh');
					    	break;
					   	case 'invoice':
					   		echo uif::lng('attr.invoice_sh');
					   		break;
					   	default:
					   		echo '-';
					   		break;
					}
				?>
			</td>
			<td><?=uif::isNull($row->price,$glCurrSh)?></td>
			<td><?=uif::date($row->dateoforder)?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=($row->quantity*$row->price==0)?'-':round($row->quantity*$row->price,2).$glCurrSh;?></td>
			<td><?=uif::actionGroup('inventory',$row->id,'edit/gr','delete/gr')?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=prodname_fk]","<?=uif::lng('attr.item')?>");
		cd.dd("select[name=partner_fk]","<?=uif::lng('attr.vendor')?>");
		cd.dd("select[name=pcname_fk]","<?=uif::lng('attr.category')?>");
	});
</script>