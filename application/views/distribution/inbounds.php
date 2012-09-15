<h2><?php echo $heading?></h2>
<hr>
<a href="<?php echo site_url('distribution/insert_inbound');?>" class="button"><span class="lorry_add">Влез</span></a>
<div class="filers">
    <?php echo form_open('distribution/in_search');?>
    <?php echo form_dropdown('prodname_fk', $products, set_value('prodname_fk')); ?>
    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
			<th>&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("distribution/inbounds/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('distribution/view/in/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo ($row->dateoforigin == null) ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateoforigin)); ?></td>	
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->qty_current.' '.$row->uname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>	
			<td><?php echo $row->quantity+$row->qty_current.' '.$row->uname;?></td>
			<td><?php echo ($row->dateofentry == null ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry))); ?></td>
			<td class="functions">
				<?php echo anchor('distribution/edit/in/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('distribution/delete/in/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
	</tbody>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>