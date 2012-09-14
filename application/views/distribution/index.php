<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('distribution/insert_inbound');?>" class="button"><span class="lorry_add">Влез</span></a>
	<a href="<?php echo site_url('distribution/insert_outbound');?>" class="button"><span class="lorry_delete">Излез</span></a>
	<a href="<?php echo site_url('distribution/insert_return');?>" class="button"><span class="lorry_go">Врати</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Производ</th>
		<th>Салдо</th>
		<th>Последно Ажурирање</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr <?php echo ($row->quantity <= 0)?"class='red'":'';?>>
			<td class="code"><?php echo anchor('distribution/digg/'.$row->pid,'&nbsp;','class="zoom_icon"');?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>
			<td><?php echo $row->dateofentry;?></td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_del_dialog');?>