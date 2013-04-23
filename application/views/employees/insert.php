<?=uif::contentHeader($heading)?>
	<?=form_open('employees/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<legend>Основни Информации</legend>
			<?=uif::controlGroup('text','Име','fname')?>
			<?=uif::controlGroup('text','Презиме','lname')?>
			<?=uif::controlGroup('datepicker','ДНР','dateofbirth')?>
			<?=uif::controlGroup('text','ЕМБ','ssn')?>
			<?=uif::controlGroup('dropdown','Брачна Состојба','mstatus',
			[[''=>'','single'=>'Слободен/а','married'=>'Во Брак','divorced'=>'Разведен/а']])?>
			<?=uif::controlGroup('radio','Пол','gender',[['m'=>'Машко','f'=>'Женско'],''])?>
		<legend>Контакт Информации</legend>	
			<?=uif::controlGroup('text','Адреса','address')?>
			<?=uif::controlGroup('dropdown','Град','postcode_fk',[$postalcodes])?>
			<?=uif::controlGroup('text','Службен Мобилен','comp_mobile')?>
			<?=uif::controlGroup('text','Мобилен','mobile')?>
			<?=uif::controlGroup('text','Телефон','phone')?>
			<?=uif::controlGroup('text','Е-Меил','email')?>
		<legend>Логин Инфомации</legend>
			<?=uif::controlGroup('dropdown','Корисничка Група','role_id',[$roles])?>
			<?=uif::controlGroup('checkbox','Логирање','can_login',[1,''])?>
			<?=uif::controlGroup('text','Корисничко Име','username')?>
			<?=uif::controlGroup('password','Лозинка','password')?>
	</div>
	<div class="span6">
		<legend>Финансиски Информации</legend>
			<?=uif::controlGroup('text','Банка','bank')?>		
			<?=uif::controlGroup('text','Број на Сметка','account_no')?>		
			<?=uif::controlGroup('checkbox','Само Фиксна Плата','fixed_wage_only',[1,''])?>		
			<?=uif::controlGroup('text','Фиксна Плата','fixed_wage')?>		
			<?=uif::controlGroup('text','Придонеси','social_cont')?>		
			<?=uif::controlGroup('text','Служ.Моб.Субвенција','comp_mobile_sub')?>	
		<legend>Информации за Работнен Однос</legend>
			<?=uif::controlGroup('dropdown','Работно Место','poss_fk',[$positions])?>
			<?=uif::controlGroup('dropdown','Менаџер','manager_fk',[$managers])?>
			<?=uif::controlGroup('checkbox','Дистрибутер','is_distributer',[1,''])?>		
			<?=uif::controlGroup('checkbox','Менаџер','is_manager',[1,''])?>
			<?=uif::controlGroup('datepicker','Почеток','start_date')?>	
			<?=uif::controlGroup('dropdown','Локација','location_id',[$locations])?>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<legend>Белешка</legend>
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