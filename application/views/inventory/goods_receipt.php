<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("inventory/edit/gr/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("inventory/delete/gr/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
		    <dt>Добавувач:</dt>
		    <dd><?=uif::isNull($master->company);?></dd>   
		    <dt>Артикл:</dt>
		    <dd><?=$master->prodname;?></dd>
		    <dt>Количина:</dt>
		    <dd><?=$master->quantity .' '.  $master->uname;?></dd> 
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
		    <dd><?=($master->ext_doc) ? $master->ext_doc:'-';?></dd>
			<?php 
				if($master->price) { 
					$net_total = $master->price * $master->quantity;
					$vat = ($net_total * $master->rate)/100;
					$gross_total = $net_total + $vat;
				}
			?>
		    <dt>Цена (без ДДВ):</dt>
		    <dd><?=($master->price == null ? '-' : $master->price.' / '.$master->uname);?></dd>
		    <dt>Вкупно (без ДДВ):</dt>
		    <dd><?=($master->price == null ? '-' : $net_total);?></dd>
		    <dt>ДДВ (<?=$master->rate.'%'; ?>):</dt>
		    <dd><?=(!isset($vat)) ? '-' : $vat; ?></dd>
		    <dt>Вкупно (со ДДВ):</dt>
		    <dd><?=(!isset($gross_total)) ? '-' : $gross_total; ?></dd>    
		    <dt>Нарачано:</dt>
		    <dd><?=uif::date($master->dateoforder)?></dd>
		    <dt>Примено:</dt>
		    <dd><?=uif::date($master->datereceived)?></dd>
		    <dt>Траење:</dt>
		    <dd><?=uif::date($master->dateofexpiration)?></dd>
		    <dt>Белешка:</dt>
		    <dd><?=uif::isNull($master->comments)?></dd>
		    <?php if($this->session->userdata('admin')):?>
		        <dt>Оператор:</dt>
		        <dd><?=$master->fname. ' '.$master->lname;?></dd>
		    <?php endif;?>
		</dl>
	</div>
</div>