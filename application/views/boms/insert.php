<h2><?php echo $heading; ?></h2>
 <?php echo form_open('boms/insert');?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<div id="west">
    <fieldset class="data_form">
	<legend>Основни Информации</legend>
    <table class="data_forms_wide">    
		<tr>
		    <td class="label"><?php echo form_label('Назив:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('name', set_value('name'));?></td>
		</tr>
        <tr>
            <td class="label" ><?php echo form_label('Количина:');?><span class='req'>*</span></td>
            <td><?php echo form_input('quantity'); ?></td>
    
            <td class="label"><?php echo form_label('ЕМ:');?><span class='req'>*</span></td>
            <td><?php echo form_dropdown('uname_fk',$uoms); ?></td>
        </tr>
        <tr>
        	<td class="label"><?php echo form_label('Производ:');?><span class='req'>*</span></td>
            <td><select id="product"></select></td>
            
            <td class="label" ><?php echo form_label('Конверзија:');?><span class='req'>*</span></td>
            <td><?php echo form_input('conversion'); ?></td>
        </tr>
	</table >
</fieldset>
<fieldset class="data_form">
	<legend>Белешка:</legend>
	<table class="data_forms_wide">
        <tr>
            <td colspan="2"><textarea name="description" class="wide"></textarea></td>
        </tr>    
	</table>
</fieldset>
</div>
<?php echo form_hidden('prodname_fk'); ?>
<?php echo form_close();?>
<?php echo validation_errors(); ?>

<script type="text/javascript">
	// Dropdown menu populating! PRODUICTS
	$.getJSON("<?php echo site_url('products/dropdown/salable'); ?>", function(result) {
	    var optionsValues = "<select id='product'>";
	    JSONObject2 = result;
	    optionsValues += '<option value="">' + '- Производ -' + '</option>';
	    $.each(result, function() {
	            optionsValues += '<option value="' + this.id + '">' + this.prodname + '</option>';
	    });
	    optionsValues += '</select>';
	    var options = $("select#product");
	    options.replaceWith(optionsValues);  
	});
</script>