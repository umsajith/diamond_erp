<h2><?php echo $heading; ?></h2>
<?php echo form_open("uom/edit/{$result->id}"); ?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
	<td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
	<td><?php echo form_input('uname', set_value('uname',$result->uname));?></td>
	<?php echo form_hidden('id',$result->id); ?>
</tr>
<?php form_close();?>
</table>
<?php echo validation_errors(); ?>