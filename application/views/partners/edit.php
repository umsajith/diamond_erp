<?=uif::contentHeader($heading)?>
	<?=form_open("partners/edit/{$partner->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend"><?=uif::lng('attr.general_info')?></div>
			<?=uif::controlGroup('text',':attr.company','company',$partner)?>
			<?=uif::controlGroup('text',':attr.contact_person','contperson',$partner)?>
			<?=uif::controlGroup('dropdown',':attr.hq','mother_fk',[$customers,$partner])?>
			<?=uif::controlGroup('text',':attr.code','code',$partner)?>
			<?=uif::controlGroup('checkbox',':attr.hq','is_mother',[1,$partner])?>		
		<div class="legend"><?=uif::lng('attr.financial_info')?></div>
			<?=uif::controlGroup('text',':attr.bank','bank',$partner)?>
			<?=uif::controlGroup('text',':attr.account_number','account_no',$partner)?>
			<?=uif::controlGroup('text',':attr.tax_number','tax_no',$partner)?>	
	</div>
	<div class="span6">
		<div class="legend"><?=uif::lng('attr.contact_info')?></div>
			<?=uif::controlGroup('text',':attr.address','address',$partner)?>
			<?=uif::controlGroup('dropdown',':attr.city','postalcode_fk',[$postalcodes,$partner])?>
			<?=uif::controlGroup('text',':attr.phone','phone1',$partner)?>
			<?=uif::controlGroup('text',':attr.phone','phone2',$partner)?>
			<?=uif::controlGroup('text',':attr.fax','fax',$partner)?>
			<?=uif::controlGroup('text',':attr.mobile','mobile',$partner)?>
			<?=uif::controlGroup('text',':attr.email','email',$partner)?>
			<?=uif::controlGroup('text',':attr.web','web',$partner)?>
			<?=form_hidden('id',$partner->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>