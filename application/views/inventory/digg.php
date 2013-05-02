<?=uif::contentHeader($heading.': '.$product->prodname)?>
<?php if (isset($results) AND is_array($results) AND count($results)):?>
<table class="table table-stripped table-hover data-grid"> 
	<thead>
		<tr>
			<th colspan="2">&nbsp;</th>
			<th>Линк</th>
			<th>Старо Салдо</th>
			<th>Влез</th>
			<th>Излез</th>
			<th>Внес</th>
			<?php if($this->session->userdata('admin')):?>
				<th>Oператор</th>
			<?php endif;?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($results as $row):?>
		<tr>
			<td><?=(!$row->is_use) ?
				uif::staticIcon('icon-circle-arrow-down') :
				uif::staticIcon('icon-circle-arrow-up'); ?>
			</td>
			<td><?=($row->type == 'adj' ? uif::staticIcon('icon-refresh'):'')?></td>
			<td>
			<?php 	
				if(is_null($row->job_order_fk) AND is_null($row->warehouse_fk) )
					echo uif::linkIcon("inventory/view/$row->type/$row->id",'icon-link');
				elseif($row->warehouse_fk)
					echo uif::linkIcon("distribution/view/in/$row->warehouse_fk",'icon-link');
				else
					echo uif::linkIcon("job_orders/view/$row->job_order_fk",'icon-link');
			?>
			</td>	
			<td><?=$row->qty_current.' '.$row->uname?></td>
			<td><?=($row->quantity > 0) ? $row->quantity.' '.$row->uname:'-'?></td>
			<td><?=($row->quantity < 0) ? $row->quantity.' '.$row->uname:'-'?></td>
			<td><?=uif::date($row->dateofentry)?></td>
			<?php if($this->session->userdata('admin')):?>
				<td><?=$row->fname. ' ' . $row->lname?></td>
			<?php endif;?>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
	<?=uif::load('_no_records')?>
<?php endif;?>