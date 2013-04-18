<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('employees/insert')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('employees/search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','poss_fk',[$possitions])?>
			<?=uif::formElement('dropdown','','role_id',[$roles])?>
			<?=uif::filterButton()?>
    	<?=form_close()?>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid">  
	<thead>
	<tr>
		<th>&nbsp;</th>
		<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?=($sort_by==$col_name) ? "class=$sort_order" : ""?>>
    			<?=anchor("employees/index/{$query_id}/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('employees',$row->id)?></td>
			<td><?=$row->fname. ' '.$row->lname?></td>
			<td><?=uif::isNull($row->comp_mobile)?></td>
			<td><?=$row->position?></td>
			<td><?=$row->department?></td>
			<td><?=($row->fixed_wage_only) ? uif::staticIcon('icon-ok'):'-'?></td>
			<td><?=($row->is_manager) ? uif::staticIcon('icon-ok'):'-'?></td>
			<td><?=($row->is_distributer) ? uif::staticIcon('icon-ok'):'-'?></td>
			<td><?=uif::isNull($row->fixed_wage)?></td>
			<td><?=($row->status=='active') ? 
				uif::staticIcon('icon-ok-sign text-success') : 
				uif::staticIcon('icon-minus-sign text-error')?></td>
			<td><?=uif::actionGroup('employees',$row->id)?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>

<script>
	$(function(){
		$("select").select2();
	});	
</script>