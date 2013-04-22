<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("inventory/edit/po/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("inventory/po/delete/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
			<dt>Артикл:</dt>
			<dd><?=$master->prodname;?></dd>
			<dt>Количина:</dt>
			<dd><?=$master->quantity .' '.  $master->uname;?></dd>
			<dt>Добавувач:</dt>
			<dd><?=($master->company) ? $master->company : '-' ;?></dd>
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
		    <dt>Задолжение:</dt>
		    <dd><?=(!is_null($master->assigned_to)) ? $master->assignfname.' '.$master->assignlname:'-';?></dd>
		    <dt>Статус:</dt>
			<dd><?=$master->po_status?></dd>
		    <dt>Нарачано:</dt>
			<dd><?=uif::isNull($master->dateoforder)?></dd>
			<dt>Белешка:</dt>
			<dd><?=uif::isNull($master->comments)?></dd>
			<?php if($this->session->userdata('admin')):?>
		        <dt>Оператор:</dt>
		        <dd><?=$master->fname. ' '.$master->lname;?></dd>
		    <?php endif;?>
		</dl>
	</div>
</div>