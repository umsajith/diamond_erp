<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('employees/insert');?>" class="button"><span class="add">Внес</span></a>
<div class="filters">
    <?php echo form_open('employees/search');?>
	    <?php echo form_dropdown('poss_fk', $possitions, set_value('poss_fk')); ?>
	    <?php echo form_dropdown('ugroup_fk', $ugroups, set_value('ugroup_fk')); ?>
	    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
			<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
    			<?php echo anchor("employees/index/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('employees/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->fname. ' '.$row->lname;?></td>
			<td><?php echo ($row->comp_mobile) ? $row->comp_mobile : '-';?></td>
			<td><?php echo $row->position;?></td>
			<td><?php echo $row->department;?></td>
			<td><?php echo ($row->fixed_wage_only) ?'Да':'-';?></td>
			<td><?php echo ($row->is_manager) ?'Да':'-';?></td>
			<td><?php echo ($row->is_distributer) ?'Да':'-';?></td>
			<td><?php echo ($row->fixed_wage != 0) ? $row->fixed_wage.$G_currency:'-';?></td>
			<td><?php echo ($row->comp_mobile_sub != 0) ? $row->comp_mobile_sub.$G_currency:'-';?></td>
			<td align="center"><?php echo ($row->status=='active')?'Активна':'Неактивна';?></td>
			<td class="functions">
				<?php echo anchor('employees/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('employees/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>

	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>