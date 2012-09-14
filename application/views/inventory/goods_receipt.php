<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>	
	
	<div id="buttons">
		<a href="<?php echo site_url('inventory/edit/gre/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('inventory/delete/gre/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>	
<hr>
	<dl>   
        <dt>Артикл:</dt>
        <dd><?php echo $master->prodname;?></dd>
        <dt>Количина:</dt>
        <dd><?php echo $master->quantity .' '.  $master->uname;?></dd> 
        <dt>Добавувач:</dt>
        <dd><?php echo($master->company == null ? '-' : $master->company);?></dd>   
        <dt>Начин:</dt>
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
        <dt>Цена (без ДДВ):</dt>
        <dd><?php echo ($master->price == null ? '-' : $master->price);?></dd>  
        <dt>Нарачано:</dt>
        <dd><?php echo ($master->dateoforder == null ? '-' : $master->dateoforder);?></dd>
        <dt>Рок на Траење:</dt>
        <dd><?php echo ($master->dateofexpiration == null ? '-' : $master->dateofexpiration);?></dd>
        <dt>Примено:</dt>
        <dd><?php echo ($master->datereceived == null ? '-' : $master->datereceived);?></dd>
        <dt>Документ:</dt>
        <dd><?php echo ($master->ext_doc) ? $master->ext_doc:'-';?></dd>
        <?php if($this->session->userdata('admin')):?>
	        <dt>Оператор:</dt>
	        <dd><?php echo $master->lname. ' '.$master->fname;?></dd>
        <?php endif;?>
	</dl>
<hr>
<?php $this->load->view('includes/_del_dialog');?>