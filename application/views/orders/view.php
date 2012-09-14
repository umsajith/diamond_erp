<h2><?php echo $heading; ?></h2>
<hr>
<?php if($master->locked != 1):?>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	
	<div id="buttons">
		<a href="<?php echo site_url('orders/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('orders/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
		<a href="<?php echo site_url('payroll/payroll_pdf/'.$master->id);?>" class="button"><span class="pdf">PDF</span></a>
	</div>
<hr>
<?php endif;?>
<div id="west">
	<dl>
        <dt>Купувач:</dt>
        <dd><?php echo anchor("partners/view/$master->pid",$master->company);?></dd>

        <dt>За Испорака на:</dt>
        <dd><?php echo ($master->desiredshipping == NULL ? '-' : $master->desiredshipping); ?></dd>
        
        <dt>Дистрибутер:</dt>
        <dd><?php echo $master->lname . ' ' . $master->fname; ?></dd>
        
       	<dt>Начина на Плаќање:</dt>
        <dd><?php echo ($master->name == NULL ? '-' : $master->name); ?></dd>
       	 <?php
        			if($master->ostatus=='pending')
        			{
        				echo "<dt>Статус на Нарачката</dt>";
        				echo "<dd>Примена</dd>";
        				
        			}
        			elseif($master->ostatus=='completed')
        			{
        				echo "<dt>Статус на Нарачката</dt>";
        				echo "<dd>Испорачана</dd>";
        				echo "<dt>Испорачана на</dt>";
        				echo "<dd>$master->dateshipped</dd>";
        			}
        			else
        			{
        				echo "<dt>Статус на Нарачката</dt>";
        				echo "<dd>Одбиена</dd>";
        				
        			}
        				
        	?>
        <dt>Белешка:</dt>
        <dd><?php echo ($master->comments == NULL ? '-' : $master->comments); ?></dd>     
	</dl>
	<h3>Нарачани Производи</h3>
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
<?php if($master->payroll_fk != null && $this->session->userdata('admin')):?>
	<h4>Ставката е заклучена по калкулација за плата бр. <?php echo anchor('payroll/view/'.$master->payroll_fk,$master->payroll_fk);?></h4>
<?php endif;?>
</div>
<?php $this->load->view('includes/_del_dialog');?>