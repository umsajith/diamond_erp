<?=uif::contentHeader($heading)?>
	<?=form_open('inventory/insert_gr','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('dropdown',':attr.vendor','partner_fk',[$vendors])?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('dropdown',':attr.payment_method','purchase_method',
		[['0'=>uif::lng('attr.unknown'),'cash'=>uif::lng('attr.cash'),'invoice'=>uif::lng('attr.invoice')]])?>
		<?=uif::controlGroup('text',':attr.document','ext_doc')?>
		<?=uif::controlGroup('text',':attr.price_wo_vat','price')?>
		<?=uif::controlGroup('datepicker',':attr.ordered','dateoforder')?>
		<?=uif::controlGroup('datepicker',':attr.received','datereceived',uif::today())?>
		<?=uif::controlGroup('datepicker',':attr.expires','dateofexpiration')?>
		<?=uif::controlGroup('textarea',':attr.note','comments')?>
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
