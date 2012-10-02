<h2><?php echo $heading; ?></h2>
	<?php echo form_open('orders_list/search'); ?>
		<div id="searchBox">
			<?php echo form_input('q',set_value('q'),"placeholder='Документ/Код'"); ?>
			<?php echo form_submit('','',"class='search'");  ?>
		</div>
	<?php echo form_close(); ?>
<hr>
	<a href="<?php echo site_url('orders_list/insert');?>" class="button"><span class="add">Внес</span></a>
	<div class="filters"> 
    <?php echo form_open('orders_list/search');?>
	    <?php echo form_dropdown('distributor_id', $distributors, set_value('distributor_id')); ?>
	    <?php echo form_hidden('q','');  ?>
	    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
	</div>
<table class="master_table">
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("orders_list/index/$query_id/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach ($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('orders_list/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->date)); ?></td>
			<td><?php echo $row->distributor; ?></td>
			<td><?php echo ($row->ext_doc) ? $row->ext_doc : '-' ; ?></td>
			<td><?php echo ($row->code) ? $row->code : '-' ; ?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
			<td class="functions">
				<?php echo anchor('orders_list/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('orders_list/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>	
</table>
<?php $this->load->view('includes/_pagination');?>