<?=uif::contentHeader($heading)?>
	<?=form_open("inventory/edit/{$page}/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>	
		<?=uif::controlGroup('dropdown','Артикл','',[],'id="products"')?>
		<?=uif::controlGroup('text','Количина','quantity',$result)?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?php if(isset($vendors)):?>
			<?=uif::controlGroup('dropdown','Добавувач','partner_fk',[$vendors,$result])?>
		<?php endif;?>
		<?=uif::controlGroup('dropdown','Начин','purchase_method',
		[['0'=>'Непознато','cash'=>'Готовина','invoice'=>'Фактура'],$result])?>
		<?=uif::controlGroup('dropdown','Задолжение','assigned_to',[$employees,$result])?>
		<?=uif::controlGroup('dropdown','Статус','po_status',
		[['approved'=>'Оддобрено','pending'=>'Во Исчекување','redjected'=>'Одбиено'],$result])?>
		<?=uif::controlGroup('datepicker','Нарачано','dateoforder',$result)?>
		<?=uif::controlGroup('textarea','Белешка','comments',$result)?>
		<?=form_hidden('prodname_fk',$result->prodname_fk)?>
		<?=form_hidden('id',$result->id)?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		$("select").select2();
		cd.datepicker('input[name=dateoforder]');
		var args = {
			prodname_fk : "<?=$result->prodname_fk?>"
		};
		cd.dropdownProducts("<?=site_url('products/ajxGetProducts')?>", args)	
	});
</script>