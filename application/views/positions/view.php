<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	<div id="buttons">
		<a href="<?php echo site_url('positions/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('positions/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
<hr>
<dl>
	<dt>Работно Место:</dt>
	<dd><?php echo $master->position;?></dd>
	<dt>Сектор:</dt>
	<dd><?php echo $master->department;?></dd>

	<dt>Основна Плата:</dt>
	<dd><?php echo ($master->base_salary == 0 ? '-' : $master->base_salary);?></dd>

	<dt>Бонус:</dt>
	<dd><?php echo ($master->bonus == 0 ? '-' : $master->bonus . '%'); ?></dd>

	<dt>Провизија:</dt>
	<dd><?php echo ($master->commision == 0 ? '-' : $master->commision. '%'); ?></dd>

	<dt>Квалификации:</dt>
	<dd><?php echo ($master->requirements == NULL ? '-' : $master->requirements); ?></dd>

	<dt>Статус:</dt>
	<dd><?php echo $master->status;?></dd>

	<dt>Опис:</dt>
	<dd><?php echo ($master->description == NULL ? '-' : $master->description);?></dd>   
</dl>