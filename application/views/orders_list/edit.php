<?=uif::contentHeader($heading)?>
	<?=form_open("orders_list/edit/{$master->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','date',$master)?>
		<?=uif::controlGroup('dropdown',':attr.distributor','distributor_id',[$distributors,$master],'id="distributors"')?>
		<?=uif::controlGroup('text',':attr.document','ext_doc',$master)?>
		<?=uif::controlGroup('textarea',':attr.note','note',$master)?>
		<?=form_hidden('id',$master->id)?>
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