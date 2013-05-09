<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("boms/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("boms/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.name')?>:</dt>
            <dd><?=$master->name;?></dd>
            <dt><?=uif::lng('attr.quantity')?>:</dt>
            <dd><?=$master->quantity.' '.$master->uname2;?></dd>
            <dt><?=uif::lng('attr.conversion')?>:</dt>
            <dd><?=$master->quantity.' '.$master->uname2.' = '.$master->quantity * $master->conversion.' '.$master->uname;?></dd>
            <dt><?=uif::lng('attr.item')?>:</dt>
            <dd><?=uif::isNull($master->prodname)?></dd>
            <dt><?=uif::lng('attr.status')?>:</dt>
            <dd><?=($master->is_active == 1) ? uif::lng('attr.status_active') : uif::lng('attr.status_inactive') ;?></dd>      
	   </dl>
    </div>
    <div class="span7">
         <?=form_open('boms/addProduct','id="add-product-form"')?>
                <div class="legend"><?=uif::lng('app.add_item_to_bom')?></div>
            <div class="well well-small form-horizontal">
                    <?=uif::formElement('dropdown','','prodname_fk',[],'id="products" class="input-large"')?>
                <div class="input-append">
                    <?=uif::formElement('text','','quantity','','placeholder="'.uif::lng('attr.quantity').'" class="input-medium"')?>
                    <span class="add-on uom"></span>
                    <?=form_hidden('bom_fk',$master->id)?>
                    <?=uif::button('icon-plus-sign','success','onClick="cd.submit(#add-product-form);"')?>
                </div>
            </div>  
        <?=form_close()?>
        <?php if (isset($details) AND is_array($details) AND count($details)):?>
            <div class="legend"><?=uif::lng('app.items_in_bom')?></div>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th><?=uif::lng('attr.item')?></th>
                <th><?=uif::lng('attr.category')?></th>
                <th><?=uif::lng('attr.quantity')?></th>
                <th><?=uif::lng('attr.uom')?></th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($details as $row):?>
                <tr data-pid=<?=$row->prodname_fk?>>
                    <td><?=$row->prodname?></td>
                    <td><?=$row->pcname?></td>
                    <td>
                        <a href="#" class="editable" data-original-title="Количина" data-name="quantity"
                        data-pk="<?=$row->id?>"><?=$row->quantity?></a>
                    </td>
                    <td><?=$row->uname?></td>
                    <td><?=uif::linkButton("boms/removeProduct/{$row->id}",'icon-trash','danger btn-mini')?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?php endif;?>
    </div>
</div>
<script>
    $(function(){
        $('.editable').editable({
            type: 'text',
            url: "<?=site_url('boms/ajxEditQty')?>"
        });
        var options = {select : "#products",aux1 : "span.uom", placeholder:"<?=uif::lng('attr.item')?>"};
        cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options); 
    });
</script>