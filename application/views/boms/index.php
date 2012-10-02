<h2><?php echo $heading?></h2>
<hr/>
	<a href="<?php echo site_url('boms/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">   
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
    	<th>&nbsp;</th>
    	<?php foreach ($columns as $col_name => $col_display):?>
			<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
				<?php echo anchor("boms/index/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
    	<?php endforeach;?>
    	<th>&nbsp;</th>
    </tr>
	<?php foreach($results as $row):?>
	<tr>
		<td class="code" align="center"><?php echo anchor('boms/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
		<td><?php echo $row->name;?></td>
		<td><?php echo $row->quantity .' '. $row->uname2;?></td>
		<td><?php echo ($row->prodname) ? $row->prodname : '-'; ?></td>
		<td><?php echo ($row->quantity * $row->conversion) 
						? $row->quantity*$row->conversion.' '.$row->uname
						: '-';?></td>
		<td class="functions">
			<?php echo anchor('boms/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
			<?php echo anchor('boms/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
		</td>
	</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>