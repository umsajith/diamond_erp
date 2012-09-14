<h2><?php echo $heading; ?></h2>
<?php echo form_open('employees/insert');?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<div id="west">
<fieldset class="data_form">
	<legend>Oсновни Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Име: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('fname', set_value('fname'));?></td>
		    <td class="label"><?php echo form_label('Презиме: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('lname', set_value('lname'));?></td>
		</tr>
		<tr>
			<?php $dob = array('name'=>'dateofbirth','id'=>'dob');?>
		    <td class="label"><?php echo form_label('Датум на Раѓање: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input($dob);?></td>
			<td class="label"><?php echo form_label('Матичен Број: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('ssn', set_value('ssn'));?></td>
		</tr>
		<tr>    
			<td class="label"><?php echo form_label('Пол: ');?></td>
		    <td><?php echo form_dropdown('gender',array('m'=>'Машко','f'=>'Женско'));?></td>   
		    <td class="label"><?php echo form_label('Брачна Состојба: ');?></td>
		    <td><?php echo form_dropdown('mstatus',array(''=>'--','single'=>'Слободен/а','married'=>'Во Брак','divorced'=>'Разведен/а'));?></td> 
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Контакт Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Адреса: ');?></td>
		    <td><?php echo form_input('address', set_value('address'));?></td>
		   	<td class="label"><?php echo form_label('Град: ');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('postcode_fk',$postalcodes);?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Телефон: ');?></td>
		    <td><?php echo form_input('phone', set_value('phone'));?></td>
		
		
		    <td class="label"><?php echo form_label('Мобилен: ');?></td>
		    <td><?php echo form_input('mobile', set_value('mobile'));?></td>
		</tr>
		<tr>
		   	<td class="label"><?php echo form_label('Службен Мобилен: ');?></td>
		    <td><?php echo form_input('comp_mobile', set_value('comp_mobile'));?></td>
		
		    <td class="label"><?php echo form_label('Е-маил: ');?></td>
		    <td><?php echo form_input('email', set_value('email'));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Логин Инфомации</legend>
		<table class="data_forms_wide">
		<tr>    
		    <td class="label"><?php echo form_label('Корисничка Група: ');?></td>
		    <td><?php echo form_dropdown('ugroup_fk',$ugroups, set_value('ugroup_fk'));?></td>
		    <td class="label"><?php echo form_label('Логирање: ');?></td>
		    <td><?php echo form_checkbox('can_login','1',false);?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Корисничко Име: ');?></td>
		    <td><?php echo form_input('username', set_value('username'));?></td>
		    <td class="label"><?php echo form_label('Лозинка: ');?></td>
		    <td><?php echo form_password('password');?></td>
		</tr>
		</table>
</fieldset>
<?php echo validation_errors(); ?>
</div>

<div id="east">
<fieldset class="data_form">
	<legend>Финансиски Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Банка: ');?></td>
		    <td><?php echo form_input('bank');?></td>
		   	<td class="label"><?php echo form_label('Број на Сметка: ');?></td>
		    <td><?php echo form_input('account_no');?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Фиксна Плата: ');?></td>
		    <td><?php echo form_input('fixed_wage', set_value('fixed_wage'));?></td>
		   	<td class="label"><?php echo form_label('Придонеси: ');?></td>
		    <td><?php echo form_input('social_cont', set_value('social_cont'));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Само Фиксна Плата: ');?></td>
		    <td><?php echo form_checkbox('fixed_wage_only','1',false);?></td>
		    <td class="label"><?php echo form_label('Тел.Субвенција: ');?></td>
		    <td><?php echo form_input('comp_mobile_sub','', set_value('comp_mobile_sub'));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Информации за Работнен Однос</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Работно Место: ');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('poss_fk',$positions);?></td>
		    <td class="label"><?php echo form_label('Менаџер: ');?></td>
		    <td><?php echo form_dropdown('manager_fk',$managers);?></td>
		</tr>
		<tr>
			<td class="label"><?php echo form_label('Дистрибутер: ');?></td>
		    <td><?php echo form_checkbox('is_distributer','1',false);?></td>
		    <td class="label"><?php echo form_label('Менаџер: ');?></td>
		    <td><?php echo form_checkbox('is_manager','1',false);?></td>
		</tr>
		<tr>
		    <?php $start = array('name'=>'start_date','id'=>'start');?>
		    <td class="label"><?php echo form_label('Почеток: ');?></td>
		    <td><?php echo form_input($start, set_value('start_date'));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Белешка</legend>
		<table class="data_forms_wide">
		<tr>
		    <td colspan="4"><?php echo form_textarea('note', set_value('note'),"class='wide'"); ?></td>
		</tr>
		</table>
</fieldset>
</div>
<?php echo form_close();?>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#dob" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
			changeYear: true
		});
		$( "#start" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0
		});
	});
</script>