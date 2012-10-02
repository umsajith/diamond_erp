<h2><?php echo $heading; ?></h2>
<hr>
	<a href="<?php echo site_url('tproduct/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
			<th>&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("tproduct/index/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $row->ptname;?></td>
			<td class="functions">
				<?php echo anchor('tproduct/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('tproduct/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
	</tbody>
</table>
<?php $this->load->view('includes/_pagination');?>