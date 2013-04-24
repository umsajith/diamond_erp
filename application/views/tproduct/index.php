<?=uif::contentHeader($heading)?>
<div class="row-fluid">
		<div class="span3" id="content-main-buttons">
		<?=uif::linkInsertButton('tproduct/insert')?>
		<hr>
	</div>
</div>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid">  
	<thead>
		<tr>
	    	<?php foreach ($columns as $col_name => $col_display):?>
    		<th <?=($sort_by==$col_name) ? "class={$sort_order}" : ""?>>
    			<?=anchor("tproduct/index/{$col_name}/".
    			(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
    		</th>
	    	<?php endforeach;?>
	    	<th>&nbsp;</th>
    	</tr>
    </thead>
    <tbody>
		<?php foreach($results as $row):?>
		<tr>
			<td><?=$row->ptname?></td>
			<td><?=uif::actionGroup('tproduct',$row->id)?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>