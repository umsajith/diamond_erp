<h2><?php echo $heading?></h2>
<hr>
<a href="<?php echo site_url('inventory/insert_adj');?>" class="button"><span class="add">Внес</span></a>
<div class="filers">
    <?php echo form_open('inventory/adj_search');?>
	    <?php echo form_dropdown('prodname_fk', $products, set_value('prodname_fk')); ?>
	    <?php echo form_dropdown('pcname_fk',$categories, set_value('pcname_fk')); ?>
	    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
    			<?php echo anchor("inventory/adjustments/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    <?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor("inventory/view/adj/$row->id",'&nbsp;','class="view_icon"');?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>
			<td class="functions">
				<?php echo anchor('inventory/delete/adj/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>