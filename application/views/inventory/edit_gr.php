<?=uif::contentHeader($heading)?>
	<?=form_open("inventory/edit/{$page}/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>	
		<?=uif::controlGroup('dropdown',':attr.vendor','partner_fk',[$vendors,$result])?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity',$result)?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('dropdown',':attr.payment_method','purchase_method',
		[['0'=>uif::lng('attr.unknown'),'cash'=>uif::lng('attr.cash'),'invoice'=>uif::lng('attr.invoice')],$result])?>
		<?=uif::controlGroup('text',':attr.document','ext_doc',$result)?>
		<?=uif::controlGroup('text',':attr.price_wo_vat','price',$result)?>
		<?=uif::controlGroup('datepicker',':attr.ordered','dateoforder',$result)?>
		<?=uif::controlGroup('datepicker',':attr.received','datereceived',$result)?>
		<?=uif::controlGroup('datepicker',':attr.expires','dateofexpiration',$result)?>
		<?=uif::controlGroup('textarea',':attr.note','comments',$result)?>
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