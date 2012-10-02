<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	
	<div id="buttons">
		<a href="<?php echo site_url('inventory/edit/gr/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('inventory/delete/gr/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>	
<hr>
<dl>
    <dt>Артикл:</dt>
    <dd><?php echo $master->prodname;?></dd>
    <dt>Количина:</dt>
    <dd><?php echo $master->quantity .' '.  $master->uname;?></dd> 
    <dt>Добавувач:</dt>
    <dd><?php echo($master->company == null ? '-' : $master->company);?></dd>   
    <dt>Плаќање:</dt>
    <dd>
    	<?php 
			switch ($master->purchase_method) 
			{
			    case '0':
			       echo '-';
			        break;
			    case 'cash':
			        echo 'Готовина';
			        break;
			   	case 'invoice':
			        echo 'Фактура';
			        break;
			}
		?>
	</dd>
    <dt>Документ:</dt>
    <dd><?php echo ($master->ext_doc) ? $master->ext_doc:'-';?></dd>
	<?php 
		if($master->price) { 
			$net_total = $master->price * $master->quantity;
			$vat = ($net_total * $master->rate)/100;
			$gross_total = $net_total + $vat;
		}
	?>
    <dt>Цена (без ДДВ):</dt>
    <dd><?php echo ($master->price == null ? '-' : $master->price.$G_currency .'/'.$master->uname);?></dd>
    <dt>Вкупно (без ДДВ):</dt>
    <dd><?php echo ($master->price == null ? '-' : $net_total.$G_currency);?></dd>
    <dt>ДДВ (<?php echo $master->rate.'%'; ?>):</dt>
    <dd><?php echo (!isset($vat)) ? '-' : $vat.$G_currency; ?></dd>
    <dt>Вкупно (со ДДВ):</dt>
    <dd><?php echo (!isset($gross_total)) ? '-' : $gross_total.$G_currency; ?></dd>    
    <dt>Нарачано:</dt>
    <dd><?php echo ($master->dateoforder == null ? '-' : $master->dateoforder);?></dd>
    <dt>Примено:</dt>
    <dd><?php echo ($master->datereceived == null ? '-' : $master->datereceived);?></dd>
    <dt>Рок на Траење:</dt>
    <dd><?php echo ($master->dateofexpiration == null ? '-' : $master->dateofexpiration);?></dd>
    <?php if($this->session->userdata('admin')):?>
        <dt>Оператор:</dt>
        <dd><?php echo $master->lname. ' '.$master->fname;?></dd>
    <?php endif;?>
</dl>