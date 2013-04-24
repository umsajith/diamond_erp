<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("products/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("products/delete/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span6 well well-small">  
        <dl class="dl-horizontal">
            <dt>Назив:</dt>
            <dd><?=$master->prodname?></dd>
            <dt>Код:</dt>
            <dd><?=uif::isNull($master->code)?></dd>
            <dt>Тип:</dt>
            <dd><?=$master->ptname?></dd>
            <dt>Категорија:</dt>
            <dd><?=$master->pcname?></dd>
            <dt>Магацин:</dt>
            <dd><?=$master->wname?></dd>
            <dt>ЕМ:</dt>
            <dd><?=$master->uname?></dd>
            <dt>Основна Единица:</dt>
            <dd><?=uif::isNull($master->base_unit)?></dd>
            <dt>МП Цена:</dt>
            <dd><?=uif::isNull($master->retail_price)?></dd>
            <dt>ГП Цена 1:</dt>
            <dd><?=uif::isNull($master->whole_price1)?></dd>
            <dt>ГП Цена 2:</dt>
            <dd><?=uif::isNull($master->whole_price2)?></dd>
            <dt>Данок (%):</dt>
            <dd><?=$master->rate.'%'?></dd>
            <dt>Провизија:</dt>
            <dd><?=uif::isNull($master->commision)?></dd>
            <dt>Мин. Количина:</dt>
            <dd><?=uif::isNull($master->alert_quantity)?></dd>
            <dt>Опис:</dt>
            <dd><?=uif::isNull($master->description)?></dd>
            <dt>Се Продава:</dt>
            <dd><?=($master->salable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt>Се Купува:</dt>
            <dd><?=($master->purchasable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt>Состојба:</dt>
            <dd><?=($master->stockable) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
        </dl>
    </div>
</div>