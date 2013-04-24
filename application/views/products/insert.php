<?=uif::contentHeader($heading)?>
	<?=form_open('products/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','prodname')?>
		<?=uif::controlGroup('text','Код','code')?>
		<?=uif::controlGroup('dropdown','Тип','ptname_fk',[$product_types])?>
		<?=uif::controlGroup('dropdown','Категорија','pcname_fk',[$product_cates])?>
		<?=uif::controlGroup('dropdown','Магацин','wname_fk',[$warehouses])?>
		<?=uif::controlGroup('dropdown','EM','uname_fk',[$uoms])?>
		<?=uif::controlGroup('text','МП Цена','base_unit')?>
		<?=uif::controlGroup('text','ГП Цена 1','retail_price')?>
		<?=uif::controlGroup('text','ГП Цена 2','whole_price1')?>
		<?=uif::controlGroup('dropdown','Данок (%)','tax_rate_fk',[$tax_rates])?>
		<?=uif::controlGroup('text','Провизија','commision')?>
		<?=uif::controlGroup('text','Мин. Количина','alert_quantity')?>
		<?=uif::controlGroup('textarea','Опис','description')?>
		<?=uif::controlGroup('checkbox','Се Продава','salable',[1])?>
		<?=uif::controlGroup('checkbox','Се Купува','purchasable',[1])?>
		<?=uif::controlGroup('checkbox','Состојба','stockable',[1])?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();z
	});
</script>