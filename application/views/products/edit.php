<?=uif::contentHeader($heading)?>
	<?=form_open("products/edit/{$product->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','prodname',$product)?>
		<?=uif::controlGroup('text',':attr.code','code',$product)?>
		<?=uif::controlGroup('dropdown',':attr.type','ptname_fk',[$product_types,$product])?>
		<?=uif::controlGroup('dropdown',':attr.category','pcname_fk',[$product_cates,$product])?>
		<?=uif::controlGroup('dropdown',':attr.warehouse','wname_fk',[$warehouses,$product])?>
		<?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms,$product])?>
		<?=uif::controlGroup('text',':attr.base_unit','base_unit',$product)?>
		<?=uif::controlGroup('text',':attr.retail_price','retail_price',$product)?>
		<?=uif::controlGroup('text',':attr.wholesale_price','whole_price1',$product)?>
		<?=uif::controlGroup('dropdown',':attr.tax','tax_rate_fk',[$tax_rates,$product])?>
		<?=uif::controlGroup('text',':attr.commision','commision',$product)?>
		<?=uif::controlGroup('text',':attr.alert_quantity','alert_quantity',$product)?>
		<?=uif::controlGroup('textarea',':attr.note','description',$product)?>
		<?=uif::controlGroup('checkbox',':attr.salable','salable',[1,$product])?>
		<?=uif::controlGroup('checkbox',':attr.purchasable','purchasable',[1,$product])?>
		<?=uif::controlGroup('checkbox',':attr.stockable','stockable',[1,$product])?>
		<?=form_hidden('id',$product->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>