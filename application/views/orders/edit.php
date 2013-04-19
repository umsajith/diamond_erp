<?=uif::contentHeader($heading,$master)?>
	<?=form_open("orders/edit/{$master->id}",'class="form-horizontal" id="order-form"')?>
    <?=uif::button('icon-save','primary','onClick="submit_form()"')?>
<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::controlGroup('datepicker','Датум','dateshipped',$master)?>
		<?=uif::controlGroup('dropdown','Купувач','partner_fk',[$customers,$master],'placeholder="Купувач"')?>
		<?=uif::controlGroup('dropdown','Дистрибутер','distributor_fk',[$distributors,$master],'placeholder="Дистрибутер"')?>
		<?=uif::controlGroup('dropdown','Плаќање','payment_mode_fk',[$modes_payment,$master],'placeholder="Плаќање"')?>	
		<?=uif::controlGroup('dropdown','Статус','ostatus',
		[['pending'=>'Примена','completed'=>'Испорачана','rejected'=>'Одбиена'],$master],'placeholder="Статус"')?>	
		<?=uif::controlGroup('textarea','Белешка','comments',$master,'placeholder="Белешка"')?>	
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
	<div class="span6">
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
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
					<td><?=$row->uname;?></td>
				</tr><?php $i++;?>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php else:?>
			<?=uif::load('_no_records')?>
		<?php endif;?>
	</div>
</div>

<script>
	$(function() {
		$("select").select2();
		var options = {future: false};
		cd.datepicker(".datepicker",options);
	});
</script>