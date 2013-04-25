<?=uif::contentHeader($heading)?>
	<?=form_open('distribution/insert_outbound','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','dateoforigin',uif::date(time(),'%Y-%m-%d'))?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('dropdown','Дистрибутер','distributor_fk',[$distributors])?>
		<?=uif::controlGroup('text','Документ','ext_doc')?>
		<?=uif::controlGroup('textarea','Белешка','note')?>
		<?=form_hidden('inserted_by',$this->session->userdata('userid'))?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.datepicker("input[name=dateoforigin]");
		$("select").select2();
		var args = {salable: 1};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>",args);	
	});
</script>