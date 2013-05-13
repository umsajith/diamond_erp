<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("positions/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("positions/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
			<dt><?=uif::lng('attr.name')?>:</dt>
			<dd><?=$master->position;?></dd>
			<dt><?=uif::lng('attr.department')?>:</dt>
			<dd><?=$master->department;?></dd>
			<dt><?=uif::lng('attr.wage')?>:</dt>
			<dd><?=uif::isNull($master->base_salary)?></dd>
			<dt><?=uif::lng('attr.bonus')?>:</dt>
			<dd><?=uif::isNull($master->bonus,' %')?></dd>
			<dt><?=uif::lng('attr.commision')?>:</dt>
			<dd><?=uif::isNull($master->commision,' %')?></dd>
			<dt><?=uif::lng('attr.qualifications')?>:</dt>
			<dd><?=uif::isNull($master->requirements)?></dd>
			<dt><?=uif::lng('attr.note')?>:</dt>
			<dd><?=uif::isNull($master->description)?></dd>   
		</dl>
	</div>
</div>