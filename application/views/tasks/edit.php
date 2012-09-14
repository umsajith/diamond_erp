<h2><?php echo $heading; ?></h2>
<?php echo form_open('tasks/edit/'.$task->id);?>
<hr>
	<div id="meta">
		<p>бр.<?php echo $task->id;?></p>
		<p><?php echo $task->dateofentry;?></p>
	</div>	
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
    <td><?php echo form_input('taskname', set_value('taskname',$task->taskname ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Основна Единица:');?><span class='req'>*</span></td>
    <td><?php echo form_input('base_unit', set_value('base_unit',$task->base_unit ));?></td>
  </tr>
<tr>
    <td class="label"><?php echo form_label('EM:');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('uname_fk',$uoms, set_value('uname_fk',$task->uname_fk ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Норматив:');?></td>
    <td><?php echo form_dropdown('bom_fk',$boms,set_value('bom_fk',$task->bom_fk ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Основна Цена:');?><span class='req'>*</span></td>
    <td><?php echo form_input('rate_per_unit', set_value('rate_per_unit',$task->rate_per_unit ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Бонус Цена:');?></td>
    <td><?php echo form_input('rate_per_unit_bonus', set_value('rate_per_unit_bonus',$task->rate_per_unit_bonus ));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Опис: ');?></td>
    <td>
    	<textarea name="description" rows="5"><?php echo $task->description;?></textarea> 
    </td>  
</tr>
</table>
<?php echo form_close();?>
<?php echo validation_errors(); ?>















