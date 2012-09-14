<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('positions/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th></th>
		<th>Работно Место</th>
		<th>Сектор</th>
		<th>Основна Плата</th>
		<th>Бонус</th>
		<th>Провизија</th>
		<th>Квалификации</th>
		<th>Статус</th>
		<th></th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('positions/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->position;?></td>
			<td><?php echo $row->department;?></td>
			<td align="center"><?php echo ($row->base_salary == 0 ? '-' : $row->base_salary); ?></td>
            <td align="center"><?php echo ($row->bonus == 0 ? '-' : $row->bonus . '%'); ?></td>
            <td align="center"><?php echo ($row->commision == 0 ? '-' : $row->commision . '%'); ?></td>
			<td><?php echo $row->requirements;?></td>
			<td align="center"><?php echo $row->status;?></td>
			<td class="functions">
				<?php echo anchor('positions/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('positions/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>