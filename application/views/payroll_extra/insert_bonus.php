<?=uif::contentHeader($heading)?>
	<?=form_open('payroll_extra/insert_bonus','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','for_date')?>
		<?=uif::controlGroup('dropdown','Работник','employee_fk',[$employees])?>
		<?=uif::controlGroup('dropdown','Категорија','payroll_extra_cat_fk',[$categories])?>
		<?=uif::controlGroup('text','Износ','amount')?>
		<?=uif::controlGroup('textarea','Белешка','description')?>
	 <?=form_close()?>
	</div>
	<div class="span6">
		<?=uif::load('_last_job_order_view','job_orders')?>
	</div>
</div>
<script>
	$(function() {
		$("select[name=employee_fk]").select2();
		cd.datepicker("input[name=for_date]");
	});
</script>