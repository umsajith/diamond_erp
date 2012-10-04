<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('orders_list');?>" class="button"><span class="add">Внес</span></a>
<div class="filters"> 
    <?php echo form_open('orders/search');?>
	    <?php echo form_dropdown('partner_fk', $customers, set_value('partner_fk')); ?>
	    <?php echo form_dropdown('distributor_fk', $distributors, set_value('distributor_fk')); ?>
	    <?php echo form_dropdown('payment_mode_fk', $modes_payment, set_value('payment_mode_fk')); ?>
	    <?php echo form_dropdown('postalcode_fk',$postalcodes, set_value('postalcode_fk'));?>
	    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
</div>
<table class="master_table">   
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
    	<th>&nbsp;</th>
    	<th>&nbsp;</th>
    	<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
    			<?php echo anchor("orders/index/$query_id/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    <?php endforeach;?>
    	<th>&nbsp;</th>
    </tr>
	<?php foreach($results as $row):?>
	<tr>
			<td class="code" align="center"><?php echo anchor('orders/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td class="code" align="center"><?php echo ($row->locked == 0 ? '' : "<span class='lock_icon'></span>");?></td>
			<td><?php echo (($row->dateshipped == null) || ($row->dateshipped == '0000-00-00') ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateshipped))); ?></td>
			<td><?php echo $row->company;?></td>
			<td><?php echo $row->fname . ' ' . $row->lname; ?></td>
			<td><?php echo ($row->name == null ? '-' : $row->name); ?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
			<td><?php echo ($row->order_list_id) ? 
				anchor("orders_list/view/{$row->order_list_id}",'Линк') :'-'; ?></td>	
			<td class="functions">
			<?php if($row->locked != 1):?>
				<?php echo anchor('orders/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('orders/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>