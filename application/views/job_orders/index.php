<h2><?php echo $heading; ?></h2>
<hr>
	<a href="<?php echo site_url('job_orders/insert');?>" class="button"><span class="add">Внес</span></a>
	<a href="#" class="button" id="complete" onClick="complete_job_orders();"><span class="complete">Затвори</span></a>
<div class="filers">
    <?php echo form_open('job_orders/search');?>
	    <?php echo form_dropdown('task_fk', $tasks, set_value('task_fk')); ?>
	    <?php echo form_dropdown('assigned_to', $employees, set_value('assigned_to')); ?>
	    <?php echo form_dropdown('shift', array(''=>'- Смена -','1'=>'1','2'=>'2','3'=>'3'),set_value('shift')); ?>
	    <?php echo form_dropdown('job_order_status',array(''=>'- Статус -','completed'=>'Завршен','pending'=>'Во Тек','canceled'=>'Откажан'), set_value('job_order_status')); ?>
	    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">  
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
	    	<th><?php echo form_checkbox('','',FALSE,"class='check_all'");?>&nbsp;</th>
	    	<th colspan="3">&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("job_orders/index/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>Статус</th>
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
			<td><?php echo $row->taskname;?></td>
			<td><?php echo  $row->fname. ' ' .$row->lname;?></td>
			<td><?php echo $row->assigned_quantity.' '.$row->uname;?></td>
			<td><?php echo ($row->work_hours == NULL ? '-' : $row->work_hours); ?></td>
			<td><?php echo ($row->shift == NULL ? '-' : $row->shift); ?></td>
			<td class="quantity" id="<?php echo $row->id;?>"><?php echo ($row->final_quantity == NULL ? '-' : $row->final_quantity.' '.$row->uname); ?></td>
			<td align="center"><?php echo ($row->dateofentry == NULL ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry))); ?></td>
			<td class="functions">
				<?php 
					if($row->job_order_status=='completed')echo 'Завршен';
					elseif ($row->job_order_status=='pending')echo 'Во Тек';
					else echo 'Откажан';
				?>
			</td>
			<td class="functions">
			<?php if($row->locked != 1):?>
				<?php echo anchor('job_orders/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('job_orders/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
	</tbody>
</table>
<?php $this->load->view('includes/_pagination');?>

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
			  url: "<?php echo site_url('job_orders/complete'); ?>",
			  dataType: "json",
			  data: {ids:json_ids},
			  success: function(msg){		
				 		location.reload(true);
				  }
			});
		
		return false; 
	}
	
</script>