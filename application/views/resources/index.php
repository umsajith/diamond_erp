<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('resources/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Title</th>
		<th>Parent</th>
		<th>Controller</th>
		<th>Method</th>
		<th>Order</th>
		<th>Permalink</th>
		<th>&nbsp;</th>
	</tr>
	<?php $i=1; ?>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code"><?php echo $i; $i++;?></td>
			<td><?php echo $row->ctitle;?></td>
			<td><?php echo (!$row->ptitle)?'-':$row->ptitle;?></td>
			<td><?php echo $row->controller;?></td>
			<td><?php echo (!$row->method)?'-':$row->method;?></td>
			<td><?php echo (!$row->order)?'-':$row->order;?></td>
			<td><?php echo (!$row->permalink)?'-':$row->permalink;?></td>
			<td class="functions">
				<?php echo anchor("resources/edit/{$row->id}",'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor("resources/delete/{$row->id}",'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
