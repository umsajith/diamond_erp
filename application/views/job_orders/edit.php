<?=uif::contentHeader($heading)?>
	<?=form_open("job_orders/edit/$job_order->id",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','datedue',$job_order)?>
		<?=uif::controlGroup('dropdown','Работник','assigned_to',[$employees,$job_order],'id="employee"')?>
		<?=uif::controlGroup('dropdown','Работна Задача','',[],'id="tasks"')?>
		<?=uif::controlGroup('text','Количина','assigned_quantity',$job_order)?>
		<?=uif::controlGroup('text','Растур','defect_quantity',$job_order)?>
		<?=uif::controlGroup('text','ЕМ','','','id="uname" disabled',$job_order)?>
		<?=uif::controlGroup('text','Работни Часови','work_hours',$job_order)?>
		<?=uif::controlGroup('radio','Смена','shift',[[1,2,3],$job_order])?>
		<?=uif::controlGroup('textarea','Забелешка','description',$job_order)?>
		<?=form_hidden('id',$job_order->id)?>
		<?=form_hidden('task_fk',$job_order->task_fk)?>
	</div>
</div>
	<?=form_close()?>
<script>
	$(function() {
		var options = {future: false};
		cd.datepicker(".datepicker",options);

		$("#employee").select2();
		$("#tasks").select2({placeholder: "- Работна Задача -",allowClear: true});

		cd.dropdownTasks("<?=site_url('employees/ajxGetTasks')?>","<?=$job_order->task_fk?>");
		$("#employee").trigger("change");
	});
</script>