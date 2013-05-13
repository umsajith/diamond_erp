<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("tasks/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("tasks/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.name')?>:</dt>
            <dd><?=$master->taskname;?></dd>
            <dt><?=uif::lng('attr.base_unit')?></dt>
            <dd><?=$master->base_unit.' '.$master->uname?></dd>
            <dt><?=uif::lng('attr.bom')?>:</dt>
            <dd><?=($master->is_production) ? $master->name : '-'?></dd>
            <dt><?=uif::lng('attr.price_per_uom')?>:</dt>
            <dd><?=$master->rate_per_unit.$glCurrSh.'/'.$master->uname?></dd>
            <dt><?=uif::lng('attr.price_plus_per_uom')?>:</dt>
            <dd><?=(!is_null($master->rate_per_unit_bonus)) ?
                $master->rate_per_unit_bonus.$glCurrSh.'/'.$master->uname : '-'?></dd>
            <dt><?=uif::lng('attr.note')?>:</dt>
            <dd><?=uif::isNull($master->description)?></dd>   
        </dl>
    </div>
</div>