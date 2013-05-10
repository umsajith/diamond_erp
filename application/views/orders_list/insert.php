<?=uif::contentHeader($heading)?>
	<?=form_open('orders_list/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','date')?>
		<?=uif::controlGroup('dropdown',':attr.distributor','distributor_id',[$distributors],'id="distributors"')?>
		<?=uif::controlGroup('text',':attr.document','ext_doc')?>
		<?=uif::controlGroup('textarea',':attr.note','note')?>
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