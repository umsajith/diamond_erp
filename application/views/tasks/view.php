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
        <dd><?php echo $master->base_unit .' '.$master->uname;?></dd>
        <dt>Основна Цена:</dt>
        <dd><?php echo $master->rate_per_unit;?></dd>
        <dt>Бонус Цена:</dt>
        <dd><?php echo ($master->rate_per_unit_bonus == NULL ? '-' : $master->rate_per_unit_bonus);?></dd>
        <dt>Опис:</dt>
        <dd><?php echo ($master->description == NULL ? '-' : $master->description);?></dd>   
	</dl>
<hr>
<?php $this->load->view('includes/_del_dialog');?>