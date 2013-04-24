<?=uif::contentHeader($heading,$master)?>
	<?=form_open("orders/edit/{$master->id}",'class="form-horizontal" id="order-form"')?>
    <?=uif::button('icon-save','primary','onClick="submit_form()"')?>
<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::controlGroup('datepicker','Датум','dateshipped',$master)?>
		<?=uif::controlGroup('dropdown','Купувач','partner_fk',[$customers,$master],'placeholder="Купувач"')?>
		<?=uif::controlGroup('dropdown','Дистрибутер','distributor_fk',[$distributors,$master],'placeholder="Дистрибутер"')?>
		<?=uif::controlGroup('dropdown','Плаќање','payment_mode_fk',[$modes_payment,$master],'placeholder="Плаќање"')?>	
		<?=uif::controlGroup('dropdown','Статус','ostatus',
		[['pending'=>'Примена','completed'=>'Испорачана','rejected'=>'Одбиена'],$master],'placeholder="Статус"')?>	
		<?=uif::controlGroup('textarea','Белешка','comments',$master,'placeholder="Белешка"')?>	
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var options = {future: false};
		cd.datepicker(".datepicker",options);
	});
</script>