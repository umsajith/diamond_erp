<h2><?php echo $heading; ?></h2>
<hr>
	<div id="buttons">
		<a href="#" class="button" id="change_calculation"><span class="edit">Измена</span></a>
		<a href="#" class="button" id="pdf" onClick="createPdf();"><span class="pdf">PDF</span></a>
	</div>
<hr>
<?php echo form_open('payroll/report',"id='report'");?>
<div class="report_calc">
	<table>
		<tr>
		    <td class="label"><?php echo form_label('Од:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('date_from',(!isset($date_from) ? '' : $date_from),'id="datefrom"'); ?></td>
		    <td><?php echo form_dropdown('employee_fk', $employees); ?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('До:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('date_to',(!isset($date_to) ? '' : $date_to),'id="dateto"'); ?></td>
		    <?php echo form_hidden('submited',set_value('submited',$submited)); ?>
		    <td align="right"><?php echo form_submit('','Преглед');?></td>
		</tr>
	</table>
</div>
<?php echo form_close();?>
<?php echo validation_errors(); ?>

<table class="master_table"> 
<?php if (isset($results) AND count($results) > 0):?>
	<thead>
		<tr>
	    	<th>Ставка</th>
	    	<th>Вкупно</th>
	    	<th>Просек</th>
	    	<th>Максимум</th>
	    	<th>Минимум</th>    	
    	</tr>
    </thead>
    <tbody>
		<tr>
			<td><?php echo 'Учинок';?></td>
			<td><?php echo $results->sum_acc_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_acc_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_acc_wage. $G_currency;?></td>
			<td><?php echo $results->min_acc_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Придонес';?></td>
			<td><?php echo $results->sum_social_cont. $G_currency;?></td>
			<td><?php echo round($results->avg_social_cont,2). $G_currency;?></td>
			<td><?php echo $results->max_social_cont. $G_currency;?></td>
			<td><?php echo $results->min_social_cont. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Додатоци';?></td>
			<td><?php echo $results->sum_bonuses. $G_currency;?></td>
			<td><?php echo round($results->avg_bonuses,2). $G_currency;?></td>
			<td><?php echo $results->max_bonuses. $G_currency;?></td>
			<td><?php echo $results->min_bonuses. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Бруто';?></td>
			<td><?php echo $results->sum_gross_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_gross_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_gross_wage. $G_currency;?></td>
			<td><?php echo $results->min_gross_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Трошоци';?></td>
			<td><?php echo $results->sum_expenses. $G_currency;?></td>
			<td><?php echo round($results->avg_expenses,2). $G_currency;?></td>
			<td><?php echo $results->max_expenses. $G_currency;?></td>
			<td><?php echo $results->min_expenses. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Фиксна Плата';?></td>
			<td><?php echo $results->sum_fixed_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_fixed_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_fixed_wage. $G_currency;?></td>
			<td><?php echo $results->min_fixed_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Тел. Субвенција';?></td>
			<td><?php echo $results->sum_comp_mobile_sub. $G_currency;?></td>
			<td><?php echo round($results->avg_comp_mobile_sub,2). $G_currency;?></td>
			<td><?php echo $results->max_comp_mobile_sub. $G_currency;?></td>
			<td><?php echo $results->min_comp_mobile_sub. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Доплата';?></td>
			<td><?php echo $results->sum_paid_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_paid_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_paid_wage. $G_currency;?></td>
			<td><?php echo $results->min_paid_wage. $G_currency;?></td>
		</tr>
<?php else:?>
	<?php if(empty($results)):?>
		<?php $this->load->view('includes/_no_records');?>
	<?php endif;?>
<?php endif;?>
</tbody>
</table>
<div id="container" style="margin: 0"></div>


<script type="text/javascript">

	$(function() {

		var submited = '<?php echo $submited; ?>';

		if(submited == 0)
			$("#pdf").hide();

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
	});

	function createPdf()
	{
		$.download("<?php echo site_url('payroll/report_pdf'); ?>",
			 $("form#report").serialize());
	}

</script>