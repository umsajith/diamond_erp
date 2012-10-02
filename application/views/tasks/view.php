<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	<div id="buttons">
		<a href="<?php echo site_url('tasks/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('tasks/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
<hr>
<dl>
    <dt>Назив:</dt>
    <dd><?php echo $master->taskname;?></dd>
    <dt>Основна ЕМ</dt>
    <dd><?php echo $master->base_unit.' '.$master->uname;?></dd>
    <dt>Основна Цена:</dt>
    <dd><?php echo $master->rate_per_unit.$G_currency.'/'.$master->uname;?></dd>
    <dt>Бонус Цена:</dt>
    <dd><?php echo ($master->rate_per_unit_bonus == null ? '-' 
    			: $master->rate_per_unit_bonus.$G_currency.'/'.$master->uname);?></dd>
    <dt>Норматив:</dt>
    <dd><?php echo ($master->is_production) ? $master->name : '-' ;?></dd>
    <dt>Опис:</dt>
    <dd><?php echo ($master->description == null ? '-' : $master->description);?></dd>   
</dl>