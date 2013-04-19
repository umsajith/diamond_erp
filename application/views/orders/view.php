<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders/edit/$master->id",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders/delete/$master->id")?>
	<hr>	
    <?php endif;?>
<div class="row-fluid">
	<div class="span5 well well-small">
		<dl class="dl-horizontal">
	        <dt>Датум</dt>
	        <dd><?=$master->dateshipped; ?></dd>
	        <dt>Купувач:</dt>
	        <dd><?=anchor("partners/view/$master->pid",$master->company);?></dd>
	        <dt>Дистрибутер:</dt>
	        <dd><?=$master->lname . ' ' . $master->fname; ?></dd>
	        <dt>Извештај:</dt>
	        <dd><?=($master->order_list_id) ?
	        	anchor("orders_list/view/$master->order_list_id",'#'.$master->order_list_id) : '-' ; ?></dd>
	       	<dt>Плаќање:</dt>
	        <dd><?=uif::isNull($master->name)?></dd>
	        <dt>Белешка:</dt>
	        <dd><?=uif::isNull($master->comments)?></dd>     
		</dl>
	</div>
	<div class="span7">
		<?php if (isset($details) AND is_array($details) AND count($details) > 0):?>
		<table class="table table-condensed">
			<thead>
		    <tr>
		    	<th>&nbsp;</th>
		    	<th>Производ</th>
		    	<th>Категорија</th>
		    	<th>Земено</th>
		    	<th>Вратено</th>
		    	<th>&nbsp;</th>
		    </tr>
		    </thead>
		    <tbody><?php $i = 1;?>
			<?php foreach($details as $row):?>
				<tr>
					<td><?=$i?></td>
					<td><?=$row->prodname?></td>
					<td><?=$row->pcname?></td>
					<td><?=$row->quantity?></td>
					<td><?=$row->returned_quantity?></td>
					<td><?=$row->uname?></td>
				</tr><?php $i++;?>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
		<?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong>Овој налог за продажба е заклучен по калкулација за плата #
            <?=anchor("payroll/view/$master->payroll_fk",$master->payroll_fk);?></strong>
        </div>
    <?php endif;?>
	</div>
</div>