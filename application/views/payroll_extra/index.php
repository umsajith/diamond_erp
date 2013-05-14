<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('payroll_extra/insert_bonus')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('payroll_extra/search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','employee_fk',[$employees])?>
			<?=uif::formElement('dropdown','','payroll_extra_cat_fk',[$categories])?>
			<?=uif::filterButton()?>
    	<?=form_close()?>
	</div>
</div>
<hr>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid"> 
	<thead>
		<tr>
			<th colspan="2">&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
    			<?=anchor("payroll_extra/index/{$query_id}/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
			</th>
	    	<?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('payroll_extra',$row->id)?></td>
			<td><?=($row->locked) ? uif::staticIcon('icon-lock') : ''?></i></td>
			<td><?=$row->fname.' '.$row->lname?></td>
			<td><?=$row->name?></td>
			<td><?=$row->amount.$glCurrSh?></td>
			<td><?=uif::date($row->for_date)?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=(!$row->locked) ? uif::actionGroup('payroll_extra',$row->id) : ''?></td>		
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>

<script>
	$(function(){
		cd.dd("select[name=employee_fk]","<?=uif::lng('attr.employee')?>");
		cd.dd("select[name=payroll_extra_cat_fk]","<?=uif::lng('attr.category')?>");
	});	
</script>