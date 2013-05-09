<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("distribution/edit/{$page}/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("distribution/delete/{$page}/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
	        <dt><?=uif::lng('attr.date')?>:</dt>
	        <dd><?=uif::date($master->dateoforigin)?></dd>         
	        <dt><?=uif::lng('attr.item')?>:</dt>
	        <dd><?=$master->prodname?></dd>
	        <dt><?=uif::lng('attr.quantity')?>:</dt>
	        <dd><?=$master->quantity .' '.  $master->uname?></dd>
	        <?php if(in_array($page,['out','ret'])): ?> 
	        	<dt><?=uif::lng('attr.distributor')?>:</dt>
	        	<dd><?=(!is_null($master->distributor_fk)) ? $master->fname. ' '.$master->lname : '-';?></dd>
	        <?php endif; ?> 
	        <dt><?=uif::lng('attr.document')?>:</dt>
	        <dd><?=uif::isNull($master->ext_doc)?></dd> 
	        <dt><?=uif::lng('attr.note')?>:</dt>
	        <dd><?=($master->note) ? $master->note : '-'?></dd>
	        <dt><?=uif::lng('attr.operator')?>:</dt>
	        <dd><?=$master->assignfname .' '.  $master->assignlname?></dd>
		</dl>
	</div>
	<div class="span7">
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
        <div class="legend"><?=uif::lng('app.used_components')?></div>
		<table class="table table-condensed table-bordered">
	        <thead>
			    <tr>
			    	<th><?=uif::lng('attr.item')?></th>
			    	<th><?=uif::lng('attr.category')?></th>
			    	<th><?=uif::lng('attr.quantity')?></th>
			    </tr>
		    </thead>
		    <tbody>
				<?php foreach($details as $row):?>
				<tr>
					<td><?=$row->prodname?></td>
					<td><?=$row->pcname?></td>
					<td><?=$row->quantity.' '.$row->uname?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
	</div>
</div>