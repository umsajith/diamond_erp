<?=uif::contentHeader($heading)?>
	<?=form_open("employees/edit/{$employee->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend">Основни Информации</div>
			<?=uif::controlGroup('text','Име','fname',$employee)?>
			<?=uif::controlGroup('text','Презиме','lname',$employee)?>
			<?=uif::controlGroup('datepicker','ДНР','dateofbirth',$employee)?>
			<?=uif::controlGroup('text','ЕМБ','ssn',$employee)?>
			<?=uif::controlGroup('dropdown','Брачна Состојба','mstatus',
			[[''=>'','single'=>'Слободен/а','married'=>'Во Брак','divorced'=>'Разведен/а'],$employee])?>
			<?=uif::controlGroup('radio','Пол','gender',[['m'=>'Машко','f'=>'Женско'],$employee])?>
		<div class="legend">Контакт Информации</div>	
			<?=uif::controlGroup('text','Адреса','address',$employee)?>
			<?=uif::controlGroup('dropdown','Град','postcode_fk',[$postalcodes,$employee])?>
			<?=uif::controlGroup('text','Службен Мобилен','comp_mobile',$employee)?>
			<?=uif::controlGroup('text','Мобилен','mobile',$employee)?>
			<?=uif::controlGroup('text','Телефон','phone',$employee)?>
			<?=uif::controlGroup('text','Е-Меил','email',$employee)?>
		<div class="legend">Логин Инфомации</div>
			<?=uif::controlGroup('dropdown','Корисничка Група','role_id',[$roles,$employee])?>
			<?=uif::controlGroup('checkbox','Логирање','can_login',[1,$employee])?>
			<?=uif::controlGroup('text','Корисничко Име','username',$employee)?>
			<?=uif::controlGroup('password','Лозинка','password')?>
	</div>
	<div class="span6">
		<div class="legend">Финансиски Информации</div>
			<?=uif::controlGroup('text','Банка','bank',$employee)?>		
			<?=uif::controlGroup('text','Број на Сметка','account_no',$employee)?>		
			<?=uif::controlGroup('checkbox','Само Фиксна Плата','fixed_wage_only',[1,$employee])?>		
			<?=uif::controlGroup('text','Фиксна Плата','fixed_wage',$employee)?>		
			<?=uif::controlGroup('text','Придонеси','social_cont',$employee)?>		
			<?=uif::controlGroup('text','Служ.Моб.Субвенција','comp_mobile_sub',$employee)?>	
		<div class="legend">Информации за Работнен Однос</div>
			<?=uif::controlGroup('dropdown','Работно Место','poss_fk',[$positions,$employee])?>
			<?=uif::controlGroup('dropdown','Менаџер','manager_fk',[$managers,$employee])?>
			<?=uif::controlGroup('checkbox','Дистрибутер','is_distributer',[1,$employee])?>		
			<?=uif::controlGroup('checkbox','Менаџер','is_manager',[1,$employee])?>
			<?=uif::controlGroup('dropdown','Локација','location_id',[$locations,$employee])?>
			<?=uif::controlGroup('datepicker','Почеток','start_date',$employee)?>	
			<?=uif::controlGroup('datepicker','Крај','stop_date',$employee)?>	
			<?=uif::controlGroup('dropdown','Статус','status',
				[['active'=>'Активен','inactive'=>'Неактивен'],$employee])?>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="legend">Белешка</div>
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