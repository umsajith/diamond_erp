<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('inventory/insert_po')?>
		<?=uif::button('icon-download-alt','success','onClick=cd.receivePurchaseOrders("'.site_url('inventory/receive_po').'")')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('inventory/po_search','class="form-inline"')?>
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
			<th><input type="checkbox" class="check-all">&nbsp;</th>
			<th>&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?=($sort_by==$col_name) ? "class=$sort_order" : ""?>>
	    			<?=anchor("inventory/purchase_orders/$query_id/$col_name/".
	    				(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
		    <?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
	<?php switch ($row->po_status) 
		{
		    case 'approved': $status = 'оддобрено'; $rowClass = 'success';break;
		    case 'redjected': $status = 'одбиено'; $rowClass = 'error';break;
		   	case 'pending': $status = 'отворено'; $rowClass = ''; break;
		}
	?>
	<tr data-id=<?=$row->id?> class=<?=$rowClass?>>
		<td><input type="checkbox" value='<?=$row->id?>' class="purchase-order"></td>
		<td><?=uif::viewIcon('inventory',$row->id,'view/po')?></td>
		<td><?=uif::date($row->dateoforder)?></td>
		<td><?=$row->prodname?></td>
		<td><?=($row->qty_current == 0) ? '-' : $row->qty_current.' '.$row->uname?></td>
		<td><?=($row->quantity == 0) ? '-' : $row->quantity.' '.$row->uname?></td>
		<td><?=uif::isNull($row->company)?></td>
		<td><?=uif::isNull($row->assigned)?></td>
		<td>
			<?php switch ($row->purchase_method) 
			{
			    case '0':echo '-';break;
			    case 'cash':echo 'Готовина';break;
			   	case 'invoice':echo 'Фактура';break;
			}?>
		</td>
		<td><?=$status;?></td>
		<td><?=uif::date($row->dateofentry)?></td>
		<td><?=uif::actionGroup('inventory',$row->id,'edit/po','delete/po')?></td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>

<script>
	$(function() {
		$("select").select2();
	});
</script>