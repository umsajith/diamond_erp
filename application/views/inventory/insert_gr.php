<?=uif::contentHeader($heading)?>
	<?=form_open('inventory/insert_gr','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('dropdown','Добавувач','partner_fk',[$vendors])?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('dropdown','Начин','purchase_method',
		[['0'=>'Непознато','cash'=>'Готовина','invoice'=>'Фактура']])?>
		<?=uif::controlGroup('text','Документ','ext_doc')?>
		<?=uif::controlGroup('text','Цена (без ДДВ)','price')?>
		<?=uif::controlGroup('datepicker','Нарачано','dateoforder')?>
		<?=uif::controlGroup('datepicker','Примено','datereceived')?>
		<?=uif::controlGroup('datepicker','Траење','dateofexpiration')?>
		<?=uif::controlGroup('textarea','Белешка','comments')?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.datepicker('input[name=dateofexpiration]');
		cd.datepicker('input[name=dateoforder]');
		cd.datepicker('input[name=datereceived]');
		$("select").select2();
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>")	
	});
</script>
