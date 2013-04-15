<?=uif::contentHeader($heading)?>
	<?=form_open("job_orders/edit/$job_order->id",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','datedue',$job_order)?>
		<?=uif::controlGroup('dropdown','Работник','assigned_to',[$employees,$job_order],'id="employee"')?>
		<?=uif::controlGroup('dropdown','Работна Задача','task_fk',[$tasks,$job_order],'id="tasks"')?>
		<?=uif::controlGroup('text','Количина','assigned_quantity',$job_order)?>
		<?=uif::controlGroup('text','Растур','defect_quantity',$job_order)?>
		<?=uif::controlGroup('text','ЕМ','','','id="uname" disabled',$job_order)?>
		<?=uif::controlGroup('text','Работни Часови','work_hours',$job_order)?>
		<?=uif::controlGroup('radio','Смена','shift',[[1,2,3],$job_order])?>
		<?=uif::controlGroup('textarea','Забелешка','description')?>
		<?=form_hidden('id',$job_order->id)?>
		<?=form_hidden('task_fk')?>
	</div>
</div>
	<?=form_close()?>
<script>
	/**
	  * FIX! IN EDIT.... ALL TASKS ALL AVAILABLE NO MATTER EMPLOYEE
 	  **/

	$(function() {

		$("#employee").select2();
		$("#tasks").select2({placeholder: "- Работна Задача -",allowClear: true});

		cd.dropdownTasks("<?=site_url('employees/ajxGetTasks')?>","<?=$job_order->id?>");
		var options = {future: false};
		cd.datepicker(".datepicker",options);

	});

	/*
	 * Takes the employee ID, and retreives all the tasks assigned
	 * to this employee. When find the task assigned in this Job Order
	 * marks it as "Selected"
	 */
	// var employee = $("select[name=assigned_to]").val();
	// console.log(employee);
	// $.getJSON("<?php echo site_url('employees/ajxGetTasks'); ?>", {employee:employee}, function(result) {
	//     data_obj = result;
	//     optionsValues = '';
	//     $.each(result, function(i, row){
	// 	        //Selected the correct value retreived from the database
	//             if (row.id == <?=$job_order->task_fk?>){
	//             	 optionsValues += '<option value="' + row.id + '>' + row.taskname + '</option>';
	//             	 $("#uname").val(row.uname);
	//             	 $("input[name=task_fk]").val(row.id);
	//             	 $("#tasks").select2('val',{id:row.id,text:row.taskname});
	//             }
	//             else {
	//             	optionsValues += "<option value='" + row.id + "'>" + row.taskname + "</option>";
	//             }
	//     });
	//     var options = $("#tasks");
	//     options.html(optionsValues);  
	// });
	
	
	// $(function() {
		
	// 	$("#uname").attr("disabled", "disabled");
	// 	$("input#uname").val("");
	// 	var options = {future:false};
	// 	cd.datepicker(".datepicker",options);
		
	// 	//OnChange for Tasks dropdown menu
	// 	$(document).on('change','select#tasks',function() {
	// 			if(this.selectedIndex == "")
	// 			{ 
	// 				$("input#uname").val(""); 
	// 				$("input[name=task_fk]").val("");   
	// 				return false;	
	// 			}
	// 		  $("input[name=task_fk]").val(data_obj[this.selectedIndex-1].id);
	// 		  $("input#uname").val(data_obj[this.selectedIndex-1].uname);
	// 		  return false;  
	// 	});

	// 	/*
	// 	 * If an employee is changed, searches the tasks assigned
	// 	 * to this employee, and populates the dropdown
	// 	 * Also, it deletes the Task ID from the hidden field
	// 	*/
	// 	$("select[name=assigned_to]").on("change",function() {
	// 		var emp_id = $(this).val();
	// 		$.get("<?php echo site_url('employees/ajxGetTasks'); ?>", {emp_id:emp_id}, function(result) {
	// 			 var optionsValues = "<select id='tasks'>";
	// 			    data_obj = result;
	// 			    optionsValues += "<option value=''>" + "- Работна Задача -" + "</option>";
	// 			    $.each(result, function() {
	// 			            optionsValues += "<option value='" + this.id + "'>" + this.taskname + "</option>";
	// 			    });
	// 			    optionsValues += "</select>";
	// 			    var options = $("select#tasks");
	// 			    options.replaceWith(optionsValues); 
	// 		    },"json");
	// 		$("input[name=task_fk]").val("");
	// 		return false;
	// 	});	
	// });

</script>