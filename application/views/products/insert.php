<?=uif::contentHeader($heading)?>
	<?=form_open('products/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','prodname')?>
		<?=uif::controlGroup('text',':attr.code','code')?>
		<?=uif::controlGroup('dropdown',':attr.type','ptname_fk',[$product_types])?>
		<?=uif::controlGroup('dropdown',':attr.category','pcname_fk',[$product_cates])?>
		<?=uif::controlGroup('dropdown',':attr.warehouse','wname_fk',[$warehouses])?>
		<?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms])?>
		<?=uif::controlGroup('text',':attr.base_unit','base_unit')?>
		<?=uif::controlGroup('text',':attr.retail_price','retail_price')?>
		<?=uif::controlGroup('text',':attr.wholesale_price','whole_price1')?>
		<?=uif::controlGroup('dropdown',':attr.tax','tax_rate_fk',[$tax_rates])?>
		<?=uif::controlGroup('text',':attr.commision','commision')?>
		<?=uif::controlGroup('text',':attr.alert_quantity','alert_quantity')?>
		<?=uif::controlGroup('textarea',':attr.note','description')?>
		<?=uif::controlGroup('checkbox',':attr.salable','salable',[1])?>
		<?=uif::controlGroup('checkbox',':attr.purchasable','purchasable',[1])?>
		<?=uif::controlGroup('checkbox',':attr.stockable','stockable',[1])?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>