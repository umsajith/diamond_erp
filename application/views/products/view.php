<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>
	<div id="buttons">
		<a href="<?php echo site_url('products/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('products/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
<hr>
<dl>
    <dt>Назив:</dt>
    <dd><?php echo $master->prodname;?></dd>
    <dt>Код:</dt>
    <dd><?php echo ($master->code!='')?$master->code:'-';?></dd>
    <dt>Тип:</dt>
    <dd><?php echo $master->ptname;?></dd>
	<dt>Категорија:</dt>
    <dd><?php echo $master->pcname;?></dd>
    <dt>Магацин:</dt>
    <dd><?php echo $master->wname;?></dd>
    <dt>Основна Единица:</dt>
    <dd><?php echo $master->base_unit . ' ' . $master->uname;?></dd>
    <dt>МП Цена:</dt>
    <dd><?php echo $master->retail_price;?></dd>
    <dt>ГП Цена 1:</dt>
    <dd><?php echo $master->whole_price1;?></dd>
    <dt>ГП Цена 2:</dt>
    <dd><?php echo $master->whole_price2;?></dd>
    <dt>Данок (%):</dt>
    <dd><?php echo $master->rate.'%';?></dd>
    <dt>Провизија:</dt>
    <dd><?php echo $master->commision;?></dd>
    <dt>Се Продава:</dt>
    <dd><?php echo ($master->salable==1)?'Да':'Не';?></dd>
   	<dt>Се Купува:</dt>
    <dd><?php echo ($master->purchasable==1)?'Да':'Не';?></dd>
    <dt>Состојба:</dt>
    <dd><?php echo ($master->stockable==1)?'Да':'Не';?></dd>
    <dt>Мин Количина:</dt>
    <dd><?php echo $master->alert_quantity. ' ' . $master->uname;?></dd>    
</dl>