<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('tasks/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th></th>
		<th>Работна Задача</th>
		<th>Норматив</th>
		<th>Основна Единица</th>
		<th>Основна Цена</th>
		<th>Бонус Цена</th>
		<th></th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('tasks/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo ($row->is_production==1)?$row->name:'-';?></td>
			<td><?php echo $row->base_unit . ' ' . $row->uname;?></td>
			<td><?php echo $row->rate_per_unit.$G_currency;?></td>
			<td><?php echo $row->rate_per_unit_bonus.$G_currency;?></td>
			<td class="functions">
				<?php echo anchor('tasks/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('tasks/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>