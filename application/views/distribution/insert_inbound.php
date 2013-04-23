<?=uif::contentHeader($heading)?>
	<?=form_open('distribution/insert_inbound','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','dateoforigin')?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('text','Документ','ext_doc')?>
		<?=uif::controlGroup('textarea','Белешка','note')?>
		<?=form_hidden('inserted_by',$this->session->userdata('userid'));?>
		<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.datepicker("input[name=dateoforigin]");
		$("input[name=dateoforigin]").val("<?=uif::date(time(),'%Y-%m-%d')?>");
		$("select").select2();
		var args = {salable: 1};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>",args)	
	});
</script>