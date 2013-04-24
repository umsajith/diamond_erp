<?=uif::contentHeader($heading)?>
	<?=form_open("orders_list/edit/{$master->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','date',$master)?>
		<?=uif::controlGroup('dropdown','Дистрибутер','distributor_id',[$distributors,$master],'id="distributors"')?>
		<?=uif::controlGroup('text','Документ','ext_doc',$master)?>
		<?=uif::controlGroup('textarea','Белешка','note',$master)?>
		<?=form_hidden('id',$master->id)?>
		<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("#distributors").select2();
		var options = {future: false};
		cd.datepicker(".datepicker",options);
	});
</script>