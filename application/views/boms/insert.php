<?=uif::contentHeader($heading)?>
	<?=form_open('boms/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','name')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity')?>
		<?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms])?>
		<?=uif::controlGroup('text',':attr.conversion','conversion')?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('textarea',':attr.description','description')?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var args = {salable: 1};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>",args);	
	});
</script>