<?=uif::contentHeader($heading)?>
	<?=form_open("payroll_extra/edit/{$payroll_extra->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','for_date',$payroll_extra)?>
		<?=uif::controlGroup('dropdown','Работник','employee_fk',[$employees,$payroll_extra])?>
		<?=uif::controlGroup('dropdown','Категорија','payroll_extra_cat_fk',[$categories,$payroll_extra])?>
		<?=uif::controlGroup('text','Износ','amount',$payroll_extra)?>
		<?=uif::controlGroup('textarea','Белешка','description',$payroll_extra)?>
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