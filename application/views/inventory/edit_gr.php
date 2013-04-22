<?=uif::contentHeader($heading)?>
	<?=form_open("inventory/edit/{$page}/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>	
		<?=uif::controlGroup('dropdown','Добавувач','partner_fk',[$vendors,$result])?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[	],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity',$result)?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('dropdown','Начин','purchase_method',
		[['0'=>'Непознато','cash'=>'Готовина','invoice'=>'Фактура'],$result])?>
		<?=uif::controlGroup('text','Документ','ext_doc',$result)?>
		<?=uif::controlGroup('text','Цена (без ДДВ)','price',$result)?>
		<?=uif::controlGroup('datepicker','Нарачано','dateoforder',$result)?>
		<?=uif::controlGroup('datepicker','Примено','datereceived',$result)?>
		<?=uif::controlGroup('datepicker','Траење','dateofexpiration',$result)?>
		<?=uif::controlGroup('textarea','Белешка','comments',$result)?>
		<?=form_hidden('prodname_fk',$result->prodname_fk)?>
		<?=form_hidden('id',$result->id)?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		$("select").select2();
		cd.datepicker('input[name=dateofexpiration]');
		cd.datepicker('input[name=datereceived]');
		cd.datepicker('input[name=dateoforder]');
		var args = {
			prodname_fk : "<?=$result->prodname_fk?>"
		};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>", args)	
	});
</script>