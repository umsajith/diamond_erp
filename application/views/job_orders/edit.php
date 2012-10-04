<h2><?php echo $heading; ?></h2>
<?php echo form_open('job_orders/edit/'. $job_order->id);?>
<hr>
	<div id="meta">
		<p>бр.<?php echo $job_order->id;?></p>
		<p><?php echo $job_order->dateofentry;?></p>
	</div>	
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms_jo">
<tr>
    <td class="label"><?php echo form_label('Датум:');?><span class='req'>*</span></td>
    <td><?php echo form_input('datedue',set_value('datedue',$job_order->datedue)); ?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Работник: ');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('assigned_to',$employees, set_value('assigned_to',$job_order->assigned_to));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Работна Задача: ');?><span class='req'>*</span></td>
    <td><select id="tasks"></select></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Количина: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('assigned_quantity',set_value('assigned_quantity',$job_order->assigned_quantity));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Растур: ');?></td>
    <td><?php echo form_input('defect_quantity',set_value('defect_quantity',$job_order->defect_quantity));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('ЕМ: ');?></td>
    <td><?php echo form_input(array('id'=>'uname','value' => set_value('uname',$job_order->uname)));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Работни Часови: ');?></td>
    <td><?php echo form_input('work_hours',set_value('work_hours',$job_order->work_hours));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Смена: ');?></td>
    <td>
    	1<?php echo form_radio('shift','1',(isset($job_order->shift) && $job_order->shift=='1')?true:false);?> 
    	2<?php echo form_radio('shift','2',(isset($job_order->shift) && $job_order->shift=='2')?true:false);?>
    	3 <?php echo form_radio('shift','3',(isset($job_order->shift) && $job_order->shift=='3')?true:false);?>
    </td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Забелешка: ');?></td>
    <td><?php echo form_textarea('description',set_value('description',$job_order->description));?></td>
</tr>
<?php echo form_hidden('id',$job_order->id);?>
<?php echo form_hidden('task_fk');?>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>

<script type="text/javascript">

	/*
	 * Takes the employee ID, and retreives all the tasks assigned
	 * to this employee. When find the task assigned in this Job Order
	 * marks it as "Selected"
	 */
	var emp_id = $("select[name=assigned_to]").val();
	$.get("<?php echo site_url('employees/ajxGetTasks'); ?>", {emp_id:emp_id}, function(result) {
	    var optionsValues = "<select id='tasks'>";
	    data_obj = result;
	    optionsValues += "<option value=''>" + "- Работна Задача -" + "</option>";
	    $.each(result, function() {
		        //Selected the correct value retreived from the database
	            if (this.id == <?php echo $job_order->task_fk;?>){
	            	 optionsValues += "<option value='" + this.id + "' selected='selected'>" + this.taskname + "</option>";
	            	 $("input#uname").val(this.uname);
	            	 $("input[name=task_fk]").val(this.id);   
	            }
	            else {
	            	optionsValues += "<option value='" + this.id + "'>" + this.taskname + "</option>";
	            }
	    });
	    optionsValues += "</select>";
	    var options = $("select#tasks");
	    options.replaceWith(optionsValues);  
	},"json");
	
	
	$(function() {
		
		$("#uname").attr("disabled", "disabled");
		$("input#uname").val("");
		
		$("input[name=datedue]").datepicker({
			dateFormat: "yy-mm-dd"
		});
		
		//OnChange for Tasks dropdown menu
		$("select#tasks").live("change",function() {
				if(this.selectedIndex == "")
				{ 
					$("input#uname").val(""); 
					$("input[name=task_fk]").val("");   
					return false;	
				}
			  $("input[name=task_fk]").val(data_obj[this.selectedIndex-1].id);
			  $("input#uname").val(data_obj[this.selectedIndex-1].uname);
			  return false;  
		});

		/*
		 * If an employee is changed, searches the tasks assigned
		 * to this employee, and populates the dropdown
		 * Also, it deletes the Task ID from the hidden field
		*/
		$("select[name=assigned_to]").on("change",function() {
			var emp_id = $(this).val();
			$.get("<?php echo site_url('employees/ajxGetTasks'); ?>", {emp_id:emp_id}, function(result) {
				 var optionsValues = "<select id='tasks'>";
				    data_obj = result;
				    optionsValues += "<option value=''>" + "- Работна Задача -" + "</option>";
				    $.each(result, function() {
				            optionsValues += "<option value='" + this.id + "'>" + this.taskname + "</option>";
				    });
				    optionsValues += "</select>";
				    var options = $("select#tasks");
				    options.replaceWith(optionsValues); 
			    },"json");
			$("input[name=task_fk]").val("");
			return false;
		});	
	});

</script>