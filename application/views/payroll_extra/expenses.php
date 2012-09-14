<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('payroll_extra/insert_expense');?>" class="button"><span class="add">Внес</span></a>
<div class="filers"> 
    <?php echo form_open('payroll_extra/expenses');?>
    <?php echo form_label('Работник:');?>
    <?php echo form_dropdown('employee_fk', $employees, set_value('employee_fk')); ?>
    <?php echo form_label('Категорија:');?>
    <?php echo form_dropdown('payroll_extra_cat_fk', $categories, set_value('payroll_extra_cat_fk')); ?>
    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Работник</th>
		<th>Категорија</th>
		<th>Изонс</th>
		<th>За Месец</th>
		<th>Датум на Внес</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor("payroll_extra/view/$row->id/2",'&nbsp;','class="view_icon"');?></td>
			<td class="code" align="center"><?php echo ($row->locked == 0 ? '' : anchor('#','&nbsp;','class="lock_icon" id="lock_icon"'));?></td>
			<td><?php echo $row->fname . ' ' . $row->lname;?></td>
			<td><?php echo $row->name;?></td>
			<td><?php echo $row->amount;?></td>
			<td><?php echo $row->for_month;?></td>
			<td><?php echo $row->dateofentry;?></td>
			<td class="functions">
				<?php if($row->locked != 1):?>
					<?php echo anchor('payroll_extra/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
					<?php echo anchor('payroll_extra/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
				<?php endif;?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>

<script type="text/javascript">
	$(document).ready(function() {
		
		//TAKE AWAY the A properties of the tag
		$("a#lock_icon").click(function(){
			return false;
		});

	});
</script>