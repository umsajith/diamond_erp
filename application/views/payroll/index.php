<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkButton('payroll/calculate','icon-cogs btn-large')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('payroll/search','class="form-inline"')?>
			<?=uif::formElement('dropdown','','employee_fk',[$employees])?>
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
	    		<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
	    			<?=anchor("payroll/index/{$query_id}/{$col_name}/".
	    				(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
				</th>
	    	<?php endforeach;?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('payroll',$row->id)?></td>
			<td><?=$row->fname.' '.$row->lname?></td>
			<td><?=uif::date($row->date_from)?></td>
			<td><?=uif::date($row->date_to)?></td>
			<td><?=uif::isNull($row->acc_wage)?></td>
			<td><?=$row->bonuses?></td>
			<td><?=$row->gross_wage?></td>
			<td><?=$row->fixed_wage?></td>
			<td><?=$row->expenses?></td>
			<td><?=$row->paid_wage.$G_currency?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<td><?=uif::linkIcon("payroll/delete/{$row->id}",'icon-trash confirm-delete')?></td>
		</tr>
	<?php endforeach;?>
	</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=employee_fk]",'Работник');
	});	
</script>