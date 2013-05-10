<?=uif::contentHeader($heading)?>
	<?=form_open("job_orders/edit/{$job_order->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','datedue',$job_order)?>
		<?=uif::controlGroup('dropdown',':attr.employee','assigned_to',[$employees,$job_order],'id="employee"')?>
		<?=uif::controlGroup('dropdown',':attr.task','',[],'id="tasks"')?>
		<?=uif::controlGroup('text',':attr.quantity','assigned_quantity',$job_order)?>
		<?=uif::controlGroup('text',':attr.spill','defect_quantity',$job_order)?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uname" disabled',$job_order)?>
		<?=uif::controlGroup('text',':attr.work_hours','work_hours',$job_order)?>
		<?=uif::controlGroup('radio',':attr.shift','shift',[[1,2,3],$job_order])?>
		<?=uif::controlGroup('textarea',':attr.note','description',$job_order)?>
		<?=form_hidden('task_fk',$job_order->task_fk)?>
		<?=form_hidden('id',$job_order->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		var options = {future: false};
		cd.datepicker("input[name=datedue]",options);

		$("#employee").select2();
		$("#tasks").select2();

		cd.cascadeEmployeesTasks("<?=site_url('employees/ajxGetTasks')?>","<?=$job_order->task_fk?>");
		$("#employee").trigger("change");
	});
</script>