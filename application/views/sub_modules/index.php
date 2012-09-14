<h2><?php echo $heading?></h2>
<hr>
<a href="<?php echo site_url('sub_modules/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>Title</th>
		<th>Module</th>
		<th>Controller</th>
		<th>Method</th>
		<th>Order</th>
		<th>Permalink</th>
		<th>Visible</th>
		<th>Status</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->title;?></td>
			<td><?php echo $row->mtitle;?></td>
			<td><?php echo $row->controller;?></td>
			<td><?php echo ($row->method==null)?'-':$row->method;?></td>
			<td><?php echo ($row->order==null)?'-':$row->order;?></td>
			<td><?php echo ($row->permalink==null)?'-':$row->permalink;?></td>
			<td><?php echo ($row->is_visible==1)?'Yes':'No';?></td>
			<td align="center"><?php echo $row->status;?></td>
			<td class="functions">
				<?php echo anchor('sub_modules/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('sub_modules/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>

	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_del_dialog');?>
