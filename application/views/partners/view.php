<h2><?php echo $heading; ?></h2>
<hr>
<div id="buttons">
<a href="<?php echo site_url('partners/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
<a href="<?php echo site_url('partners/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
</div>
<hr>
	<dl>
        <dt>Назив:</dt>
        <dd><?php echo $master->company;?></dd>
        <dt>Код:</dt>
        <dd><?php echo ($master->code == NULL ? '-' : $master->code);?></dd>
        <dt>Контакт Лице:</dt>
        <dd><?php echo ($master->contperson == NULL ? '-' : $master->contperson);?></dd>
        <dt>Тип на Партнер:</dt>
        <dd>
       		<?php 	
	       		if($master->is_customer==1 && $master->is_vendor==0) echo 'Customer';
				if($master->is_vendor==1 && $master->is_customer==0) echo 'Vendor';
				if($master->is_vendor==1 && $master->is_customer==1) echo 'Customer/Vendor';
			?>
		</dd>

        <dt>Адреса:</dt>
        <dd><?php echo ($master->address == NULL ? '-' : $master->address);?></dd>
        <dt>Град:</dt>
        <dd><?php echo $master->name;?></dd>
        <dt>Поштенски Код:</dt>
        <dd><?php echo $master->postalcode;?></dd>
        <dt>Телефон:</dt>
        <dd><?php echo ($master->phone1 == NULL ? '-' : $master->phone1);?></dd>
        <dt>Телефон 2:</dt>
        <dd><?php echo ($master->phone2 == NULL ? '-' : $master->phone2);?></dd>
        <dt>Факс:</dt>
        <dd><?php echo ($master->fax == NULL ? '-' : $master->fax);?></dd>
        <dt>Мобилен:</dt>
        <dd><?php echo ($master->mobile == NULL ? '-' : $master->mobile);?></dd>
        <dt>Е-меил:</dt>
        <dd><?php echo ($master->email == NULL ? '-' : $master->email);?></dd>
        <dt>Веб страна:</dt>
        <dd><?php echo ($master->web == NULL ? '-' : $master->web);?></dd>

        <dt>Банка:</dt>
        <dd><?php echo ($master->bank == NULL ? '-' : $master->bank);?></dd>
        <dt>Број на Сметка:</dt>
        <dd><?php echo ($master->account_no == NULL ? '-' : $master->account_no);?></dd>
        <dt>Даночен Број:</dt>
        <dd><?php echo ($master->tax_no == NULL ? '-' : $master->tax_no);?></dd>
 
        <dt>Корисничка Група:</dt>
        <dd><?php echo ($master->ugroup == NULL ? '-' : $master->ugroup);?></dd>
        <dt>Корисничко Име:</dt>
        <dd><?php echo ($master->username == NULL ? '-' : $master->username);?></dd>
        <dt>Статус:</dt>
        <dd><?php echo $master->status;?></dd>
        <dt>Датум на Внес:</dt>
        <dd><?php echo $master->dateofentry;?></dd>   
	</dl>
<?php if($master->is_customer==1):?>
<h3>Последни 10 Нарачки од <?php echo $master->company;?></h3>
<hr>
<table class="master_table">   
<?php if (isset($orders) && is_array($orders) && count($orders) > 0):?>
	<tr>
    	<th>&nbsp;</th>
    	<th>&nbsp;</th>
    	<th>Испорачано</th>
    	<th>Купувач</th>
    	<th>Дистрибутер</th>
    	<th>Начин на Плаќање</th>
    	<th>Датум на Внес</th>
    	<th>Статус на Нарачка</th>
    	<th>&nbsp;</th>
    </tr>
	<?php foreach($orders as $row):?>
	<tr>
			<td class="code" align="center"><?php echo anchor('orders/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td class="lock_td" align="center"><?php echo ($row->locked == 0 ? '' : anchor('#','&nbsp;','class="lock_icon" id="lock_icon"'));?></td>
			<td><?php echo (($row->dateshipped == NULL) || ($row->dateshipped == '0000-00-00') ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateshipped))); ?></td>
			<td><?php echo $row->company;?></td>
			<td><?php echo $row->fname . ' ' . $row->lname; ?></td>
			<td><?php echo ($row->name == NULL ? '-' : $row->name); ?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
			<td>
					<?php 
	        			if($row->ostatus=='pending')
	        				echo 'Примена';
	        			elseif($row->ostatus=='completed')
	        				echo 'Испорачана';
	        			else
	        				echo 'Одбиена';
	        		?>
	        </td>
			<td class="functions">
			<?php if($row->locked != 1):?>
				<?php echo anchor('orders/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('orders/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			<?php endif;?>
			</td>
	</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php endif;?>
<?php $this->load->view('includes/_del_dialog');?>