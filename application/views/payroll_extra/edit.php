<?=uif::contentHeader($heading)?>
	<?=form_open("payroll_extra/edit/{$payroll_extra->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','for_date',$payroll_extra)?>
		<?=uif::controlGroup('dropdown',':attr.employee','employee_fk',[$employees,$payroll_extra])?>
		<?=uif::controlGroup('dropdown',':attr.category','payroll_extra_cat_fk',[$categories,$payroll_extra])?>
		<?=uif::controlGroup('text',':attr.amount','amount',$payroll_extra)?>
		<?=uif::controlGroup('textarea',':attr.note','description',$payroll_extra)?>
		<?=form_hidden('id',$payroll_extra->id)?>
	 <?=form_close()?>
	</div>
	<div class="span6">
		<?=uif::load('_last_job_order_view','job_orders')?>
	</div>
</div>
<script>
	$(function() {
		$("select[name=employee_fk]").select2();
		$("select[name=payroll_extra_cat_fk]").select2();
		cd.datepicker("input[name=for_date]");
	});
</script>