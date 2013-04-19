<?=uif::contentHeader($heading)?>
	<?=form_open("products/edit/{$product->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','prodname',$product)?>
		<?=uif::controlGroup('text','Код','code',$product)?>
		<?=uif::controlGroup('dropdown','Тип','ptname_fk',[$product_types,$product])?>
		<?=uif::controlGroup('dropdown','Категорија','pcname_fk',[$product_cates,$product])?>
		<?=uif::controlGroup('dropdown','Магацин','wname_fk',[$warehouses,$product])?>
		<?=uif::controlGroup('dropdown','EM','uname_fk',[$uoms,$product])?>
		<?=uif::controlGroup('text','МП Цена','base_unit',$product)?>
		<?=uif::controlGroup('text','ГП Цена 1','retail_price',$product)?>
		<?=uif::controlGroup('text','ГП Цена 2','whole_price1',$product)?>
		<?=uif::controlGroup('dropdown','Данок (%)','tax_rate_fk',[$tax_rates,$product])?>
		<?=uif::controlGroup('text','Провизија','commision',$product)?>
		<?=uif::controlGroup('text','Мин. Количина','alert_quantity',$product)?>
		<?=uif::controlGroup('textarea','Опис','description',$product)?>
		<?=uif::controlGroup('checkbox','Се Продава','salable',[1,$product])?>
		<?=uif::controlGroup('checkbox','Се Купува','purchasable',[1,$product])?>
		<?=uif::controlGroup('checkbox','Состојба','stockable',[1,$product])?>
		<?=form_hidden('id',$product->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>