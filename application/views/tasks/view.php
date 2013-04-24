<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("tasks/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("tasks/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt>Назив:</dt>
            <dd><?=$master->taskname;?></dd>
            <dt>Основна ЕМ</dt>
            <dd><?=$master->base_unit.' '.$master->uname?></dd>
            <dt>Норматив:</dt>
            <dd><?=($master->is_production) ? $master->name : '-'?></dd>
            <dt>Основна Цена:</dt>
            <dd><?=$master->rate_per_unit.$G_currency.'/'.$master->uname?></dd>
            <dt>Бонус Цена:</dt>
            <dd><?=(!is_null($master->rate_per_unit_bonus)) ?
                $master->rate_per_unit_bonus.$G_currency.'/'.$master->uname : '-'?></dd>
            <dt>Белешка:</dt>
            <dd><?=uif::isNull($master->description)?></dd>   
        </dl>
    </div>
</div>