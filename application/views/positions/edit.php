<h2><?php echo $heading; ?></h2>
<?php echo form_open('positions/edit/'. $position->id);?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Назив: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('position', set_value('position', $position->position ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Сектор: ');?></td>
    <td><?php echo form_dropdown('dept_fk',$departments, set_value('dept_fk', $position->dept_fk));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Основна Плата');?></td>
    <td><?php echo form_input('base_salary', set_value('base_salary', $position->base_salary ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Бонус(%):');?></td>
    <td><?php echo form_input('bonus', set_value('bonus', $position->bonus ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Провизија(%):');?></td>
    <td><?php echo form_input('commision', set_value('commision', $position->commision ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Квалификации: ');?></td>
    <td><?php echo form_input('requirements', set_value('requirements', $position->requirements ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Опис: ');?></td>
    <td><textarea name="description" rows="5"><?php echo $position->description;?></textarea> </td>
</tr>
<tr>
	<td class="label"><?php echo form_label('Статус:');?></td>
	<td><?php echo form_dropdown('status',array('active'=>'Active','inactive'=>'Inactive'), set_value('status', $position->status));?></td>
</tr>
</table>
<?php echo form_close();?>
<?php echo validation_errors(); ?>















