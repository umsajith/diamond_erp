<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('positions/insert');?>" class="button"><span class="add">Внес</span></a>
<table class="master_table">
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
    			<?php echo anchor("positions/index/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('positions/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td><?php echo $row->position;?></td>
			<td><?php echo $row->department;?></td>
			<td align="center"><?php echo ($row->base_salary == 0 ? '-' : $row->base_salary); ?></td>
            <td align="center"><?php echo ($row->bonus == 0 ? '-' : $row->bonus . '%'); ?></td>
            <td align="center"><?php echo ($row->commision == 0 ? '-' : $row->commision . '%'); ?></td>
			<td class="functions">
				<?php echo anchor('positions/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('positions/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>