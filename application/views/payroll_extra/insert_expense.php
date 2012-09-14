<h2><?php echo $heading; ?></h2>
<?php echo form_open('payroll_extra/insert_expense');?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Работник: ');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('employee_fk',$employees);?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Категорија: ');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('payroll_extra_cat_fk',$categories);?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Износ: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('amount', set_value('amount'));?></td>
</tr>
<tr>
	<td class="label"><?php echo form_label('За месец:');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('for_month',$G_months, set_value('for_month')); ?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Опис: ');?></td>
    
    <td><textarea name="description" rows="5"></textarea></td>
</tr>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>















