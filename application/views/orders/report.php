<h2><?php echo $heading; ?></h2>
<hr>
	<div id="buttons">
		<a href="#" class="button" id="change_calculation"><span class="edit">Измена</span></a>
		<a href="#" class="button" id="pdf"><span class="pdf">PDF</span></a>
	</div>
<hr>
<?php echo form_open('orders/report',"id='report'");?>
<fieldset class="report">
	<legend>Припрема на Рипорт</legend>
	
	<table id="calculation">
	<tr>
	    <td class="label"><?php echo form_label('Од:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('datefrom',(!isset($datefrom) ? '' : $datefrom),'id="datefrom"'); ?></td>
	    <td><?php echo form_dropdown('distributor_fk', $distributors); ?></td>
	    <td><?php echo form_dropdown('partner_fk',$customers);?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('До:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('dateto',(!isset($dateto) ? '' : $dateto),'id="dateto"'); ?></td>
	    <td><?php echo form_dropdown('payment_mode_fk', $modes_payment); ?></td>	    
	</tr>
	<tr>
		<td colspan="4" align="right"><?php echo form_submit('','Преглед');?></td>
	</tr>
	</table>
</fieldset>
<?php echo form_close();?>
<?php echo validation_errors(); ?>

<table class="master_table"> 
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<thead>
		<tr>
	    	<th>Производ</th>
	    	<th>Земено</th>
	    	<th>Вратено</th>   
	    	<th>% Вратено</th>    	
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>
			<td><?php echo $row->returned_quantity.' '.$row->uname;?></td>
			<td><?php echo round($row->returned_quantity/$row->quantity,3).' %';?></td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php if($submited == 1):?>
		<?php $this->load->view('includes/_no_records');?>
	<?php endif;?>
<?php endif;?>
</tbody>
</table>
<div id="container" style="margin: 0"></div>


<script type="text/javascript">

	$(function() {
		//Date Pickers From-To.
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

		$("#pdf").live("click",function(){
			var form = $("form#report");
			form.attr("action","<?php echo site_url('orders/report_pdf'); ?>");
			form.submit();
		});
		
	});
</script>