<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("inventory/edit/gr/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("inventory/delete/gr/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
		    <dt><?=uif::lng('attr.vendor')?>:</dt>
		    <dd><?=uif::isNull($master->company);?></dd>   
		    <dt><?=uif::lng('attr.item')?>:</dt>
		    <dd><?=$master->prodname;?></dd>
		    <dt><?=uif::lng('attr.quantity')?>:</dt>
		    <dd><?=$master->quantity .' '.  $master->uname;?></dd> 
		    <dt><?=uif::lng('attr.payment_method')?>:</dt>
		    <dd>
		    	<?php 
					switch ($master->purchase_method) 
					{
					    case 'cash':
					        echo uif::lng('attr.cash');
					        break;
					   	case 'invoice':
					        echo uif::lng('attr.invoice');
					        break;
					    default:
					       	echo '-';
					        break;
					}
				?>
			</dd>
		    <dt><?=uif::lng('attr.document')?>:</dt>
		    <dd><?=($master->ext_doc) ? $master->ext_doc:'-';?></dd>
			<?php 
				if($master->price) { 
					$net_total = $master->price * $master->quantity;
					$vat = ($net_total * $master->rate)/100;
					$gross_total = $net_total + $vat;
				}
			?>
		    <dt><?=uif::lng('attr.price_wo_vat')?>:</dt>
		    <dd><?=($master->price == null ? '-' : $master->price.$glCurrSh.' / '.$master->uname);?></dd>
		    <dt><?=uif::lng('attr.subtotal')?>:</dt>
		    <dd><?=($master->price == null ? '-' : $net_total.$glCurrSh);?></dd>
		    <dt><?=uif::lng('attr.vat')?> (<?=$master->rate.'%'; ?>):</dt>
		    <dd><?=(!isset($vat)) ? '-' : $vat; ?></dd>
		    <dt><?=uif::lng('attr.total_w_vat')?>:</dt>
		    <dd><?=(!isset($gross_total)) ? '-' : $gross_total; ?></dd>    
		    <dt><?=uif::lng('attr.ordered')?>:</dt>
		    <dd><?=uif::date($master->dateoforder)?></dd>
		    <dt><?=uif::lng('attr.received')?>:</dt>
		    <dd><?=uif::date($master->datereceived)?></dd>
		    <dt><?=uif::lng('attr.expires')?>:</dt>
		    <dd><?=uif::date($master->dateofexpiration)?></dd>
		    <dt><?=uif::lng('attr.note')?>:</dt>
		    <dd><?=uif::isNull($master->comments)?></dd>
		    <?php if($this->session->userdata('admin')):?>
		        <dt><?=uif::lng('attr.operator')?>:</dt>
		        <dd><?=$master->fname. ' '.$master->lname;?></dd>
		    <?php endif;?>
		</dl>
	</div>
</div>