<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
		</div>
	<div id="buttons">
		<a href="<?php echo site_url('inventory/edit/po/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('inventory/delete/po/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>	
<hr>
<dl>
	<dt>Нарачано:</dt>
    <dd><?php echo ($master->dateoforder == null ? '-' : $master->dateoforder);?></dd>
    <dt>Артикл:</dt>
    <dd><?php echo $master->prodname;?></dd>
    <dt>Количина:</dt>
    <dd><?php echo $master->quantity .' '.  $master->uname;?></dd>
    <dt>Добавувач:</dt>
    <dd><?php echo ($master->company) ? $master->company : '-' ;?></dd>
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
    <dt>Задолжение:</dt>
    <dd><?php echo ($master->assigned_to == null) ? '-' : $master->assignfname.' '.$master->assignlname;?></dd>
	<?php if($this->session->userdata('admin')):?>
        <dt>Оператор:</dt>
        <dd><?php echo $master->lname. ' '.$master->fname;?></dd>
    <?php endif;?>
</dl>