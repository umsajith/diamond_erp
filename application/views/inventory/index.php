<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('inventory/insert_gr');?>" class="button"><span class="receive">Прием</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Артикл</th>
		<th>Категорија</th>
		<th>Лагер</th>
		<th>Просечна Цена</th>
		<th>Највисока Цена</th>
		<th>Последно Ажурирање</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr <?php echo ($row->alert_quantity >= $row->quantity ? ' class="red" '  : '');?>>
			<td class="code"><?php echo anchor('inventory/digg/'.$row->pid,'&nbsp;','class="zoom_icon"');?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>
			<td><?php echo round($row->price,3).$G_currency;?></td>
			<td><?php echo round($row->maxprice,3).$G_currency;?></td>
			<td><?php echo $row->dateofentry;?></td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>