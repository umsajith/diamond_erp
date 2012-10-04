<h2><?php echo $heading; ?></h2>
<?php echo form_open('products/edit/'. $product->id); ?>
<hr>
	<div id="meta">
		<p>бр.<?php echo $product->id;?></p>
		<p><?php echo $product->dateofentry;?></p>
	</div>	
<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
		<td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
		<td><?php echo form_input('prodname', set_value('prodname', $product-> prodname ));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Код:');?></td>
		<td><?php echo form_input('code', set_value('code', $product->code));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Тип:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('ptname_fk', $product_types, set_value('ptname_fk', $product->ptname_fk));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Категорија:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('pcname_fk', $product_cates, set_value('pcname_fk', $product->pcname_fk));?></td>
	</tr>

	<tr>
		<td class="label"><?php echo form_label('Магацин:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('wname_fk', $warehouses, set_value('wname_fk', $product->wname_fk));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Основна Единица:');?><span class='req'>*</span></td>
		<td><?php echo form_input('base_unit', set_value('base_unit', $product->base_unit));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('ЕМ:');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('uname_fk', $uoms, set_value('uname_fk', $product->uname_fk));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('МП Цена:');?></td>
		<td><?php echo form_input('retail_price', set_value('retail_price',$product->retail_price));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('ГП Цена 1:');?></td>
		<td><?php echo form_input('whole_price1', set_value('whole_price1',$product->whole_price1));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('ГП Цена 2:');?></td>
		<td><?php echo form_input('whole_price2', set_value('whole_price2',$product->whole_price2));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Данок (%):');?><span class='req'>*</span></td>
		<td><?php echo form_dropdown('tax_rate_fk', $tax_rates, set_value('tax_rate_fk', $product->tax_rate_fk));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Провизија:');?></td>
		<td><?php echo form_input('commision', set_value('commision', $product->commision));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Мин. Количина:');?></td>
		<td><?php echo form_input('alert_quantity', set_value('alert_quantity', $product->alert_quantity));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Опис:');?></td>
		<td><textarea name="description" rows="5"><?php echo $product->description;?></textarea></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Се Продава:');?></td>
		<td><?php echo form_checkbox('salable','1',($product->salable=='1'?TRUE:FALSE));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Се Купува:');?></td>
		<td><?php echo form_checkbox('purchasable','1',($product->purchasable=='1'?TRUE:FALSE));?></td>
	</tr>
	<tr>
		<td class="label"><?php echo form_label('Состојба:');?></td>
		<td><?php echo form_checkbox('stockable','1',($product->stockable=='1'?TRUE:FALSE));?></td>
	</tr>
</table>
<?php form_close();?>
<?php echo validation_errors(); ?>