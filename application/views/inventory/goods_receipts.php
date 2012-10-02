<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('inventory/insert_gr');?>" class="button"><span class="add">Внес</span></a>
<div class="filters">
    <?php echo form_open('inventory/gr_search');?>
    <?php echo form_dropdown('prodname_fk', $products, set_value('prodname_fk')); ?>
    <?php echo form_dropdown('partner_fk', $vendors, set_value('partner_fk')); ?>
    <?php echo form_dropdown('pcname_fk',$categories, set_value('pcname_fk')); ?>
    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
			<th>&nbsp;</th>
	    	<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("inventory/goods_receipts/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
	    	<th>Вкупно</th>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor("inventory/view/gr/$row->id",'&nbsp;','class="view_icon"');?></td>
			<td><?php echo (!$row->datereceived) ? '-': mdate('%d/%m/%Y',mysql_to_unix($row->datereceived)); ?></td>		
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->company;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>	
			<td><?php 
				switch ($row->purchase_method) 
				{
				    case '0':
				       echo '-';
				        break;
				    case 'cash':
				        echo 'Готовина';
				        break;
				   	case 'invoice':
				        echo 'Фактура';
				        break;
				}
			?></td>
			<td><?php echo (!$row->price) ? '-': $row->price.$G_currency; ?></td>
			<td><?php echo (!$row->dateoforder) ? '-': mdate('%d/%m/%Y',mysql_to_unix($row->dateoforder)); ?></td>
			<td><?php echo (!$row->dateofentry) ? '-': mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry)); ?></td>
			<td><?php echo ($row->quantity*$row->price==0)?'-':round($row->quantity*$row->price,2).$G_currency;?></td>
			<td class="functions">
				<?php echo anchor('inventory/edit/gr/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('inventory/delete/gr/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
	</tbody>
</table>
<?php $this->load->view('includes/_pagination');?>