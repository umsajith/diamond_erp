<?=uif::contentHeader($heading)?>
	<?=form_open('inventory/insert_po','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('datepicker',':attr.ordered','dateoforder',uif::today())?>
		<?=uif::controlGroup('textarea',':attr.note','comments')?>
		<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.datepicker('input[name=dateoforder]');
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>")	
	});
</script>