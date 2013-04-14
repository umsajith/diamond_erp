<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('job_orders/insert')?>
		<?=uif::button('icon-ok','success','onClick=cd.completeJobOrders("'.site_url('job_orders/ajxComplete').'")')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<form action="<?=site_url('job_orders/search')?>" method="POST" class="form-inline">
	    	<?=form_dropdown('task_fk', $tasks, set_value('task_fk'))?>
	    	<?=form_dropdown('assigned_to', $employees, set_value('assigned_to'))?>
	    	<?=form_dropdown('shift', array(''=>'- Смена -','1'=>'1','2'=>'2','3'=>'3'),set_value('shift'))?>
	    	<button type="submit" class="btn btn-primary"><i class="icon-search"></i></button>
    	</form>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
<table class="table table-stripped table-hover data-grid">  
	<thead>
		<tr>
	    	<th><input type="checkbox" class="check-all">&nbsp;</th>
	    	<th colspan="3">&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?=($sort_by==$col_name) ? "class=$sort_order" : ""?>>
	    			<?=anchor("job_orders/index/$query_id/{$col_name}/".
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
			<td><?=uif::linkIcon("job_orders/view/{$row->id}",'icon-file-alt')?></td>
			<td><?=($row->is_completed) ? uif::staticIcon('icon-ok') : '';?></td>
			<td><?=($row->locked) ? uif::staticIcon('icon-lock') : '';?></i></td>
			<td><?=($row->datedue == null ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->datedue))); ?></td>
			<td><?= $row->fname. ' ' .$row->lname;?></td>
			<td><?=$row->taskname;?></td>
			<td><?=$row->assigned_quantity.' '.$row->uname;?></td>
			<td><?=($row->work_hours == null ? '-' : $row->work_hours); ?></td>
			<td><?=($row->shift == null ? '-' : $row->shift); ?></td>
			<td><?=($row->dateofentry == null ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry))); ?></td>
			<td>
			<?php if(!$row->locked):?>
				<?=uif::actionGroup('job_orders',$row->id)?>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?php $this->load->view('includes/_no_records'); ?>
<?php endif;?>

<script>
	$(function(){
		$("select").select2();
	});	
</script>