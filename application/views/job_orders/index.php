<?=uif::createContentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::createInsertButton('job_orders/insert')?>
		<?=uif::createButton('icon-ok','onClick=completeJobOrders()','success')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<form action="<?=site_url('job_orders/search')?>" method="POST" class="form-inline">
	    	<?php echo form_dropdown('task_fk', $tasks, set_value('task_fk')); ?>
	    	<?php echo form_dropdown('assigned_to', $employees, set_value('assigned_to')); ?>
	    	<?php echo form_dropdown('shift', array(''=>'- Смена -','1'=>'1','2'=>'2','3'=>'3'),set_value('shift')); ?>
	    	<button type="submit" class="btn btn-primary"><i class="icon-search"></i></button>
    	</form>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
<table class="table table-stripped table-hover data-grid">  
	<thead>
		<tr>
	    	<th><?php echo form_checkbox('','',false,'class="check-all"');?>&nbsp;</th>
	    	<th colspan="3">&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("job_orders/index/$query_id/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
	<tr data-id="<?=$row->id?>">
			<td><?php echo ((!$row->is_completed)) ? form_checkbox('',$row->id,false,"class='job-order'") : '';?>&nbsp;</td>
			<td><?=uif::createLinkIcon("job_orders/view/{$row->id}",'icon-file-alt')?></td>
			<td><?=($row->is_completed) ? uif::createStaticIcon('icon-ok') : '';?></td>
			<td><?=($row->locked) ? uif::createStaticIcon('icon-lock') : '';?></i></td>
			<td><?php echo ($row->datedue == null ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->datedue))); ?></td>
			<td><?php echo  $row->fname. ' ' .$row->lname;?></td>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo $row->assigned_quantity.' '.$row->uname;?></td>
			<td><?php echo ($row->work_hours == null ? '-' : $row->work_hours); ?></td>
			<td><?php echo ($row->shift == null ? '-' : $row->shift); ?></td>
			<td><?php echo ($row->dateofentry == null ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry))); ?></td>
			<td>
			<?php if(!$row->locked):?>
				<?=uif::createLinkIcon("job_orders/edit/{$row->id}",'icon-edit')?>
				<?=uif::createLinkIcon("job_orders/delete/{$row->id}",'icon-trash confirm-delete')?>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>

<script>

	$(function(){
		$("select").select2();
	});

	function completeJobOrders()
	{
		var ids = $(".job-order:checked").map(function(i,n) {
	        return $(n).val();
	    }).get();
	
		if(ids.length == 0)
		{
			$.pnotify({pnotify_text:"Нема селектирани ставки!",pnotify_type: "info"});		
			return false;
		}
		
		var json_ids = JSON.stringify(ids);

		var success = $.ajax({
			  type: "POST",
			  url: "<?=site_url('job_orders/ajxComplete')?>",
			  dataType: "json",
			  data: {ids:json_ids},
			  success: function(msg){		
				 		location.reload(true);
				  }
			});
		
		return false; 
	}
	
</script>