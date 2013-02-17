<h2><?php echo $heading; ?></h2>
<?php echo form_open('resources/edit/'.$resource->id);?>
<hr>
	<?php echo form_submit('','Save','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
	    <td class="label"><?php echo form_label('Title:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('title',set_value('title', $resource->title));?></td>
	</tr>
	<tr>
        <td class="label"><?php echo form_label('Parent: ');?></td>
        <td><?php echo form_dropdown('parent_id', $parents, set_value('parent_id',$resource->parent_id));?></td>
    </tr>
    <tr>
	    <td class="label"><?php echo form_label('Permalink:');?></td>
	    <td><?php echo form_input('permalink', set_value('permalink', $resource->permalink));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Folder:');?></td>
	    <td><?php echo form_input('folder', set_value('folder', $resource->folder));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Controller:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('controller', set_value('controller', $resource->controller));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Method:');?></td>
	    <td><?php echo form_input('method', set_value('method', $resource->method));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Order:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('order', set_value('order', $resource->order));?></td>
	</tr>
	<tr>
        <td class="label"><?php echo form_label('Visible:');?></td>
        <td><?php echo form_checkbox('visible','1',($resource->visible=='1'?true:false));?></td>
    </tr>
	<?php echo form_hidden('id',$resource->id); ?>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>