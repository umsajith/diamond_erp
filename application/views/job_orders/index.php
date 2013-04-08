<?=uif::createContentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::createInsertButton('job_orders/insert')?>
		<?=uif::createLockButton('#','complete_job_orders()',"id=complete")?>
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
	<!-- <a href="#" class="btn btn-primary" onClick="complete_job_orders();"><i class="icon-check"></i></a> -->
<div class="filters">
    
</div>
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
<table class="table table-stripped table-hover table-condensed data-grid">  
	<thead>
		<tr>
	    	<th><?php echo form_checkbox('','',FALSE,"class='check_all'");?>&nbsp;</th>
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
	<tr id="<?php echo $row->id;?>">
			<td class="code"><?php echo (($row->is_completed != 1)) ? form_checkbox('',$row->id,FALSE,"class='jo_check'") : '';?></td>
			<td class="code"><?php echo anchor('job_orders/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td class="code"><?php echo ($row->is_completed == 0 ? '' : "<span class='tick_icon'></span>");?></td>
			<td class="code"><?php echo ($row->locked == 0 ? '' : "<span class='lock_icon'></span>");?></td>
			<td align="center"><?php echo ($row->datedue == NULL ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->datedue))); ?></td>
			<td><?php echo  $row->fname. ' ' .$row->lname;?></td>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo $row->assigned_quantity.' '.$row->uname;?></td>
			<td><?php echo ($row->work_hours == NULL ? '-' : $row->work_hours); ?></td>
			<td><?php echo ($row->shift == NULL ? '-' : $row->shift); ?></td>
			<td align="center"><?php echo ($row->dateofentry == NULL ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry))); ?></td>
			<td class="functions">
			<?php if($row->locked != 1):?>
				<a href="<?php echo site_url("job_orders/edit/$row->id");?>" class="icon-edit"></a>
				<a href="<?php echo site_url("job_orders/delete/$row->id");?>" class="icon-trash del_icon"></a>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>

<script type="text/javascript">

	$(function(){
		$("#complete").on("click",function(e){
			e.preventDefault();
		});
	});

	function complete_job_orders()
	{
		var ids = $(".jo_check:checked").map(function(i,n) {
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