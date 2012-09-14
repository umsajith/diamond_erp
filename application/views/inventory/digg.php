<h2><?php echo $heading . ' на Артикл: '. $product->prodname; ?></h2>
<hr>
<table class="master_table">
<tr>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>Документ</th>
	<th>Влез</th>
	<th>Излез</th>
	<th>Салдо</th>
	<th>Внес</th>
	<?php if($this->session->userdata('admin')):?>
		<th>Oператор</th>
	<?php endif;?>
	<th>&nbsp;</th>
</tr>
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<?php foreach($results as $row):?>
		<tr>
			<td align="center"><?php echo ($row->is_use == 0 ? anchor('#','&nbsp;','class="arrow_down" id="arrow_down"') : anchor('#','&nbsp;','class="arrow_up" id="arrow_up"'));?></td>
			<td align="center"><?php echo ($row->type == 'adj' ? anchor("#",'&nbsp;','class="arrow_rot" id="arrow_rot"') : '' ); ?></td>
			<td>
			<?php 
				echo (($row->warehouse_fk == null) ? 
				anchor("inventory/view/$row->type/$row->id",'Референца') : 
				anchor('distribution/view/'.$row->warehouse_fk,'Референца'));
			?>
			</td>
			<td><?php echo ($row->quantity > 0) ? $row->quantity.' '.$row->uname:'-';?></td>
			<td><?php echo ($row->quantity < 0) ? $row->quantity.' '.$row->uname:'-';?></td>
			<td><?php echo $row->quantity+$row->qty_current.' '.$row->uname;?></td>
			<td><?php echo $row->dateofentry;?></td>
			<?php if($this->session->userdata('admin')):?>
				<td><?php echo $row->fname. ' ' . $row->lname;?></td>
			<?php endif;?>
			<td>&nbsp;</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>