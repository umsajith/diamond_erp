<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("boms/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("boms/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt>Назив:</dt>
            <dd><?=$master->name;?></dd>
            <dt>Количина:</dt>
            <dd><?=$master->quantity.' '.$master->uname2;?></dd>
            <dt>Конверзија:</dt>
            <dd><?=$master->quantity.' '.$master->uname2.' = '.$master->quantity * $master->conversion.' '.$master->uname;?></dd>
            <dt>Артикл:</dt>
            <dd><?=uif::isNull($master->prodname)?></dd>
            <dt>Статус:</dt>
            <dd><?=($master->is_active == 1) ? 'Активен' : 'Неактивен' ;?></dd>      
	   </dl>
    </div>
    <div class="span7">
         <?=form_open('boms/addProduct','id="add-product-form"')?>
                <div class="legend">Додавање сировини и репро-материјали на норматив</div>
            <div class="well well-small form-horizontal">
                    <?=uif::formElement('dropdown','Артикл','prodname_fk',[],'id="products" class="input-large"')?>
                <div class="input-append">
                    <?=uif::formElement('text','','quantity','','placeholder="Количина" class="input-medium"')?>
                    <span class="add-on uom"></span>
                    <?=form_hidden('bom_fk',$master->id)?>
                    <?=uif::button('icon-plus-sign','success','onClick="cd.submit(#add-product-form);"')?>
                </div>
            </div>  
        <?=form_close()?>
        <?php if (isset($details) AND is_array($details) AND count($details)):?>
            <div class="legend">Сировини и репро-материјали во овој норматив</div>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Артикл</th>
                <th>Категорија</th>
                <th>Количина</th>
                <th>ЕМ</th>
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
                    <td><?=uif::linkIcon("boms/removeProduct/{$row->id}",'icon-trash')?></td>
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
            url: "<?=site_url('boms/ajxEditQty')?>",
            title: 'Qty'
        });
        var options = {select : "#products",aux1 : "span.uom"};
        cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options); 
    });
</script>