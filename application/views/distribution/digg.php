<h2><?php echo $heading . ' на Производ: ' .$product->prodname; ?></h2>
<hr>
<table class="master_table">
<tr>
	<th>&nbsp;</th>
	<th>Документ</th>
	<th>Старо Салдо</th>
	<th>Влез</th>
	<th>Излез</th>
	<th>Ново Салдо</th>
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
			<td align="center">
			<?php 
				if(!is_null($row->is_out))
				{$class = 'arrow_up'; $page ='out';}
					
				if(is_null($row->is_out) AND is_null($row->is_return))
				{$class = 'arrow_down'; $page ='in';}
					
				elseif(!is_null($row->is_return))
				{$class = 'arrow_rot'; $page ='ret';}

				echo "<span id='{$class}' class='{$class}' ></span>";			
			?>	
			</td>
			<td><?php echo anchor("distribution/view/{$page}/{$row->id}",'Референца');?></td>
			<td><?php echo $row->qty_current .' '. $row->uname;?></td>	
			<td><?php echo ($row->quantity>0)?$row->quantity. ' '. $row->uname : '-';?></td>
			<td><?php echo ($row->quantity<0)?$row->quantity. ' '. $row->uname : '-';?></td>
			<td><?php echo $row->quantity + $row->qty_current .' '. $row->uname;?></td>	
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