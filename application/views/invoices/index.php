<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('invoices/create');?>" class="button"><span class="add">Внес</span></a>
<div class="filers"> 
    <?php echo form_open('invoices/search');?>
	    <?php echo form_dropdown('partner_fk', $customers, set_value('partner_fk')); ?>
	    <?php echo form_dropdown('distributor_fk', $distributors, set_value('distributor_fk')); ?>
	    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">   
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Број</th>
    	<th>Купувач</th>
    	<th>Датум</th>
    	<th>Доспева</th>   	
    	<th>Нето</th>
    	<th>ДДВ</th>
    	<th>Вкупно</th>
    	<th>Период Од</th>
    	<th>Период До</th>
    	<th>&nbsp;</th>
    </tr>
	<?php foreach($results as $row):?>
	<tr>
		<td class="code" align="center"><?php echo anchor('invoices/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
		<td><?php echo $row->number;?></td>
		<td><?php echo $row->partner;?></td>
		<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->date));?></td>
		<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->date_due));?></td>			
		<td><?php echo $row->total_net.$G_currency;?></td>
		<td><?php echo $row->total_vat.$G_currency;?></td>
		<td><?php echo $row->total_net+$row->total_vat.$G_currency;?></td>
		<td><?php echo $row->date_from;?></td>
		<td><?php echo $row->date_to;?></td>
		<td class="functions">
				<?php echo anchor('invoices/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('invoices/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
		</td>
	</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_del_dialog');?>

<script type="text/javascript">
$(document).ready(function() {
		var dates = $( "#datefrom, #dateto" ).datepicker({
			dateFormat: "yy-mm-dd",
			onSelect: function( selectedDate ) {
				var option = this.id == "datefrom" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
});
</script>