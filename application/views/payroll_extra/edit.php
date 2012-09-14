<h2><?php echo $heading; ?></h2>
<hr>
<?php echo form_open('payroll_extra/edit/'. $payroll_extra->id);?>
<?php echo form_submit('submit','Сними','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
	    <td class="label"><?php echo form_label('Работник: ');?><span class='req'>*</span></td>
   		<td><?php echo form_dropdown('employee_fk',$employees, set_value('employee_fk', $payroll_extra->employee_fk ));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Категорија: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('payroll_extra_cat_fk',$categories, set_value('payroll_extra_cat_fk', $payroll_extra->payroll_extra_cat_fk ));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Износ: ');?><span class='req'>*</span></td>
	    <td><?php echo form_input('amount', set_value('amount', $payroll_extra->amount ));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('За Месец: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('for_month',$G_months,set_value('for_month', $payroll_extra->for_month ));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Опис: ');?></td>
	    <td><textarea name="description" rows="5"><?php echo $payroll_extra-> description;?></textarea></td>
	</tr>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>















