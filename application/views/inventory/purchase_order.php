<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("inventory/edit/po/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("inventory/delete/po/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
			<dt>Артикл:</dt>
			<dd><?=$master->prodname?></dd>
			<dt>Количина:</dt>
			<dd><?=$master->quantity .' '.  $master->uname;?></dd>
			<dt>Добавувач:</dt>
			<dd><?=($master->company) ? $master->company : '-' ?></dd>
			<dt>Начин:</dt>
		    <dd>
		    	<?php 
					switch ($master->purchase_method) 
					{
					    case 'cash':
					        echo 'Готовина';
					        break;
					   	case 'invoice':
					        echo 'Фактура';
					        break;
					    default:
					       echo '-';
					        break;
					}
				?>
			</dd>
		    <dt>Задолжение:</dt>
		    <dd><?=(!is_null($master->assigned_to)) ? $master->assignfname.' '.$master->assignlname:'-'?></dd>
		    <?php 
				switch ($master->po_status) 
				{
				    case 'approved': 
				    	$status = uif::staticIcon('icon-ok');
				    	break;
				    case 'redjected': 
				    	$status = uif::staticIcon('icon-remove');
				    	break;
				   	default:
				   		$status = uif::staticIcon('icon-time');
				   		break;
				}
			?>
		    <dt>Статус:</dt>
			<dd><?=$status?></dd>
		    <dt>Нарачано:</dt>
			<dd><?=uif::isNull($master->dateoforder)?></dd>
			<dt>Белешка:</dt>
			<dd><?=uif::isNull($master->comments)?></dd>
			<?php if($this->session->userdata('admin')):?>
		        <dt>Оператор:</dt>
		        <dd><?=$master->fname. ' '.$master->lname?></dd>
		    <?php endif;?>
		</dl>
	</div>
</div>