<?=uif::contentHeader($heading)?>
	<?=form_open('job_orders/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','datedue')?>
		<?=uif::controlGroup('dropdown',':attr.employee','assigned_to',[$employees],'id="employee"')?>
		<?=uif::controlGroup('dropdown',':attr.task','task_fk',[],'id="tasks" disabled')?>
		<?=uif::controlGroup('text',':attr.quantity','assigned_quantity')?>
		<?=uif::controlGroup('text',':attr.spill','defect_quantity')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uname" disabled')?>
		<?=uif::controlGroup('text',':attr.work_hours','work_hours')?>
		<?=uif::controlGroup('radio',':attr.shift','shift',[[1,2,3]])?>
		<?=uif::controlGroup('textarea',':attr.note','description')?>
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

		cd.cascadeEmployeesTasks("<?=site_url('employees/ajxGetTasks')?>");
		var options = {future: false};
		cd.datepicker("input[name=datedue]",options);
	});
</script>