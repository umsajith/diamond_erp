<?=uif::contentHeader($heading)?>
	<?=form_open('employees/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend"><?=uif::lng('attr.general_info')?></div>
			<?=uif::controlGroup('text',':attr.first_name','fname')?>
			<?=uif::controlGroup('text',':attr.last_name','lname')?>
			<?=uif::controlGroup('datepicker',':attr.dob','dateofbirth')?>
			<?=uif::controlGroup('text',':attr.ssn','ssn')?>
			<?=uif::controlGroup('dropdown',':attr.marital_status','mstatus',
			[[''=>'','single'=>uif::lng('attr.ms_single'),
			'married'=>uif::lng('attr.ms_married'),
			'divorced'=>uif::lng('attr.ms_divorced')]])?>
			<?=uif::controlGroup('radio',':attr.sex','gender',
			[['m'=>uif::lng('attr.sex_male'),'f'=>uif::lng('attr.sex_female')]])?>
		<div class="legend"><?=uif::lng('attr.contact_info')?></div>	
			<?=uif::controlGroup('text',':attr.address','address')?>
			<?=uif::controlGroup('dropdown',':attr.city','postcode_fk',[$postalcodes])?>
			<?=uif::controlGroup('text',':attr.company_mobile','comp_mobile')?>
			<?=uif::controlGroup('text',':attr.mobile','mobile')?>
			<?=uif::controlGroup('text',':attr.phone','phone')?>
			<?=uif::controlGroup('text',':attr.email','email')?>
		<div class="legend"><?=uif::lng('attr.login_info')?></div>
			<?=uif::controlGroup('dropdown',':attr.role','role_id',[$roles])?>
			<?=uif::controlGroup('checkbox',':attr.can_login','can_login',[1])?>
			<?=uif::controlGroup('text',':common.username','username')?>
			<?=uif::controlGroup('password',':common.password','password')?>
	</div>
	<div class="span6">
		<div class="legend"><?=uif::lng('attr.financial_info')?></div>
			<?=uif::controlGroup('text',':attr.bank','bank')?>		
			<?=uif::controlGroup('text',':attr.account_number','account_no')?>		
			<?=uif::controlGroup('checkbox',':attr.fixed','fixed_wage_only',[1])?>		
			<?=uif::controlGroup('text',':attr.fixed_wage','fixed_wage')?>		
			<?=uif::controlGroup('text',':attr.social_contribution','social_cont')?>		
			<?=uif::controlGroup('text',':attr.subvention','comp_mobile_sub')?>	
		<div class="legend"><?=uif::lng('attr.work_info')?></div>
			<?=uif::controlGroup('dropdown',':attr.position','poss_fk',[$positions])?>
			<?=uif::controlGroup('dropdown',':attr.manager','manager_fk',[$managers])?>
			<?=uif::controlGroup('checkbox',':attr.distributor','is_distributer',[1])?>		
			<?=uif::controlGroup('checkbox',':attr.manager','is_manager',[1])?>
			<?=uif::controlGroup('dropdown',':attr.location','location_id',[$locations])?>
			<?=uif::controlGroup('datepicker',':attr.date_start','start_date')?>
		<div class="legend"><?=uif::lng('attr.note')?></div>
			<?=uif::formElement('textarea','','note','','class="input-block-level"')?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var options = {future: false};
		cd.datepicker("input[name=dateofbirth]",options);
		cd.datepicker("input[name=start_date]",options);
		$("input[name=start_date]").val("<?=uif::date(time(),'%Y-%m-%d')?>");
	});
</script>