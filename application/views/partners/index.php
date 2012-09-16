<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('partners/insert');?>" class="button"><span class="add">Внес</span></a>
	<?php $this->load->view('includes/_total_rows');?>
<div class="filers"> 
    <?php echo form_open('partners/search');?>
	    <?php echo form_dropdown('partner_type',array(''=>'- Тип -','cus'=>'Купувачи','ven'=>'Добавувачи','cus_ven'=>'Купувачи/Добавувачи'),set_value('partner_type'));?>
	    <?php echo form_dropdown('postalcode_fk',$postalcodes,set_value('postalcode_fk'));?>
	    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("partners/index/$query_id/$col_name/".(($sort_order=='desc' && $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    <?php endforeach;?>
	    <th>Телефон</th>
	    <th>Седиште</th>
		<th>Тип</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code" align="center"><?php echo anchor('partners/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td class="code" align="center"><?php echo ($row->is_mother == 0 ? '' : anchor('#','&nbsp;','class="medal1_gold" id="medal1_gold"'));?></td>
			<td><?php echo $row->company;?></td>
			<td><?php echo ($row->contperson)?$row->contperson:'-';?></td>		
			<td><?php echo $row->name;?></td>
			<td><?php echo ($row->phone1)?$row->phone1:'-';?></td>	
			<td><?php echo ($row->mother_name)?anchor("partners/view/$row->mother_id",$row->mother_name):'-';?></td>		
			<td>
				<?php 	if($row->is_customer==1 && $row->is_vendor==0) echo 'Купувач';
						if($row->is_vendor==1 && $row->is_customer==0) echo 'Добавувач';
						if($row->is_vendor==1 && $row->is_customer==1) echo 'Купувач/Добавувач';
				?>
			</td>	
			<td class="functions">
				<?php echo anchor('partners/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('partners/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>
<?php $this->load->view('includes/_del_dialog');?>

<script type="text/javascript">
	$(document).ready(function(){
		$("a#medal1_gold").click(function(){
			return false;
		});
	});
</script>