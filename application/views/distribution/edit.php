<?=uif::contentHeader($heading)?>
	<?=form_open("distribution/edit/{$page}/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','Датум','dateoforigin',$result)?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity',$result)?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?php if(in_array($page,['out','ret'])):?>
			<?=uif::controlGroup('dropdown','Дистрибутер','distributor_fk',[$distributors,$result])?>
		<?php endif;?>
		<?=uif::controlGroup('text','Документ','ext_doc',$result)?>
		<?=uif::controlGroup('textarea','Белешка','note',$result)?>
		<?=form_hidden('prodname_fk',$result->prodname_fk)?>
		<?=form_hidden('id',$result->id)?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		$("select").select2();
		cd.datepicker('input[name=dateoforigin]');
		var args = {salable: 1, prodname_fk : "<?=$result->prodname_fk?>"};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>", args)	
	});
</script>