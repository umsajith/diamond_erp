<?=uif::contentHeader($heading)?>
	<?=form_open("boms/edit/{$master->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','name',$master)?>
		<?=uif::controlGroup('text',':attr.quantity','quantity',$master)?>
		<?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms,$master])?>
		<?=uif::controlGroup('text',':attr.conversion','conversion',$master)?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('textarea',':attr.description','description',$master)?>
		<?=form_hidden('prodname_fk',$master->prodname_fk)?>
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var options = {
			hidden : "input[name=prodname_fk]",
			select : "#products",
			aux1 : "#uom",
			aux2 : "#category",
			prodname_fk : "<?=$master->prodname_fk?>",
			args : {
				salable : 1
			}
		};
		cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options);
	});
</script>