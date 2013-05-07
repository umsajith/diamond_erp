<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('partners/insert')?>
	</div>
	<div class="span9 text-right" id="content-main-filters">
		<?=form_open('partners/search','class="form-inline"')?>
			<?=uif::formElement('text','','q','','placeholder="Пребарувај по Код/Фирма"')?>
			<?=uif::formElement('dropdown','','postalcode_fk',[$postalcodes])?>
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
    	<th <?=($sort_by==$col_name) ? "class={$sort_order}" : "";?>>
			<?=anchor("partners/index/{$query_id}/{$col_name}/".
			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    </th>
	    <?php endforeach;?>
	    <th>Телефон</th>
	    <th>Седиште</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($results as $row):?>
		<tr data-id=<?=$row->id?>>
			<td><?=uif::viewIcon('partners',$row->id)?></td>
			<td><?=($row->is_mother) ? uif::staticIcon('icon-building') : ''?></td>
			<td><?=$row->id?></td>
			<td><?=$row->company?></td>
			<td><?=uif::isNull($row->contperson)?></td>			
			<td><?=$row->name?></td>
			<td><?=uif::isNull($row->phone1)?></td>	
			<td><?=($row->mother_name)?uif::linkIcon("partners/view/{$row->mother_id}",'icon-link'):'-';?></td>
			<td><?=uif::actionGroup('partners',$row->id)?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>	
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>
<script>
	$(function(){
		cd.dd("select[name=postalcode_fk]",'Град');
	});	
</script>