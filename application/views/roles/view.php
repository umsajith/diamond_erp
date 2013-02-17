<h2><?php echo $heading . ': '.$result->name; ?></h2>
<hr>
<?php echo form_open('roles/assign_resource',"id='assign_resource'"); ?>
	<table>
		<tr>
			<td><?php echo form_dropdown('resource_id',$dd_resources ,set_value('resource_id'));?>&nbsp;</td>
			<td><?php echo form_dropdown('permission',$dd_permissions,set_value('permission'));?>&nbsp;</td>
			<td><a href="#" class="button" id="assign_resource_btn"><span class="add">Додади</span></a></td>
		</tr>
	</table>
	<?php echo form_hidden('role_id',$result->id); ?>
<?php echo form_close(); ?>
<?php echo  validation_errors(); ?>
<hr>
<table class="master_table">
<?php if (isset($resources) AND is_array($resources) AND count($resources) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>Title</th>
		<th>Parent</th>
		<th>Controller</th>
		<th>Permission</th>
		<th>&nbsp;</th>
	</tr>
	<?php $i=1; ?>
	<?php foreach($resources as $row):?>
		<tr>
			<td class="code"><?php echo $i; $i++;?></td>
			<td><?php echo $row->ctitle;?></td>
			<td><?php echo (!$row->ptitle)?'-':$row->ptitle;?></td>
			<td><?php echo $row->controller;?></td>
			<td><?php echo $row->permission;?></td>
			<td class="functions">
				<?php echo anchor("permissions/delete/{$row->id}",'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>

<script>
	$(function() {
		$("#assign_resource_btn").on('click',function(){
			var errors = false;

			var role_id = $("input[name=role]"),
				resource_id = $("select[name=resource_id] option:selected"),
				permission = $("select[name=permission] option:selected");

			if(role_id.val()==""){
			    errors = true;
			}
			if(resource_id.val()==""){
				$.pnotify({pnotify_text:"Resource required!",pnotify_type: "info"});
				resource_id.focus();
			     errors = true;
			}
			if(permission.val()==""){
				$.pnotify({pnotify_text:"Permission required!",pnotify_type: "info"});
				permission.focus();
			     errors = true;
			}

			if(errors === false)
				$("form#assign_resource").submit();
		});
	});
</script>