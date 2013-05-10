<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("partners/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("partners/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.company')?>:</dt>
            <dd><?=$master->company;?></dd>
            <dt><?=uif::lng('attr.contact_person')?>:</dt>
            <dd><?=uif::isNull($master->contperson)?></dd>
            <dt><?=uif::lng('attr.hq')?>:</dt>
            <dd><?=($master->is_mother) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt><?=uif::lng('attr.code')?>:</dt>
            <dd><?=uif::isNull($master->id)?></dd>
            <dt><?=uif::lng('attr.address')?>:</dt>
            <dd><?=uif::isNull($master->address)?></dd>
            <dt><?=uif::lng('attr.city')?>:</dt>
            <dd><?=$master->name;?></dd>
            <dt><?=uif::lng('attr.postal_code')?>:</dt>
            <dd><?=$master->postalcode;?></dd>
            <dt><?=uif::lng('attr.phone')?>:</dt>
            <dd><?=uif::isNull($master->phone1)?></dd>
            <dt><?=uif::lng('attr.phone')?>:</dt>
            <dd><?=uif::isNull($master->phone2)?></dd>
            <dt><?=uif::lng('attr.fax')?>:</dt>
            <dd><?=uif::isNull($master->fax)?></dd>
            <dt><?=uif::lng('attr.mobile')?>:</dt>
            <dd><?=uif::isNull($master->mobile)?></dd>
            <dt><?=uif::lng('attr.email')?>:</dt>
            <dd><?=uif::isNull($master->email)?></dd>
            <dt><?=uif::lng('attr.web')?>:</dt>
            <dd><?=uif::isNull($master->web)?></dd>
            <dt><?=uif::lng('attr.bank')?>:</dt>
            <dd><?=uif::isNull($master->bank)?></dd>
            <dt><?=uif::lng('attr.account_number')?>:</dt>
            <dd><?=uif::isNull($master->account_no)?></dd>
            <dt><?=uif::lng('attr.tax_number')?>:</dt>
            <dd><?=uif::isNull($master->tax_no)?></dd>
	   </dl>
    </div>
    <div class="span7">
        <?php if (isset($subs) AND is_array($subs) AND count($subs)):?>
        <div class="legend"><?=uif::lng('app.companies_belong_to_partner')?> <?=$master->company;?></div>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?=uif::lng('attr.name')?></th>
                    <th><?=uif::lng('attr.city')?></th>
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
        <div class="legend"><?=uif::lng('app.last_order_by_partner')?> <?=$master->company;?></div>
        <table class="table table-condensed"> 
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?=uif::lng('attr.date')?></th>
                    <th><?=uif::lng('attr.distributor')?></th>
                    <th><?=uif::lng('attr.payment_method')?></th>
                    <th><?=uif::lng('attr.doe')?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody> 
                <?php foreach($orders as $row):?>
                <tr>
                    <td><?=uif::viewIcon('orders',$row->id)?></td>
                    <td><?=uif::date($row->dateshipped)?></td>
                    <td><?=$row->fname . ' ' . $row->lname?></td>
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