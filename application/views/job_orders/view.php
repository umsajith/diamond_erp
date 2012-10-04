<h2><?php echo $heading; ?></h2>
<hr/>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
    <?php if($master->locked != 1):?>
    	<div id="buttons">
    		<a href="<?php echo site_url('job_orders/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
    		<a href="<?php echo site_url('job_orders/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>	
    	</div>
    <hr/>
    <?php endif;?>
    <?php if($master->payroll_fk != null):?>
        <h4>Ставката е заклучена по калкулација за плата бр. <?php echo anchor('payroll/view/'.$master->payroll_fk,$master->payroll_fk);?></h4>
    <?php endif;?>
    
<div class="f_left">
	<dl>
        <dt>Датум:</dt>
        <dd><?php echo ($master->datedue == NULL ? '-' : $master->datedue);?></dd>
        <dt>Работник:</dt>
        <dd><?php echo $master->fname.' '.$master->lname;?></dd>
        <dt>Работна Задача:</dt>
        <dd><?php echo $master->taskname;?></dd>
        <?php if ($master->calculation_rate): ?>
            <dt>Основна Цена:</dt>
            <dd><?php echo $master->calculation_rate.$G_currency.'/'.$master->uname;?></dd>
        <?php endif; ?>
        <dt>Количина:</dt>
        <dd><?php echo $master->assigned_quantity . ' ' . $master->uname ;?></dd>
        <dt>Растур:</dt>
        <dd><?php echo ($master->defect_quantity == NULL ? '-' : ($master->defect_quantity. ' ' . $master->uname));?></dd>
        <dt>Работни Часови:</dt>
        <dd><?php echo ($master->work_hours == NULL ? '-' : $master->work_hours);?></dd>
        <dt>Смена:</dt>
        <dd><?php echo ($master->shift == NULL ? '-' : $master->shift);?></dd>
        <dt>Забелешка:</dt>
        <dd><?php echo ($master->description == NULL ? '-' : $master->description);?></dd>
        <?php if($this->session->userdata('admin')):?>
            <dt>Оператор:</dt>
            <dd><?php echo $master->operator;?></dd>  
        <?php endif;?>
	</dl>
</div>
<?php if (isset($details) AND is_array($details) AND count($details)):?>
<div class="f_right">
	<h3>Употребени Сировини</h3>
	<table class="master_table">
    <tr>
    	<th>Артикл</th>
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
</div>
<?php endif;?>
