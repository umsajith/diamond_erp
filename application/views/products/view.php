<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("products/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("products/delete/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span6 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.name')?>:</dt>
            <dd><?=$master->prodname?></dd>
            <dt><?=uif::lng('attr.code')?>:</dt>
            <dd><?=uif::isNull($master->code)?></dd>
            <dt><?=uif::lng('attr.type')?>:</dt>
            <dd><?=$master->ptname?></dd>
            <dt><?=uif::lng('attr.category')?>:</dt>
            <dd><?=$master->pcname?></dd>
            <dt><?=uif::lng('attr.warehouse')?>:</dt>
            <dd><?=$master->wname?></dd>
            <dt><?=uif::lng('attr.uom')?>:</dt>
            <dd><?=$master->uname?></dd>
            <dt><?=uif::lng('attr.base_unit')?>:</dt>
            <dd><?=uif::isNull($master->base_unit)?></dd>
            <dt><?=uif::lng('attr.retail_price')?>:</dt>
            <dd><?=uif::isNull($master->retail_price).$glCurrSh?></dd>
            <dt><?=uif::lng('attr.wholesale_price')?>:</dt>
            <dd><?=uif::isNull($master->whole_price1).$glCurrSh?></dd>
            <dt><?=uif::lng('attr.tax')?>:</dt>
            <dd><?=$master->rate.'%'?></dd>
            <dt><?=uif::lng('attr.commision')?>:</dt>
            <dd><?=uif::isNull($master->commision).$glCurrSh?></dd>
            <dt><?=uif::lng('attr.alert_quantity')?>:</dt>
            <dd><?=uif::isNull($master->alert_quantity)?></dd>
            <dt><?=uif::lng('attr.note')?>:</dt>
            <dd><?=uif::isNull($master->description)?></dd>
            <dt><?=uif::lng('attr.salable')?>:</dt>
            <dd><?=($master->salable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt><?=uif::lng('attr.purchasable')?>:</dt>
            <dd><?=($master->purchasable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt><?=uif::lng('attr.stockable')?>:</dt>
            <dd><?=($master->stockable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
        </dl>
    </div>
</div>