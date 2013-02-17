<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('payroll_extra/insert_social_contribution');?>" class="button"><span class="add">Внес</span></a>
<div class="filters"> 
    <?php echo form_open('payroll_extra/search_social_cont');?>
	    <?php echo form_dropdown('employee_fk', $employees, set_value('employee_fk')); ?>
	    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
			<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
    			<?php echo anchor("payroll_extra/social_contributions/{$query_id}/{$col_name}/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor("payroll_extra/view/{$row->id}/3",'&nbsp;','class="view_icon"');?></td>
			<td class="code" align="center"><?php echo ($row->locked == 0 ? '' : "<span class='lock_icon'></span>");?></td>
			<td><?php echo $row->fname . ' ' . $row->lname;?></td>
			<td><?php echo $row->name;?></td>
			<td><?php echo $row->amount;?></td>
			<td><?php echo ($row->for_date!='0000-00-00') ? $row->for_date : '-' ;?></td>
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