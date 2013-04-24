<?=uif::contentHeader($heading)?>
	<?=form_open('boms/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','name')?>
		<?=uif::controlGroup('text','Количина','quantity')?>
		<?=uif::controlGroup('dropdown','ЕМ','uname_fk',[$uoms])?>
		<?=uif::controlGroup('text','Конверзија','conversion')?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('textarea','Белешка','description')?>
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