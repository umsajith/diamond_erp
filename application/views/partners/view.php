<h2><?php echo $heading; ?></h2>
<hr>
    <div id="meta">
        <p>бр.<?php echo $master->id;?></p>
        <p><?php echo $master->dateofentry;?></p>
    </div>
    <div id="buttons">
        <a href="<?php echo site_url('partners/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
        <a href="<?php echo site_url('partners/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
    </div>
<hr>
<!-- LEFT SIDE  -->
<div class="f_left">
	<dl>
        <dt>Назив:</dt>
        <dd><?php echo $master->company;?></dd>
        <dt>Код:</dt>
        <dd><?php echo ($master->id == NULL ? '-' : $master->id);?></dd>
        <dt>Контакт Лице:</dt>
        <dd><?php echo ($master->contperson == NULL ? '-' : $master->contperson);?></dd>
        <dt>Тип на Партнер:</dt>
        <dd>
       		<?php  
                if($master->is_customer==1 && $master->is_vendor==0) echo 'Купувач';
                if($master->is_vendor==1 && $master->is_customer==0) echo 'Добавувач';
                if($master->is_vendor==1 && $master->is_customer==1) echo 'Купувач/Добавувач';
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
	</dl>
</div>

<!-- RIGHT SIDE  -->
<div class="f_right">
<?php if($master->is_mother==1):?>
<h3>Подружници кои припаѓаат на <?php echo $master->company;?></h3>
<table class="master_table">   
<?php if (isset($subs) AND is_array($subs) AND count($subs) > 0):?>
    <tr>
        <th>&nbsp;</th>
        <th>Купувач</th>
        <th>Град</th>
        <th>Телефон</th>
        <th>&nbsp;</th>
    </tr>
    <?php foreach($subs as $row):?>
    <tr>
        <td class="code" align="center"><?php echo anchor('partners/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
        <td><?php echo $row->company;?></td>
        <td><?php echo $row->name;?></td>
        <td><?php echo ($row->phone1)?$row->phone1:'-';?></td>
        <td class="functions">
            <?php echo anchor('partners/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
            <?php echo anchor('partners/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
        </td>
    </tr>
    <?php endforeach;?>
<?php else:?>
    <?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php endif; ?>

<?php if($master->is_customer==1):?>
<h3>Последни 10 Нарачки од <?php echo $master->company;?></h3>
<table class="master_table">   
<?php if (isset($orders) AND is_array($orders) AND count($orders) > 0):?>
    <tr>
        <th>&nbsp;</th>
        <th>Испорачано</th>
        <th>Купувач</th>
        <th>Дистрибутер</th>
        <th>Плаќање</th>
        <th>Внес</th>
        <th>&nbsp;</th>
    </tr>
    <?php foreach($orders as $row):?>
    <tr>
        <td class="code" align="center"><?php echo anchor('orders/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
        <td><?php echo (($row->dateshipped == NULL) || ($row->dateshipped == '0000-00-00') ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateshipped))); ?></td>
        <td><?php echo $row->company;?></td>
        <td><?php echo $row->fname . ' ' . $row->lname; ?></td>
        <td><?php echo ($row->name == NULL ? '-' : $row->name); ?></td>
        <td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
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
</div>