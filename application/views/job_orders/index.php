<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('job_orders/insert')?>
		<?=uif::button('icon-ok-sign','success','onClick=cd.completeJobOrders("'.site_url('job_orders/ajxComplete').'")')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('job_orders/search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','task_fk',[$tasks])?>
			<?=uif::formElement('dropdown','','assigned_to',[$employees])?>
			<?=uif::formElement('dropdown','','shift',[[''=>'- Смена -','1'=>'1','2'=>'2','3'=>'3']])?>
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
	    	<th colspan="3">&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
    			<?=anchor("job_orders/index/{$query_id}/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display)?>
    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
		<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=((!$row->is_completed)) ? '<input type="checkbox" value='.$row->id.' class="job-order">' : '&nbsp;'?></td>
			<td><?=uif::viewIcon('job_orders',$row->id)?></td>
			<td><?=($row->is_completed) ? uif::staticIcon('icon-ok') : ''?></td>
			<td><?=($row->locked) ? uif::staticIcon('icon-lock') : ''?></i></td>
			<td><?=uif::date($row->datedue)?></td>
			<td><?= $row->fname. ' ' .$row->lname?></td>
			<td><?=$row->taskname;?></td>
			<td><?=$row->assigned_quantity.' '.$row->uname?></td>
			<td><?=uif::isNull($row->work_hours)?></td>
			<td><?=uif::isNull($row->shift)?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=(!$row->locked) ? uif::actionGroup('job_orders',$row->id) : ''?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>

<script>
	$(function(){
		$("select[name=task_fk]").select2({placeholder: "Работна Задача"});
		$("select[name=assigned_to]").select2({placeholder: "Работник"});
		$("select[name=shift]").select2({placeholder: "Смена"});
	});	
</script>