<?=uif::contentHeader($heading)?>
	<?=form_open('orders_list/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','date')?>
		<?=uif::controlGroup('dropdown','Дистрибутер','distributor_id',[$distributors],'id="distributors"')?>
		<?=uif::controlGroup('text','Документ','ext_doc')?>
		<?=uif::controlGroup('textarea','Белешка','note')?>
		<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("#distributors").select2();
		var options = {future: false};
		cd.datepicker("input[name=date]",options);
	});
</script>