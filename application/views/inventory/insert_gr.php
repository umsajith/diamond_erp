<div id="new_partner" style="display: none;" title="Внеси нов Добавувач">
	<?=form_label('Фирма:');?>
    <?=form_input('company','',"size=25");?>
</div>
<h2><?php echo $heading; ?></h2>
<?php echo form_open('inventory/insert_gr',"id='form'");?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
	<tr>
	    <td class="label"><?php echo form_label('Добавувач:');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('partner_fk',$partners);?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Артикл:');?><span class='req'>*</span></td>
	    <td><select id="product"></select></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Категорија: ');?></td>
	    <td><?php echo form_input(array('id'=>'category'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Количина:');?><span class='req'>*</span></td>
	    <td><?php echo form_input('quantity',set_value('quantity'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('ЕМ: ');?></td>
	    <td><?php echo form_input(array('id'=>'uname'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Начин: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('purchase_method',array(''=>'- Начин -','cash'=>'Готовина','invoice'=>'Фактура','0'=>'Друго'),set_value('purchase_method')); ?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Цена (без ДДВ): ');?></td>
	    <td><?php echo form_input('price', set_value('price'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Документ:');?></td>
	    <td><?php echo form_input('ext_doc',set_value('ext_doc'));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Нарачано:');?></td>
	    <td><?php echo form_input('dateoforder');?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Траење:');?></td>
	    <td><?php echo form_input('dateofexpiration',set_value('dateofexpiration')); ?></td>
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
		    $("div.quick_message").text('Полето Сировина е задожително');
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

		$(".quick_message").hide();
		
		$("#date, #uname, #category").attr("disabled", "disabled");
		$("input#code").val("");
		$("input#category").val("");
		$("input#uname").val("");

		$( "input[name=dateoforder]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
		});

		$( "input[name=dateofexpiration]" ).datepicker({
			dateFormat: "yy-mm-dd",
			minDate: +0,
		});
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