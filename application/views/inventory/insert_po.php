<h2><?php echo $heading; ?></h2>
<?php echo form_open('inventory/insert_po',"id='form'");?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Артикл:');?><span class='req'>*</span></td>
    <td><select id="product"></select></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Количина:');?></td>
    <td><?php echo form_input('quantity',set_value('quantity'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('ЕМ: ');?></td>
    <td><?php echo form_input(array('id'=>'uname'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Категорија: ');?></td>
    <td><?php echo form_input(array('id'=>'category'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Белешка: ');?></td>
    <td><textarea name="comments" rows="5" cols="25"></textarea></td>
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
	
	function validate_product()
	{
		 if ($("select#product").val() == ''){
		    $("div.quick_message").text('Полето Артикл е задожително');
			 $(".quick_message").fadeIn();
				  setTimeout(function() {
					  $(".quick_message").fadeOut();	
				}, 2750);
			 $("select#product").focus();
			 return false;
		  }
		 else {
			 return true;
		 }
	}
	
	function validate_qty()
	{
		var qty = $("input[name=quantity]").val();
		if ((qty == '')||(qty <= 0)){
		    $("div.quick_message").text('Полето Количина е задожително');
			 $(".quick_message").fadeIn();
				  setTimeout(function() {
					  $(".quick_message").fadeOut();	
				}, 2750);
			$("input[name=quantity]").focus();
			return false;
		  }
		else {
			 return true;
		 }
	}
	
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