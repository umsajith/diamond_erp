<h2><?php echo $heading; ?></h2>
<?php echo form_open('inventory/insert_adj');?>
<hr>
    <?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Артикл:');?><span class='req'>*</span></td>
    <td><select id="product"></select></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Категорија: ');?></td>
    <td><?php echo form_input(array('id'=>'category'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Количина: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('quantity',set_value('quantity'));?></td>
</tr>

<tr>
    <td class="label"><?php echo form_label('ЕМ: ');?></td>
    <td><?php echo form_input(array('id'=>'uname'));?></td>
</tr>
<tr>
	<td class="label"><?php echo form_label('Минусирај: ');?></td>
    <td><?php echo form_checkbox('is_use','1',false);?></td>
</tr>
<tr>
	<td class="label"><?php echo form_label('Причина:');?><span class='req'>*</span></td>
    <td><textarea name="comments"></textarea></td>
</tr>
<?php echo form_hidden('prodname_fk');?> 
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>

<script type="text/javascript">
	//Dropdown menu populating! PRODUCTS
	$.get("<?php echo site_url('products/dropdown/purchasable'); ?>", function(result) {
	    var optionsValues = "<select id='product'>";
	    JSONObject = result;
	    optionsValues += '<option value="">' + '- Артикл -' + '</option>';
	    $.each(result, function() {
	            optionsValues += '<option value="' + this.id + '">' + this.prodname + '</option>';
	    });
	    optionsValues += '</select>';
	    var options = $("select#product");
	    options.replaceWith(optionsValues);  
	},"json");
	
$(function() {
		$("#date, #uname, #code, #category").attr("disabled", "disabled");
		$("input#code").val("");
		$("input#category").val("");
		$("input#uname").val("");

		//OnChange for Products dropdown menu
		$(document).on('change','select#product',function() {
				if(this.selectedIndex == '')
				{
					$("input#code").val('');  
					$("input#category").val('');  
					$("input#uname").val('');
					$("input#prodname_fk").val('');   
					return false;	
				}
			  $("input[name=prodname_fk]").val(JSONObject[this.selectedIndex-1].id);
			  $("input#code").val(JSONObject[this.selectedIndex-1].code);  
			  $("input#category").val(JSONObject[this.selectedIndex-1].pcname);  
			  $("input#uname").val(JSONObject[this.selectedIndex-1].uname);
			});		    	
});

</script>