<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('payroll/calculate');?>" class="button_wide"><span class="calculator">Калкулација</span></a>
<div class="filers"> 
    <?php echo form_open('payroll/index');?>
    <?php echo form_dropdown('employee_fk', $employees, set_value('assigned_to')); ?>
    <?php echo form_dropdown('for_month', $G_months, set_value('for_month')); ?>
    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Работник</th>
		<th>Месец</th>
		<th>Од</th>
		<th>До</th>
		<th>Учинок</th>
		<th>Придонеси</th>
		<th>Тел.Суб.</th>
		<th>Додатоци</th>
		<th>Бруто Плата</th>
		<th>Фиксна Плата</th>
		<th>Трошоци</th>
		<th>Доплата</th>
		<th>Внес</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('payroll/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->fname.' '.$row->lname;?></td>
			<td><?php echo ($row->for_month) ? $row->for_month . '/' . mdate('%Y',strtotime($row->date_from)) : '-';?></td>
			<td><?php echo mdate('%d/%m/%Y',strtotime($row->date_from));?></td>
			<td><?php echo mdate('%d/%m/%Y',strtotime($row->date_to));?></td>
			<td><?php echo ($row->acc_wage==null)?'-':$row->acc_wage.$G_currency;?></td>
			<td><?php echo ($row->social_cont==null)?'-':$row->social_cont.$G_currency;?></td>
			<td><?php echo ($row->comp_mobile_sub==null)?'-':$row->comp_mobile_sub.$G_currency;?></td>
			<td><?php echo $row->bonuses.$G_currency;?></td>
			<td><?php echo $row->gross_wage.$G_currency;?></td>
			<td><?php echo $row->fixed_wage.$G_currency;?></td>
			<td><?php echo $row->expenses.$G_currency;?></td>
			<td><?php echo $row->paid_wage.$G_currency;?></td>
			<td class="code"><?php echo mdate('%d/%m/%Y',strtotime($row->dateofentry));?></td>
			<td>
				<?php echo anchor('payroll/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>