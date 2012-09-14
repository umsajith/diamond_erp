<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
<?php if($master->locked != 1):?>
	
	<div id="buttons">
		<a href="<?php echo site_url('job_orders/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('job_orders/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>	
	</div>
<hr>
<?php endif;?>

	<dl>
        <dt>Работник:</dt>
        <dd><?php echo $master->lname.' '.$master->fname;?></dd>
        <dt>Работна Задача:</dt>
        <dd><?php echo $master->taskname;?></dd>
        <dt>За Датум:</dt>
        <dd><?php echo ($master->datedue == NULL ? '-' : $master->datedue);?></dd>
        <dt>Завршен на:</dt>
        <dd><?php echo ($master->datecompleted == NULL ? '-' : $master->datecompleted);?></dd>
        <dt>Зададена Кол.:</dt>
        <dd><?php echo $master->assigned_quantity . ' ' . $master->uname ;?></dd>
        <dt>Реализирана Кол.:</dt>
        <dd><?php echo ($master->final_quantity == NULL ? '-' : ($master->final_quantity. ' ' . $master->uname));?></dd>
        <dt>Растур Кол.:</dt>
        <dd><?php echo ($master->defect_quantity == NULL ? '-' : ($master->defect_quantity. ' ' . $master->uname));?></dd>
        <dt>Работни Часови:</dt>
        <dd><?php echo ($master->work_hours == NULL ? '-' : $master->work_hours);?></dd>
        <dt>Смена:</dt>
        <dd><?php echo ($master->shift == NULL ? '-' : $master->shift);?></dd>
        <dt>Статус:</dt>
        <dd><?php if($master->job_order_status=='completed')echo 'Завршен';
						elseif ($master->job_order_status=='pending')echo 'Во Тек';
						else echo 'Откажан';?>
		</dd>
        <dt>Опис:</dt>
        <dd><?php echo ($master->description == NULL ? '-' : $master->description);?></dd>  
	</dl>
<?php if($master->payroll_fk != null):?>
	<h4>Ставката е заклучена по калкулација за плата бр. <?php echo anchor('payroll/view/'.$master->payroll_fk,$master->payroll_fk);?></h4>
<?php endif;?>
<?php if (isset($details) && is_array($details) && count($details)):?>
	<hr/>
	<h3>Употребени Сировини</h3>
	<table class="master_table">
    <tr>
    	<th>Производ</th>
    	<th>Категорија</th>
    	<th>Количина</th>
    </tr>
	<?php foreach($details as $row):?>
	<tr>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td><?php echo $row->quantity. ' ' .$row->uname;?></td>
	</tr>
	<?php endforeach;?>
	</table>
<?php endif;?>
<hr/>