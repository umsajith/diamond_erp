<h2><?php echo $heading; ?></h2>
<?php echo form_open('job_orders/insert',array('id'=>'job_order'));?>
<hr>
    <?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms_jo">
	<tr>
	    <td class="label"><?php echo form_label('Датум:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('datedue',set_value('datedue')); ?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Работник:');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('assigned_to',$employees, set_value('assigned_to'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Работна Задача:');?><span class='req'>*</span></td>
	    <td><select id="tasks"></select></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Количина:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('assigned_quantity',set_value('assigned_quantity'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Растур: ');?></td>
	    <td><?php echo form_input('defect_quantity',set_value('defect_quantity'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('ЕМ: ');?></td>
	    <td><?php echo form_input(array('id'=>'uname'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Работни Часови: ');?></td>
	    <td><?php echo form_input('work_hours',set_value('work_hours'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Смена: ');?></td>
	    <td>1<?php echo form_radio('shift','1',false);?> 2<?php echo form_radio('shift','2',false);?>3 <?php echo form_radio('shift','3',false);?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Забелешка: ');?></td>
	    <td><?php echo form_textarea('description');?></td>
	</tr>
	<?php echo form_hidden('task_fk');?>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>
<?php $this->load->view('job_orders/_last_job_order_view'); ?>

<script type="text/javascript">

$(function() {
		
		$("#uname, #category").attr("disabled", "disabled");
		$("input#uname").val("");
		
		$( "input[name=datedue]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0
		});

		/*
		 * When an employee is changed, searches the tasks assigned
		 * to this employee, and populates the dropdown
		 *
		 */
		$("select[name=assigned_to]").on("change",function() {
			var emp_id = $(this).val();

			$.get("<?php echo site_url('employees_tasks/dropdown'); ?>", {emp_id:emp_id}, function(result) {
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
			return false;
		});

		/*
		 * When task is changed, populates the hidden task ID 
		 *	and unit of measure of the same task
		 */	
		$("select#tasks").live("change",function() {
				if($("select#tasks").selectedIndex == '')
				{ 
					$("input#uname").val(''); 
					$("input[name=task_fk]").val('');   
					return false;	
				}
			  $("input[name=task_fk]").val(data_obj[this.selectedIndex-1].id);
			  $("input#uname").val(data_obj[this.selectedIndex-1].uname);  
		});	

		$("#job_order").on("submit",function(){
			$("input[name=submit]").attr("disabled","disabled");
		});
});
	
</script>