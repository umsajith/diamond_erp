<h2><?php echo $heading; ?></h2>
<?php echo form_open("inventory/edit/$page/$goods_receipt->id");?>
<hr>
	<?php echo form_submit('','Сними','class="save"');?>
<hr>
<table class="data_forms">
<?php if(isset($vendors)):?>
	<tr>
	    <td class="label"><?php echo form_label('Добавувач: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('partner_fk',$vendors,set_value('partner_fk',$goods_receipt->partner_fk));?></td>
	</tr>
<?php endif;?>
<tr>
    <td class="label"><?php echo form_label('Артикл: ');?><span class='req'>*</span></td>
    <td><select id="product" name="prodname_fk"></select></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Количина: ');?><span class='req'>*</span></td>
    <td><?php echo form_input('quantity',set_value('quantity',$goods_receipt->quantity));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('ЕМ: ');?></td>
    <td><?php echo form_input(array('id'=>'uname','value'=> $goods_receipt->uname));?></td>
</tr>
<tr>
    <td class="label"><?php echo form_label('Начин: ');?><span class='req'>*</span></td>
    <td><?php echo form_dropdown('purchase_method',array('0'=>'Непознато','cash'=>'Готовина','invoice'=>'Фактура'),set_value('purchase_method',$goods_receipt->purchase_method)); ?></td>
</tr>
<?php if($goods_receipt->type!='po'):?>
	<tr>
	    <td class="label"><?php echo form_label('Документ:');?></td>
	    <td><?php echo form_input('ext_doc',set_value('ext_doc',$goods_receipt->ext_doc));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Цена (без ДДВ): ');?></td>
	    <td><?php echo form_input('price', set_value('price',$goods_receipt->price));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Траење:');?></td>
	    <td><?php echo form_input('dateofexpiration',set_value('dateofexpiration',$goods_receipt->dateofexpiration)); ?></td>
	</tr>
	
	<tr>
	    <td class="label"><?php echo form_label('Примено: ');?></td>
	    <td><?php echo form_input(array('id'=>'date','value'=> $goods_receipt->datereceived));?></td>
	</tr>
<?php endif;?>
<?php if($goods_receipt->type=='po'):?>
	<tr>
	    <td class="label"><?php echo form_label('Задолжение: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('assigned_to',$employees,set_value('assigned_to',$goods_receipt->assigned_to));?></td>
	</tr>
	<tr>
	    <td class="label"><?php echo form_label('Статус: ');?><span class='req'>*</span></td>
	    <td><?php echo form_dropdown('po_status',array('approved'=>'Оддобрено','pending'=>'Во Исчекување','redjected'=>'Одбиено'),set_value('po_status',$goods_receipt->po_status)); ?></td>
	</tr>	
<?php endif;?>
	<tr>
	    <td class="label"><?php echo form_label('Нарачано:');?></td>
	    <td><?php echo form_input('dateoforder',set_value('dateoforder',$goods_receipt->dateoforder));?></td>
	</tr>
<tr>
    <td class="label"><?php echo form_label('Белешка: ');?></td>
    <td><textarea name="comments" rows="5"><?php echo $goods_receipt->comments;?></textarea></td>
</tr>
<?php echo form_hidden(array('prodname_fk' => $goods_receipt->prodname_fk));?>
<?php echo form_close();?>
</table>
<?php echo validation_errors(); ?>

<script type="text/javascript">
	//Dropdown menu populating! PRODUCTS
	$.getJSON("<?php echo site_url('products/dropdown/purchasable'); ?>", function(result) {
	    var optionsValues = "<select id='product'>";
	    JSONObject = result;
	    optionsValues += '<option value="">' + '--' + '</option>';
	    $.each(result, function() {
		    if(<?php echo $goods_receipt->prodname_fk;?> == this.id){
		       optionsValues += '<option value="' + this.id + '" selected="selected">' + this.prodname + '</option>';
		    }
		    else{
	           optionsValues += '<option value="' + this.id + '">' + this.prodname + '</option>';
		    }
	    });
	    optionsValues += '</select>';
	    var options = $("select#product");
	    options.replaceWith(optionsValues);  
	});

	//LIVE SEARCH
	/*
		function get_partner(value){
	
			if(value.length <= 1){
				$("#partner_search_results").hide();
			}
			else {
				$.post("<?php echo site_url('partners/live_search');?>", {q: ""+value+""}, function(data){
	                if(data.length >0) {
	                	$("#partner_search_results").show();
	                	$("#partner_search_results").html(data);	
	                }
				});	
			}
		}
	
		function fill(id,name) {
	        $("input[name=partner_fk]").val(id);
	        $("input[name=test]").val(name);
	        setTimeout("$('#partner_search_results').hide();", 200);
	    }
	*/
	$(document).ready(function() {
		
		$("#date, #uname, #received_by").attr("disabled", "disabled");
		
		$( "input[name=dateoforder]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
		});
		$( "input[name=dateofexpiration]" ).datepicker({
			dateFormat: "yy-mm-dd",
			minDate: +0,
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