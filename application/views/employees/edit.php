<h2><?php echo $heading; ?></h2>
<?php echo form_open('employees/edit/'. $employee->id);?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<div id="west">
<fieldset class="data_form">
	<legend>Oсновни Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Име: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('fname', set_value('fname', $employee->fname));?></td>
		    <td class="label"><?php echo form_label('Презиме: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('lname', set_value('lname', $employee->lname));?></td>
		</tr>
		<tr>
			<?php $dob = array('name'=>'dateofbirth','id'=>'dob');?>
		    <td class="label"><?php echo form_label('Датум на Раѓање: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input($dob, set_value('dateofbirth', $employee->dateofbirth));?></td>
			<td class="label"><?php echo form_label('Матичен Број: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('ssn', set_value('ssn', $employee->ssn));?></td>
		</tr>
		<tr>    
			<td class="label"><?php echo form_label('Пол: ');?></td>
		    <td><?php echo form_dropdown('gender',array('m'=>'Машко','f'=>'Женско'), $employee->gender);?></td>   
		    <td class="label"><?php echo form_label('Брачна Состојба: ');?></td>
		    <td><?php echo form_dropdown('mstatus',array(''=>'--','single'=>'Слободен/а','married'=>'Во Брак','divorced'=>'Разведен/а'), $employee->mstatus);?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Контакт Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Адреса: ');?></td>
		    <td><?php echo form_input('address', set_value('address', $employee->address));?></td>
		   	<td class="label"><?php echo form_label('Град: ');?></td>
		    <td><?php echo form_dropdown('postcode_fk',$postalcodes, set_value('postcode_fk', $employee->postcode_fk));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Телефон: ');?></td>
		    <td><?php echo form_input('phone', set_value('phone', $employee->phone));?></td>
		
		
		    <td class="label"><?php echo form_label('Мобилен: ');?></td>
		    <td><?php echo form_input('mobile', set_value('mobile', $employee->mobile));?></td>
		</tr>
		<tr>
		   	<td class="label"><?php echo form_label('Службен Мобилен: ');?></td>
		    <td><?php echo form_input('comp_mobile', set_value('comp_mobile', $employee->comp_mobile));?></td>
		
		    <td class="label"><?php echo form_label('Е-маил: ');?></td>
		    <td><?php echo form_input('email', set_value('email', $employee->email));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Логин Инфомации</legend>
		<table class="data_forms_wide">
		<tr>    
		    <td class="label"><?php echo form_label('Корисничка Група: ');?></td>
		    <td><?php echo form_dropdown('role_id',$roles, set_value('role_id', $employee->role_id));?></td>
		    <td class="label"><?php echo form_label('Логирање: ');?></td>
		    <td><?php echo form_checkbox('can_login','1',($employee->can_login=='1'?true:false));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Корисничко Име: ');?></td>
		    <td><?php echo form_input('username', set_value('username', $employee->username));?></td>
		    <td class="label"><?php echo form_label('Лозинка: ');?></td>
		    <td><?php echo form_password('password');?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Задолжување Работни Задачи</legend>
	<table class="data_forms">
		<tr>
			<td><?php echo form_dropdown('task',$tasks);?></td>
			<td><span class="add_icon" onclick="assign_task();">&nbsp;</span></td>	
		</tr>
	</table>
	<?php if(isset($assigned_tasks) && count($assigned_tasks)>0):?>
	<table class="data_forms_wide">
	<tr>
		<th>Работна Задача</th>
		<th>Бришење</th>
	</tr>
		<?php foreach ($assigned_tasks as $task):?>
		<tr>
			<td><?php echo $task->taskname;?></td>
			<td align="center" width="100px"><span class="removeprod" id="<?php echo $task->id;?>">&nbsp;</span></td>
		</tr>
		<?php endforeach;?>
	</table>
	<?php endif;?>
</fieldset>
</div>
<div id="east">
<fieldset class="data_form">
	<legend>Финансиски Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Банка: ');?></td>
		    <td><?php echo form_input('bank', set_value('bank', $employee->bank));?></td>
		   	<td class="label"><?php echo form_label('Број на Сметка: ');?></td>
		    <td><?php echo form_input('account_no', set_value('account_no', $employee->account_no));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Фиксна Плата: ');?></td>
		    <td><?php echo form_input('fixed_wage', set_value('account_no', $employee->fixed_wage));?></td>
		   	<td class="label"><?php echo form_label('Придонеси: ');?></td>
		    <td><?php echo form_input('social_cont', set_value('account_no', $employee->social_cont));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Само Фиксна Плата: ');?></td>
		    <td><?php echo form_checkbox('fixed_wage_only','1',(isset($employee->fixed_wage_only) && $employee->fixed_wage_only=='1')?true:false);?></td>
		    <td class="label"><?php echo form_label('Тел.Субвенција: ');?></td>
		    <td><?php echo form_input('comp_mobile_sub', set_value('comp_mobile_sub', $employee->comp_mobile_sub));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Информации за Работнен Однос</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Работно Место: ');?></td>
		    <td><?php echo form_dropdown('poss_fk',$positions, set_value('poss_fk', $employee->poss_fk));?></td>
		    <td class="label"><?php echo form_label('Менаџер: ');?></td>
		    <td><?php echo form_dropdown('manager_fk',$managers, set_value('manager_fk', $employee->manager_fk));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Дистрибутер: ');?></td>
		    <td><?php echo form_checkbox('is_distributer','1',($employee->is_distributer=='1'?true:false));?></td>
		    <td class="label"><?php echo form_label('Менаџер: ');?></td>
		    <td><?php echo form_checkbox('is_manager','1',(isset($employee->is_manager) && $employee->is_manager=='1')?true:false);?></td> 
		</tr>
		<tr>
			<?php $start = array('name'=>'start_date','id'=>'start');?>
		    <td class="label"><?php echo form_label('Почеток: ');?></td>
		    <td><?php echo form_input($start, set_value('start_date', $employee->start_date));?></td>
		    <?php $stop = array('name'=>'stop_date','id'=>'stop');?>
		    <td class="label"><?php echo form_label('Крај: ');?></td>
		    <td><?php echo form_input($stop, set_value('stop_date', $employee->stop_date));?></td>
		</tr>
		<tr>
			<td class="label"><?php echo form_label('Локација: ');?></td>
		    <td><?php echo form_dropdown('location_id',$locations,set_value('location_id',$employee->location_id));?></td>
		    <td class="label"><?php echo form_label('Статус: ');?></td>
		    <td><?php echo form_dropdown('status',array(''=>'- Статус -','active'=>'Активна','inactive'=>'Неактивна'),set_value('stop_date', $employee->status))?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Белешка</legend>
		<table class="data_forms_wide">
		<tr>
		    <td colspan="4"><?php echo form_textarea('note',$employee->note,"class='wide'"); ?></td>
		</tr>
		</table>
</fieldset>
<?php echo validation_errors(); ?>
</div>
<?php echo form_hidden('id',$employee->id);?>
<?php echo form_close();?>

<script type="text/javascript">

	$(function() {
		$( "#dob" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
			changeYear: true,
			yearRange: "1900:2000"
		});
		$( "#start" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
			changeYear: true
		});
		$( "#stop" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
			changeYear: true
		});

		$("span.removeprod").click(function(){
			var id = $(this).attr("id");
			$.post("<?php echo site_url('employees/ajxDeleteTask')?>",
					{id:id},
					function(data){
						if(data){
							location.reload(true);
						}			
				});
			return false;
		});
	});

	function assign_task()
	{
		var employee_fk = <?php echo $employee->id;?>;
		var task_fk = $("select[name=task] option:selected").val();

		$.post("<?php echo site_url('employees/ajxAssignTask')?>",
			{employee_fk:employee_fk,task_fk:task_fk},
			function(data){
				if(data){
					location.reload(true);
				}
				else
				{
					$.pnotify({pnotify_text:"Работната задача веќе е дефинирана!",pnotify_type: 'info'});
					return false;
				}
		});
		return false;
	}
</script>