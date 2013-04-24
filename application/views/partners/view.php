<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("partners/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("partners/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt>Назив:</dt>
            <dd><?=$master->company;?></dd>
            <dt>Контакт Лице:</dt>
            <dd><?=uif::isNull($master->contperson)?></dd>
            <dt>Купувач:</dt>
            <dd><?=($master->is_customer) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt>Добавувач:</dt>
            <dd><?=($master->is_vendor) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt>HQ:</dt>
            <dd><?=($master->is_mother) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt>Код:</dt>
            <dd><?=uif::isNull($master->id)?></dd>
            <dt>Адреса:</dt>
            <dd><?=uif::isNull($master->address)?></dd>
            <dt>Град:</dt>
            <dd><?=$master->name;?></dd>
            <dt>Поштенски Код:</dt>
            <dd><?=$master->postalcode;?></dd>
            <dt>Телефон:</dt>
            <dd><?=uif::isNull($master->phone1)?></dd>
            <dt>Телефон 2:</dt>
            <dd><?=uif::isNull($master->phone2)?></dd>
            <dt>Факс:</dt>
            <dd><?=uif::isNull($master->fax)?></dd>
            <dt>Мобилен:</dt>
            <dd><?=uif::isNull($master->mobile)?></dd>
            <dt>Е-Меил:</dt>
            <dd><?=uif::isNull($master->email)?></dd>
            <dt>WWW:</dt>
            <dd><?=uif::isNull($master->web)?></dd>
            <dt>Банка:</dt>
            <dd><?=uif::isNull($master->bank)?></dd>
            <dt>Број на Сметка:</dt>
            <dd><?=uif::isNull($master->account_no)?></dd>
            <dt>ДБ:</dt>
            <dd><?=uif::isNull($master->tax_no)?></dd>
	   </dl>
    </div>
    <div class="span7">
        <?php if (isset($subs) AND is_array($subs) AND count($subs)):?>
        <div class="legend">Подружници кои припаѓаат на <?=$master->company;?></div>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Купувач</th>
                    <th>Град</th>
                </tr>
            </thead> 
            <tbody>
                <?php foreach($subs as $row):?>
                <tr>
                    <td><?=uif::viewIcon('partners',$row->id)?></td>
                    <td><?=$row->company?></td>
                    <td><?=$row->name?></td>
                </tr>
                <?php endforeach;?>
            </tbody>  
        </table>
        <?php endif;?>

        <?php if (isset($orders) AND is_array($orders) AND count($orders)):?>
        <div class="legend">Последни 10 Нарачки од <?=$master->company;?></div>
        <table class="table table-condensed"> 
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Испорачано</th>
                    <th>Дистрибутер</th>
                    <th>Плаќање</th>
                    <th>Внес</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody> 
                <?php foreach($orders as $row):?>
                <tr>
                    <td><?=uif::viewIcon('orders',$row->id)?></td>
                    <td><?=uif::date($row->dateshipped)?></td>
                    <td><?=$row->fname . ' ' . $row->lname; ?></td>
                    <td><?=uif::isNull($row->name)?></td>
                    <td><?=uif::date($row->dateofentry)?></td>
                    <td><?=(!$row->locked) ? uif::actionGroup('orders',$row->id) : ''?></td>    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif;?>
    </div>
</div>