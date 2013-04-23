<?=uif::contentHeader($heading)?>
	<?=form_open('job_orders/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','datedue')?>
		<?=uif::controlGroup('dropdown','Работник','assigned_to',[$employees],'id="employee"')?>
		<?=uif::controlGroup('dropdown','Работна Задача','task_fk',[],'id="tasks" disabled')?>
		<?=uif::controlGroup('text','Количина','assigned_quantity')?>
		<?=uif::controlGroup('text','Растур','defect_quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uname" disabled')?>
		<?=uif::controlGroup('text','Работни Часови','work_hours')?>
		<?=uif::controlGroup('radio','Смена','shift',[[1,2,3],''])?>
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
		$("select#employee").select2();
		$("select#tasks").select2();

		cd.dropdownTasks("<?=site_url('employees/ajxGetTasks')?>");
		var options = {future: false};
		cd.datepicker("input[name=datedue]",options);
	});
</script>