<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("positions/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("positions/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
			<dt>Назив:</dt>
			<dd><?=$master->position;?></dd>
			<dt>Сектор:</dt>
			<dd><?=$master->department;?></dd>
			<dt>Основна Плата:</dt>
			<dd><?=uif::isNull($master->base_salary)?></dd>
			<dt>Бонус:</dt>
			<dd><?=uif::isNull($master->bonus,' %')?></dd>
			<dt>Провизија:</dt>
			<dd><?=uif::isNull($master->commision,' %')?></dd>
			<dt>Квалификации:</dt>
			<dd><?=uif::isNull($master->requirements)?></dd>
			<dt>Белешка:</dt>
			<dd><?=uif::isNull($master->description)?></dd>   
		</dl>
	</div>
</div>