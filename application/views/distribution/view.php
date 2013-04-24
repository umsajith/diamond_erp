<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("distribution/edit/{$page}/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("distribution/delete/{$page}/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
	        <dt>Датум:</dt>
	        <dd><?=uif::date($master->dateoforigin)?></dd>         
	        <dt>Артикл:</dt>
	        <dd><?=$master->prodname?></dd>
	        <dt>Количина:</dt>
	        <dd><?=$master->quantity .' '.  $master->uname?></dd>
	        <?php if(in_array($page,['out','ret'])): ?> 
	        	<dt>Дистрибутер:</dt>
	        	<dd><?=(!is_null($master->distributor_fk)) ? $master->fname. ' '.$master->lname : '-';?></dd>
	        <?php endif; ?> 
	        <dt>Документ:</dt>
	        <dd><?=uif::isNull($master->ext_doc)?></dd> 
	        <dt>Забелешка:</dt>
	        <dd><?=($master->note) ? $master->note : '-'?></dd>
	        <dt>Оператор:</dt>
	        <dd><?=$master->assignfname .' '.  $master->assignlname?></dd>
		</dl>
	</div>
	<div class="span7">
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
		<table class="table table-condensed table-bordered">
        <div class="legend">Употребени Репро-Материјали</div>
	        <thead>
			    <tr>
			    	<th>Aртикл</th>
			    	<th>Категорија</th>
			    	<th>Количина</th>
			    </tr>
		    </thead>
		    <tbody>
			<?php foreach($details as $row):?>
				<tr>
					<td><?=$row->prodname?></td>
					<td><?=$row->pcname?></td>
					<td><?=$row->quantity.' '.$row->uname?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
	</div>
</div>