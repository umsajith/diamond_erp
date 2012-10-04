<h2><?php echo $heading; ?></h2>
<?php echo form_open_multipart('products/insert'); ?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
		<td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
		<td><?php echo form_input('prodname', set_value('prodname'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Код:');?></td>
		<td><?php echo form_input('code', set_value('code'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Тип на Артикл:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('ptname_fk', $product_types);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Категорија на Артикл:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('pcname_fk', $product_cates);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Магацин:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('wname_fk', $warehouses);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Основна Единица:');?><span class='req'>*</span></td>
		<td><?php echo form_input('base_unit', set_value('base_unit'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('ЕМ:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('uname_fk', $uoms);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Малопродажна Цена:');?></td>
		<td><?php echo form_input('retail_price', set_value('retail_price'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Големопродажна Цена 1:');?></td>
		<td><?php echo form_input('whole_price1', set_value('whole_price1'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Големопродажна Цена 2:');?></td>
		<td><?php echo form_input('whole_price2', set_value('whole_price2'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Данок (%):');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('tax_rate_fk', $tax_rates);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Провизија:');?></td>
		<td><?php echo form_input('commision', set_value('commision'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Мин. Количина:');?></td>
		<td><?php echo form_input('alert_quantity', set_value('alert_quantity'));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Опис:');?></td>
		<td><textarea name="description" rows="5"></textarea></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Се Продава:');?></td>
		<td><?php echo form_checkbox('salable','1',false);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Се Купува:');?></td>
		<td><?php echo form_checkbox('purchasable','1',false);?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Состојба:');?></td>
		<td><?php echo form_checkbox('stockable','1',false);?></td>
	</tr>
</table>
<?php form_close();?>
<?php echo validation_errors(); ?>