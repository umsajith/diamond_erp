<h2><?php echo $heading; ?></h2>
<?php echo form_open('distribution/insert_return',"id='form'");?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<tr>
    <td class="label"><?php echo form_label('Производ:');?><span class='req'>*</span></td>
    <td><select id="product"></select></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Количина:');?><span class='req'>*</span></td>
    <td><?php echo form_input('quantity',set_value('quantity'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Единица Мерка: ');?></td>
    <td><?php echo form_input(array('id'=>'uname'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Датум:');?><span class='req'>*</span></td>
    <td><?php echo form_input('dateoforigin',set_value('dateoforigin')); ?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Документ:');?></td>
    <td><?php echo form_input('ext_doc',set_value('ext_doc'));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Белешка: ');?></td>
    <td><textarea name="note"></textarea></td>
</tr>
<?php echo form_hidden('inserted_by',$this->session->userdata('userid'));?>
<?php echo form_hidden('prodname_fk');?>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>

<script type="text/javascript">
	//Dropdown menu populating! PRODUCTS
	$.getJSON("<?php echo site_url('products/dropdown/salable'); ?>", function(result) {
	    var optionsValues = "<select id='product'>";
	    JSONObject = result;
	    optionsValues += '<option value="">' + '- Производ -' + '</option>';
	    $.each(result, function() {
	            optionsValues += '<option value="' + this.id + '">' + this.prodname + '</option>';
	    });
	    optionsValues += '</select>';
	    var options = $("select#product");
	    options.replaceWith(optionsValues);  
	});
	
	$(document).ready(function() {
		
		$("#uname").attr("disabled", "disabled");
		$("input#uname").val("");

		$( "input[name=dateoforigin]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0
		});

		//OnChange for Products dropdown menu
		$("select#product").live('change',function() {
				if(this.selectedIndex == '')
				{ 
					$("input#uname").val('');
					$("input#prodname_fk").val('');   
					return false;	
				}
			  $("input[name=prodname_fk]").val(JSONObject[this.selectedIndex-1].id); 
			  $("input#uname").val(JSONObject[this.selectedIndex-1].uname);
			});
 	
	});

</script>