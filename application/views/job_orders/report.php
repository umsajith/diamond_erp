<h2><?php echo $heading; ?></h2>
<hr>
	<div id="buttons">
		<a href="#" class="button" id="change_calculation"><span class="edit">Измена</span></a>
		<a href="#" class="button" id="pdf" onClick="createPdf();"><span class="pdf">PDF</span></a>
	</div>
<hr>
<div class="report_calc">
<?php echo form_open('job_orders/report',"id='report'");?>
	<table>
		<tr>
		    <td class="label"><?php echo form_label('Од:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('datefrom',(!isset($datefrom) ? '' : $datefrom),'id="datefrom"'); ?></td>
		    <td><?php echo form_dropdown('assigned_to', $employees); ?></td>
		    <td><?php echo form_dropdown('shift', array(''=>'- Смена -','1'=>'1','2'=>'2','3'=>'3')); ?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('До:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('dateto',(!isset($dateto) ? '' : $dateto),'id="dateto"'); ?></td>
		    <td><?php echo form_dropdown('task_fk', $tasks); ?></td>
		    <?php echo form_hidden('submited',set_value('submited',$submited)); ?>
		    <td align="right"><?php echo form_submit('','Преглед');?></td>
		</tr>
	</table>
<?php echo form_close();?>
<?php echo validation_errors(); ?>
</div>

<table class="master_table"> 
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<thead>
		<tr>
	    	<th>Работна Задача</th>
	    	<th>Вкупно</th>
	    	<th>Просек</th>
	    	<th>Максимум</th>
	    	<th>Минимум</th>
	    	<th>Раб.Налози</th>    	
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo $row->sum.' '.$row->uname;?></td>
			<td><?php echo round($row->avg,2).' '.$row->uname;?></td>
			<td><?php echo $row->max.' '.$row->uname;?></td>
			<td><?php echo $row->min.' '.$row->uname;?></td>
			<td><?php echo $row->count;?></td>
		</tr>
	<?php endforeach;?>
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
		$.download("<?php echo site_url('job_orders/report_pdf'); ?>",
			 $("form#report").serialize());
	}

</script>