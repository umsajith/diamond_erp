<?=uif::contentHeader($heading.': '.$result->name)?>
<div class="row-fluid">
	<div class="span6">
		<?=form_open('roles/assign_resource','class="form-horizontal" id="assign-resource"')?>
                <div class="legend">Додавање Привилегии на Корисничка Група</div>
            <div class="well well-small">
                    <?=uif::controlGroup('dropdown','Ресур','resource_id',[$dd_resources],'class="input-large"')?>
                    <?=uif::controlGroup('dropdown','Привилегија','permission',[$dd_permissions],'class="input-large"')?>
                    <?=form_hidden('role_id',$result->id)?>
                	<div class="controls">
                		<?=uif::button('icon-plus-sign btn-large','success','onClick="cd.submit(#assign-resource);"')?>
                	</div>
            </div>  
        <?=form_close()?>
	</div>
</div>
<?php if (isset($resources) AND is_array($resources) AND count($resources)):?>
	<div class="legend">Привилегии кои оваа корисничка група ги поседува</div>
	<table class="table table-condensed table-stripped data-grid">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Title</th>
				<th>Parent</th>
				<th>Controller</th>
				<th>Permission</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<?php $i=1; ?>
		<tbody>
		<?php foreach($resources as $row):?>
			<tr>
				<td><?=$i; $i++;?></td>
				<td><?=$row->ctitle?></td>
				<td><?=(!$row->ptitle)?'-':$row->ptitle?></td>
				<td><?=$row->controller?></td>
				<td><?=($row->permission == 'allow') ? 
					uif::staticIcon('icon-ok-sign text-success') :
					uif::staticIcon('icon-minus-sign text-error')?>
				</td>
				<td><?=uif::linkIcon("permissions/delete/{$row->id}",'icon-trash')?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function() {
		$("select").select2();
	});
</script>