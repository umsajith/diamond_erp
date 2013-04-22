<?=uif::contentHeader($heading)?>
	<?=form_open('inventory/insert_adj','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('checkbox','Минусирај','is_use',[1,''])?>
		<?=uif::controlGroup('textarea','Причина','comments')?>
		<?=form_hidden('prodname_fk');?> 
		<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>")	
	});
</script>