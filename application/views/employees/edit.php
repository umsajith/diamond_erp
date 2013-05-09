<?=uif::contentHeader($heading)?>
	<?=form_open("employees/edit/{$employee->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend"><?=uif::lng('attr.general_info')?></div>
			<?=uif::controlGroup('text',':attr.first_name','fname',$employee)?>
			<?=uif::controlGroup('text',':attr.last_name','lname',$employee)?>
			<?=uif::controlGroup('datepicker',':attr.dob','dateofbirth',$employee)?>
			<?=uif::controlGroup('text',':attr.ssn','ssn',$employee)?>
			<?=uif::controlGroup('dropdown',':attr.marital_status','mstatus',
			[[''=>'','single'=>uif::lng('attr.ms_single'),
			'married'=>uif::lng('attr.ms_married'),
			'divorced'=>uif::lng('attr.ms_divorced')],$employee])?>
			<?=uif::controlGroup('radio','Пол','gender',
			[['m'=>uif::lng('attr.sex_male'),'f'=>uif::lng('attr.sex_female')],$employee])?>
		<div class="legend"><?=uif::lng('attr.contact_info')?></div>	
			<?=uif::controlGroup('text',':attr.address','address',$employee)?>
			<?=uif::controlGroup('dropdown',':attr.city','postcode_fk',[$postalcodes,$employee])?>
			<?=uif::controlGroup('text',':attr.company_mobile','comp_mobile',$employee)?>
			<?=uif::controlGroup('text',':attr.mobile','mobile',$employee)?>
			<?=uif::controlGroup('text',':attr.phone','phone',$employee)?>
			<?=uif::controlGroup('text',':attr.email','email',$employee)?>
		<div class="legend"><?=uif::lng('attr.login_info')?></div>
			<?=uif::controlGroup('dropdown',':attr.role','role_id',[$roles,$employee])?>
			<?=uif::controlGroup('checkbox',':attr.can_login','can_login',[1,$employee])?>
			<?=uif::controlGroup('text',':common.username','username',$employee)?>
			<?=uif::controlGroup('password',':common.password','password')?>
	</div>
	<div class="span6">
		<div class="legend"><?=uif::lng('attr.financial_info')?></div>
			<?=uif::controlGroup('text',':attr.bank','bank',$employee)?>		
			<?=uif::controlGroup('text',':attr.account_number','account_no',$employee)?>		
			<?=uif::controlGroup('checkbox',':attr.fixed','fixed_wage_only',[1,$employee])?>		
			<?=uif::controlGroup('text',':attr.fixed_wage','fixed_wage',$employee)?>		
			<?=uif::controlGroup('text',':attr.social_contribution','social_cont',$employee)?>		
			<?=uif::controlGroup('text',':attr.subvention','comp_mobile_sub',$employee)?>	
		<div class="legend"><?=uif::lng('attr.work_info')?></div>
			<?=uif::controlGroup('dropdown',':attr.position','poss_fk',[$positions,$employee])?>
			<?=uif::controlGroup('dropdown',':attr.manager','manager_fk',[$managers,$employee])?>
			<?=uif::controlGroup('checkbox',':attr.distributor','is_distributer',[1,$employee])?>		
			<?=uif::controlGroup('checkbox',':attr.manager','is_manager',[1,$employee])?>
			<?=uif::controlGroup('dropdown',':attr.location','location_id',[$locations,$employee])?>
			<?=uif::controlGroup('datepicker',':attr.date_start','start_date',$employee)?>	
			<?=uif::controlGroup('datepicker',':attr.date_end','stop_date',$employee)?>	
			<?=uif::controlGroup('dropdown',':attr.status','status',
				[['active'=>uif::lng('attr.status_active'),'inactive'=>uif::lng('attr.status_inactive')],$employee])?>
			<div class="legend"><?=uif::lng('attr.note')?></div>
			<?=uif::formElement('textarea','','note',$employee,'class="input-block-level"')?>
			<?=form_hidden('id',$employee->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
		var options = {future: false};
		cd.datepicker("input[name=dateofbirth]",options);
		cd.datepicker("input[name=start_date]",options);
		cd.datepicker("input[name=stop_date]",options);
	});
</script>