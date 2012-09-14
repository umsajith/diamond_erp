<h2><?php echo $heading; ?></h2>
<?php echo form_open('modules/edit/'.$module->id);?>
<hr>
	<?php echo form_submit('','Save','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Title:');?><span class='req'>*</span></td>
    <td><?php echo form_input('title',set_value('title', $module->title));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Folder:');?></td>
    <td><?php echo form_input('folder', set_value('folder', $module->folder));?></td>
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
    <td class="label"><?php echo form_label('Status:');?></td>
    <td><?php echo form_dropdown('status', array('active'=>'Active','inactive'=>'Inactive'),set_value('status', $module->status));?></td>
</tr>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>
<table id="calculations">
<caption><strong>Insert New Sub-Module</strong></caption>
<?php echo form_open('',"id='sub_module'");?>
<tr>
    <td class="label"><?php echo form_label('Title:');?><span class='req'>*</span></td>
    <td><?php echo form_input('title', set_value('title'));?></td>
</tr>
<?php echo form_hidden('module_id',set_value('module_id',$module->id));?>
<tr>
    <td class="label"><?php echo form_label('Controller: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('controller', set_value('controller'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Method: ');?></td>
    <td><?php echo form_input('method', set_value('method'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Permalink: ');?></td>
    <td><?php echo form_input('permalink', set_value('permalink'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Order: ');?></td>
    <td><?php echo form_input('order', set_value('order'));?></td>
</tr>
<tr>
	<td>&nbsp;</td>
    <td><?php echo form_button('','Add',"id='add_smodule'");?></td>
</tr>
<?php echo form_close();?>
</table>

<table class="data_forms">
<tr>
	<th>Title</th>
	<th>Controller</th>
	<th>Method</th>
	<th>Order</th>
	<th></th>
</tr>
<?php foreach ($sub_modules as $sm):?>
	<tr>
		<td><?php echo $sm->title;?></td>
		<td><?php echo $sm->controller;?></td>
		<td><?php echo $sm->method;?></td>
		<td><?php echo $sm->order;?></td>
		<td align="center" width="100px"><span class="removeprod" id="<?php echo $sm->id;?>">&nbsp;</span></td>
	</tr>
	<?php endforeach;?>
</table>

<script type="text/javascript">
	$(document).ready(function() {

		$("span.removeprod").click(function(){
			var id = $(this).attr("id");
			$.post("<?php echo site_url('sub_modules/delete')?>",
					{id:id},
					function(data){
						if(data){
							location.reload(true);
						}			
				});
			return false;
		});

		$("button#add_smodule").click(function(){
			
			var form_data = $("form#sub_module").serialize();
			
			$.post("<?php echo site_url('sub_modules/insert')?>",form_data,
					function(data)
					{
						if(data)
							location.reload(true);
						else
							alert("Unknown error!");
							
					    return false;
					}
			);
		});
	});
</script>
















