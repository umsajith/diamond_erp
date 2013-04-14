<?=uif::contentHeader($heading)?>
<?=form_open('job_orders/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Датум','datedue')?>
		<?=uif::controlGroup('dropdown','Работник','assigned_to',[$employees])?>
		<?=uif::controlGroup('dropdown','Работна Задача','',[],'id="tasks"')?>
		<?=uif::controlGroup('text','Количина','assigned_quantity')?>
		<?=uif::controlGroup('text','Растур','defect_quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uname" disabled')?>
		<?=uif::controlGroup('text','Работни Часови','work_hours')?>
		<div class="control-group">
			<label class="control-label">Смена</label>
			<div class="controls">
				<label class="radio">
					1<?=form_radio('shift','1',false);?>
				</label>
				<label class="radio">
					2<?=form_radio('shift','2',false);?>
				</label>
				<label class="radio">
					3<?=form_radio('shift','3',false);?>
				</label>
			</div>
		</div>
		<?=uif::controlGroup('textarea','Забелешка','description')?>
		<?=form_hidden('task_fk')?>
	<?=form_close()?>
	</div>
	<div class="span6">
		<?=uif::load('_last_job_order_view','job_orders')?>
	</div>
</div>
<script>

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
			return false;
		});

		/*
		 * When task is changed, populates the hidden task ID 
		 *	and unit of measure of the same task
		 */	
		$(document).on('change','select#tasks',function() {
				if($("select#tasks").selectedIndex == '')
				{ 
					$("input#uname").val(''); 
					$("input[name=task_fk]").val('');   
					return false;	
				}
			  $("input[name=task_fk]").val(data_obj[this.selectedIndex-1].id);
			  $("input#uname").val(data_obj[this.selectedIndex-1].uname);  
		});	
});
	
</script>