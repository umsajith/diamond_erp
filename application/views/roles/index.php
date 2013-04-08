<h2><?php echo $heading; ?></h2>
<hr>
	<a href="<?php echo site_url('roles/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("roles/index/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code"><?php echo anchor('roles/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td>&nbsp;</td>
			<td><?php echo $row->name;?></td>
			<td class="functions">
				<?php echo anchor('roles/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('roles/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
	</tbody>
</table>
<?php $this->load->view('includes/_pagination');?>