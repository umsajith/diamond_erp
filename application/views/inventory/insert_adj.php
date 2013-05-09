<?=uif::contentHeader($heading)?>
	<?=form_open('inventory/insert_adj','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('checkbox',':attr.deduct','is_use',[1])?>
		<?=uif::controlGroup('textarea',':attr.note','comments')?>
		<?=form_hidden('prodname_fk');?> 
		<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>")	
	});
</script>