<h2><?php echo $heading; ?></h2>
<?php echo form_open();?>
<hr>
<div id="buttons">
	<?php echo form_submit('','Сними',"class='save'"); ?>
</div>
<hr>
<table class="data_forms_jo"> 
    <tr>
	    <td class="label"><?php echo form_label('Датум:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('date',set_value('datedue')); ?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Дистрибутер:');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('distributor_id', $distributors,set_value('distributor_id')); ?></td>
	</tr>
	 <tr>
	    <td class="label"><?php echo form_label('Документ:');?></td>
	    <td><?php echo form_input('ext_doc',set_value('ext_doc')); ?></td>
	</tr>
	 <tr>
	    <td class="label"><?php echo form_label('Белешка:');?></td>
	    <td><?php echo form_textarea('note',set_value('note')); ?></td>
	</tr>
</table >
<?php echo form_close();?>
<script type="text/javascript">
	$(function() {
		
		$( "input[name=date]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0
		});

	});
</script>