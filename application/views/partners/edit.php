<h2><?php echo $heading; ?></h2>
<?php echo form_open('partners/edit/'.$partner->id);?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<div id="west">
<fieldset class="data_form">
	<legend>Oсновни Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Фирма: ');?><span class='req'>*</span></td>
		    <td><?php echo form_input('company', set_value('company', $partner->company));?></td>
		    <td class="label"><?php echo form_label('Контакт Лице: ');?></td>
		    <td><?php echo form_input('contperson', set_value('contperson', $partner->contperson));?></td>  
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Купувач: ');?></td>
		    <td><?php echo form_checkbox('is_customer',1,($partner->is_customer==1)?true:false);?></td>
		    <td class="label"><?php echo form_label('Добавувач: ');?></td>
		    <td><?php echo form_checkbox('is_vendor',1,($partner->is_vendor==1)?true:false);?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Главно Седиште: ');?></td>
		    <td><?php echo form_checkbox('is_mother',1,($partner->is_mother==1)?true:false);?></td>
		    <td class="label"><?php echo form_label('Припаѓа на: ');?></td>
	    	<td><?php echo form_dropdown('mother_fk',$customers, set_value('mother_fk', $partner->mother_fk));?></td>
		</tr>
		<tr>
			<td class="label"><?php echo form_label('Код: ');?></td>
		    <td><?php echo form_input('code', set_value('code', $partner->code));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Контакт Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Адреса: ');?></td>
		    <td><?php echo form_input('address', set_value('address', $partner->address));?></td>
		   	<td class="label"><?php echo form_label('Град: ');?></td>
		    <td><?php echo form_dropdown('postalcode_fk',$postalcodes, set_value('postalcode_fk', $partner->postalcode_fk));?></td>
		</tr>
		<tr>
			<td class="label"><?php echo form_label('Телефон 1: ');?></td>
		    <td><?php echo form_input('phone1', set_value('phone1', $partner->phone1));?></td>
		    <td class="label"><?php echo form_label('Телефон 2: ');?></td>
		    <td><?php echo form_input('phone2', set_value('phone2', $partner->phone2));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Факс: ');?></td>
		    <td><?php echo form_input('fax', set_value('fax', $partner->fax));?></td>
		    <td class="label"><?php echo form_label('Мобилен: ');?></td>
		    <td><?php echo form_input('mobile', set_value('mobile', $partner->mobile));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('И-Меил: ');?></td>
		    <td><?php echo form_input('email', set_value('email', $partner->email));?></td>
		    <td class="label"><?php echo form_label('Веб Сајт: ');?></td>
		    <td><?php echo form_input('web', set_value('web', $partner->web));?></td>
		</tr>
		</table>
</fieldset>
<fieldset class="data_form">
	<legend>Финансиски Информации</legend>
		<table class="data_forms_wide">
		<tr>
		    <td class="label"><?php echo form_label('Банка:');?></td>
		    <td><?php echo form_input('bank', set_value('bank', $partner->bank));?></td>
		   	<td class="label"><?php echo form_label('Број на Сметка:');?></td>
		    <td><?php echo form_input('account_no', set_value('account_no', $partner->account_no));?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Дан.број:');?></td>
		    <td><?php echo form_input('tax_no', set_value('tax_no', $partner->tax_no));?></td>
		</tr>
		</table>
</fieldset>
<?php echo validation_errors(); ?>
<?php echo form_hidden('id',$partner->id); ?>
<?php echo form_close();?>
</div>