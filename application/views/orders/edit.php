<?=uif::contentHeader($heading,$master)?>
	<?=form_open("orders/edit/{$master->id}",'class="form-horizontal" id="order-form"')?>
    <?=uif::button('icon-save','primary','onClick="submit_form()"')?>
<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','dateshipped',$master)?>
		<?=uif::controlGroup('dropdown',':attr.customer','partner_fk',[$customers,$master],'placeholder="Купувач"')?>
		<?=uif::controlGroup('dropdown',':attr.distributor','distributor_fk',[$distributors,$master],'placeholder="Дистрибутер"')?>
		<?=uif::controlGroup('dropdown',':attr.payment_method','payment_mode_fk',[$modes_payment,$master],'placeholder="Плаќање"')?>	
		<?=uif::controlGroup('dropdown',':attr.status','ostatus',
		[['pending' => uif::lng('attr.pending'),'completed' => uif::lng('attr.delivered'),'rejected' => uif::lng('attr.rejected')],$master])?>	
		<?=uif::controlGroup('textarea',':attr.note','comments',$master)?>	
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var options = {future: false};
		cd.datepicker("input[name=dateshipped]",options);
	});
</script>