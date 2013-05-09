<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("inventory/edit/po/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("inventory/delete/po/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
			<dt><?=uif::lng('attr.item')?>:</dt>
			<dd><?=$master->prodname?></dd>
			<dt><?=uif::lng('attr.quantity')?>:</dt>
			<dd><?=$master->quantity .' '.  $master->uname;?></dd>
			<dt><?=uif::lng('attr.vendor')?>:</dt>
			<dd><?=($master->company) ? $master->company : '-' ?></dd>
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
		    <dt><?=uif::lng('attr.duty')?>:</dt>
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
		    <dt><?=uif::lng('attr.status')?>:</dt>
			<dd><?=$status?></dd>
		    <dt><?=uif::lng('attr.ordered')?>:</dt>
			<dd><?=uif::isNull($master->dateoforder)?></dd>
			<dt><?=uif::lng('attr.note')?>:</dt>
			<dd><?=uif::isNull($master->comments)?></dd>
			<?php if($this->session->userdata('admin')):?>
		        <dt><?=uif::lng('attr.operator')?>:</dt>
		        <dd><?=$master->fname. ' '.$master->lname?></dd>
		    <?php endif;?>
		</dl>
	</div>
</div>