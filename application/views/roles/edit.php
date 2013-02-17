<h2><?php echo $heading; ?></h2>
<?php echo form_open("roles/edit/{$result->id}"); ?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
		<td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
		<td><?php echo form_input('name', set_value('name',$result->name));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Усвојува од:');?></td>
		<td><?php echo form_dropdown('parent_id',$parents ,set_value('parent_id',$result->parent_id));?></td>
	</tr>
	<?php echo form_hidden('id',$result->id); ?>
<?php form_close();?>
</table>
<?php echo validation_errors(); ?>