<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('products/insert');?>" class="button"><span class="add">Внес</span></a>
<div class="filers">
    <?php echo form_open('products/search');?>
    <?php echo form_dropdown('ptname_fk', $types, set_value('ptname_fk')); ?>
    <?php echo form_dropdown('pcname_fk',$categories, set_value('pcname_fk')); ?>
    <?php echo form_dropdown('wname_fk', $warehouses, set_value('wname_fk')); ?>
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
	    			<?php echo anchor("products/index/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('products/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->code;?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->ptname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td><?php echo $row->wname;?></td>
			<td><?php echo $row->base_unit.' '.$row->uname;?></td>
			<td align="center"><?php echo ($row->alert_quantity == 0 || $row->alert_quantity == null ? '-' : $row->alert_quantity.' '.$row->uname); ?></td>
			<td align="center"><?php echo ($row->retail_price == 0 ? '-' : $row->retail_price.$G_currency); ?></td>
			<td align="center"><?php echo ($row->whole_price1 == 0 ? '-' : $row->whole_price1.$G_currency); ?></td>
			<td align="center"><?php echo ($row->whole_price2 == 0 ? '-' : $row->whole_price2.$G_currency); ?></td>
			<td align="center"><?php echo $row->commision.$G_currency; ?></td>
			<td align="center"><?php echo $row->rate.'%'; ?></td>
			<td class="functions">
				<?php echo anchor('products/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('products/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
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