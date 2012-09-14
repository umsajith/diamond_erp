<h2><?php echo $heading; ?></h2>
<?php echo form_open('sub_modules/edit/'.$module->id);?>
<hr>
	<?php echo form_submit('','Save','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Title:');?><span class='req'>*</span></td>
    <td><?php echo form_input('title',set_value('title', $module->title));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Module:');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('module_id', $modules,set_value('module_id', $module->module_id));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Controller:');?><span class='req'>*</span></td>
    <td><?php echo form_input('controller', set_value('controller', $module->controller));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Method:');?></td>
    <td><?php echo form_input('method', set_value('method', $module->method));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Permalink:');?></td>
    <td><?php echo form_input('permalink', set_value('permalink', $module->permalink));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Order:');?></td>
    <td><?php echo form_input('order', set_value('order', $module->order));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Visible:');?></td>
	<td><?php echo form_checkbox('is_visible','1',(isset($module->is_visible) && $module->is_visible=='1') ? true : false);?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Status:');?></td>
    <td><?php echo form_dropdown('status', array('active'=>'Active','inactive'=>'Inactive'),set_value('status', $module->status));?></td>
</tr>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>