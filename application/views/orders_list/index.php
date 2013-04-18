<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('orders_list/insert')?>
		<?php if($this->session->userdata('admin')):?>
			<?=uif::button('icon-lock','info','onClick=cd.lockOrderList("'.site_url('orders_list/ajxLock').'")')?>
			<?=uif::button('icon-unlock','info','onClick=cd.unlockOrderList("'.site_url('orders_list/ajxUnlock').'")')?>
		<?php endif;?>
	</div>	
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('orders_list/search','class="form-inline"')?>
			<?=uif::formElement('text','','q','','placeholder="Пребарувај по Документ/Код"')?>
			<?=uif::formElement('dropdown','','distributor_id',[$distributors])?>
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
			<th>&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?=($sort_by==$col_name) ? "class=$sort_order" : "";?>>
    			<?=anchor("orders_list/index/$query_id/$col_name/".
    				(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    	<?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><input type="checkbox" value="<?=$row->id?>" class="order-list"></td>
			<td><?=uif::viewIcon('orders_list',$row->id)?></td>
			<td><?=($row->locked) ? uif::staticIcon('icon-lock') : '';?></i></td>
			<td><?=uif::date($row->date)?></td>
			<td><?=$row->distributor; ?></td>
			<td><?=uif::isNull($row->ext_doc)?></td>
			<td><?=uif::isNull($row->code)?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=(!$row->locked) ? uif::actionGroup('orders_list',$row->id) : ''?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>	
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>

<script>
	$(function(){
		$("select").select2();
	});	
</script>