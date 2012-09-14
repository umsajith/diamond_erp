<h2><?php echo $heading . ' на Производ: ' .$product->prodname; ?></h2>
<hr>
<table class="master_table">

<tr>
	<th>&nbsp;</th>
	<th>Документ</th>
	<th>Влез</th>
	<th>Излез</th>
	<th>Салдо</th>
	<th>Документ</th>
	<th>Внес</th>
	<?php if($this->session->userdata('admin')):?>
		<th>Oператор</th>
	<?php endif;?>
	<th>&nbsp;</th>
</tr>

<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<?php foreach($results as $row):?>
		<tr>
			<td align="center"><?php echo ($row->is_out == 0 ? anchor('#','&nbsp;','class="arrow_down" id="arrow_down"') : anchor('#','&nbsp;','class="arrow_up" id="arrow_up"'));?></td>
			<td><?php echo ($row->job_order_fk == null ? anchor('distribution/view/'.$row->id,'Референца') : anchor('job_orders/view/'.$row->job_order_fk,'Референца'));?></td>
			<td><?php echo ($row->quantity>0)?$row->quantity. ' '. $row->uname : '-';?></td>
			<td><?php echo ($row->quantity<0)?$row->quantity. ' '. $row->uname : '-';?></td>
			<td><?php echo $row->quantity+$row->qty_current . ' '. $row->uname;?></td>	
			<td><?php echo ($row->ext_doc) ? $row->ext_doc : '-' ;?></td>
			<td><?php echo $row->dateofentry;?></td>
			<?php if($this->session->userdata('admin')):?>
				<td><?php echo $row->assignfname. ' ' . $row->assignlname;?></td>
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