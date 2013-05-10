<?=uif::contentHeader($heading)?>
	<?=form_open('partners/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend"><?=uif::lng('attr.general_info')?></div>
			<?=uif::controlGroup('text',':attr.company','company')?>
			<?=uif::controlGroup('text',':attr.contact_person','contperson')?>
			<?=uif::controlGroup('dropdown',':attr.hq','mother_fk',[$customers])?>
			<?=uif::controlGroup('text',':attr.code','code')?>
			<?=uif::controlGroup('checkbox',':attr.hq','is_mother',[1])?>		
		<div class="legend"><?=uif::lng('attr.financial_info')?></div>	
			<?=uif::controlGroup('text',':attr.bank','bank')?>
			<?=uif::controlGroup('text',':attr.account_number','account_no')?>
			<?=uif::controlGroup('text',':attr.tax_number','tax_no')?>	
	</div>
	<div class="span6">
		<div class="legend"><?=uif::lng('attr.contact_info')?></div>	
			<?=uif::controlGroup('text',':attr.address','address')?>
			<?=uif::controlGroup('dropdown',':attr.city','postalcode_fk',[$postalcodes])?>
			<?=uif::controlGroup('text',':attr.phone','phone1')?>
			<?=uif::controlGroup('text',':attr.phone','phone2')?>
			<?=uif::controlGroup('text',':attr.fax','fax')?>
			<?=uif::controlGroup('text',':attr.mobile','mobile')?>
			<?=uif::controlGroup('text',':attr.email','email')?>
			<?=uif::controlGroup('text',':attr.web','web')?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>