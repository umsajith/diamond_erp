<h2><?php echo $heading; ?></h2>
<hr/>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	<?php if($master->locked != 1):?>
		<div id="buttons">
			<a href="<?php echo site_url('orders/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
			<a href="<?php echo site_url('orders/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
		</div>
	<hr/>
	<?php endif;?>
	<?php if($master->locked == 1):?>
		<?php if ($master->payroll_fk != null): ?>
			<h4>Ставката е заклучена по калкулација за плата бр. <?php echo anchor('payroll/view/'.$master->payroll_fk,$master->payroll_fk);?></h4>
		<?php endif ?>
		<?php if ($master->payroll_fk == null): ?>
			<h4>Ставката е заклучена од страна на администратор</h4>
		<?php endif ?>
	<?php endif;?>

<div class="f_left">
	<dl>
        <dt>Купувач:</dt>
        <dd><?php echo anchor("partners/view/$master->pid",$master->company);?></dd>
        
        <dt>Дистрибутер:</dt>
        <dd><?php echo $master->lname . ' ' . $master->fname; ?></dd>

        <dt>Извештај:</dt>
        <dd><?php echo ($master->order_list_id) ? 
        	anchor("orders_list/view/$master->order_list_id",'Линк') : '-' ; ?></dd>
        
       	<dt>Плаќање:</dt>
        <dd><?php echo ($master->name == NULL ? '-' : $master->name); ?></dd>

        <dt>Испорачана на</dt>
        <dd><?php echo $master->dateshipped; ?></dd>

        <dt>Белешка:</dt>
        <dd><?php echo ($master->comments == NULL ? '-' : $master->comments); ?></dd>     
	</dl>
</div>

<div class="f_right">
	<h3>Производи во овој Налог за Продажба</h3>
<table id="order_grid">
    <tr>
    	<th>&nbsp;</th>
    	<th>Производ</th>
    	<th>Категорија</th>
    	<th>Количина</th>
    	<th>Вратена Кол.</th>
    </tr>
<?php if (isset($details) && is_array($details) && count($details) > 0):?>
	<?php $i = 1;?>
	<?php foreach($details as $row):?>
	<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td><?php echo $row->quantity. ' ' .$row->uname;?></td>
			<td><?php echo ($row->returned_quantity == NULL ? '-' : $row->returned_quantity. ' ' .$row->uname); ?></td>
	</tr>
	<?php $i++;?>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
</div>