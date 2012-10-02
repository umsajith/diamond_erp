<!-- <div id="new_partner" style="display: none;" title="Внес на Нов Купувач">
	<?php echo form_label('Фирма:');?>
    <?php echo form_input('company');?>
    <?php echo form_label('Град:');?>
    <?php echo form_dropdown('city',$cities);?>
</div> -->
<h2><?php echo $heading; ?></h2>
<?php echo form_open('orders_list/insert',array('id'=>'order'));?>
<hr>
<div id="buttons">
	<?php echo form_submit('','Сними',"class='save'"); ?>
</div>
<hr>
<div id="west">
<fieldset class="data_form">
	<legend>Основни Информации</legend>
    <table class="data_forms_wide">  
<!-- 		<tr>
		    <td class="label"><?php echo form_label('Купувач:');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('partner_fk',$customers);?></td>
		    <td><span class="add_icon" onclick="new_partner();">&nbsp;</span></td>
		</tr> -->
        <tr>
            <td class="label" ><?php echo form_label('Испорачано на:');?><span class='req'>*</span></td>
            <td><?php echo form_input('dateshipped'); ?></td>
    
            <td class="label"><?php echo form_label('Дистрибутер:');?><span class='req'>*</span></td>
            <td><?php echo form_dropdown('distributor_fk', $distributors,set_value('distributor_fk')); ?></td>
        </tr>
       <!--  <tr>
            <td class="label"><?php echo form_label('Плаќање:');?><span class='req'>*</span></td>
            <td><?php echo form_dropdown('payment_mode_fk', $modes_payment,set_value('payment_mode_fk')); ?></td>
        </tr> -->
	</table >
</fieldset>
<!-- <fieldset class="data_form">
<legend>Продизводи</legend>
	<table>
		<tr>
			<td>Производ:</td>
			<td><select id="products"></select></td>
		</tr>
		<tr>
			<td>Земена Кол.:</td>
			<td><?php echo form_input(array('id'=>'quantity'));?><span id="uom">&nbsp;&nbsp;</span></td>
		</tr>
		<tr>
			<td>Вратена Кол.:</td>
			<td><?php echo form_input(array('id'=>'returned_quantity'));?><span id="uom">&nbsp;&nbsp;</span>
			<span class="add_icon" id="add">&nbsp;&nbsp;&nbsp;</span></td>
		</tr>
	</table>
</fieldset>	
<table id="order_grid" class="details">
    	<tr>
    		<th>&nbsp;</th>
    		<th>Производ</th>
    		<th>Земена Кол.</th>
    		<th>Вратена Кол.</th>
    		<th>&nbsp;</th>
    	</tr>
</table>
</div>
<div id="east">
<fieldset class="data_form">
	<legend>Белешка</legend>
	<table class="data_forms">	
        <tr>
            <td colspan="4"><textarea name="comments" class="wide"></textarea></td>
        </tr> 
	</table >
</fieldset>
</div> -->
<?php echo form_close();?>

<script type="text/javascript">

	function add_product(){
		
		  var prodname_fk = $("select#products").val();
		  var prodname = $("select#products option:selected").text(); //only for display reasons
		  var quantity = $("#quantity").val(); 
		  var returned_quantity = $("#returned_quantity").val(); 
		  var uom = $("span#uom").html(); //only for display reasons

			  //VALIDATION: Checks if the product or quantity has not been selected
			  if (quantity == '' || prodname_fk == '')
			  {
			    alert("Внесете производ и количина!");
			    return false;
			  }
			  else if (quantity <= 0)
			  {
				alert("Внесете валидна количина!");
				$("#quantity").focus();
				return false;
			  }
			  //-------------------------------------------------------------------
		  
		  // Check if product already exists and increase it's quantity instead of adding new record
		  var exists = false;
		  for (var i = 0; i < products.length; i++) {
		    // I assume ID is the key that should be matched inside the list of products
		    if (products[i].id == prodname_fk) {
		      exists = true;
		      // quantity is converted to number to make sure the number is increased instead of concatenating the numbers as strings
		      products[i].quantity += Number(quantity);
		      break;
		    }
		  }
		  //Pushes the Objects(products [id,quantity,prodname,uom]) into the Array  
		  if (!exists)
		    products.push({ id: prodname_fk, prodname: prodname, uname:uom, quantity: Number(quantity), returned_quantity:Number(returned_quantity) });
		    
		  // Update the information of the product table
		  updateTable();

		  //Emptys the product and quantity of the COMPONENTS after successfull ADD
		  $("select#products").val("");
		  $("span[id=uom]").text("");
		  $("#quantity").val("");
		  $("#returned_quantity").val("");
		  $("select#products").focus();
		  return false;
	}

	//NEW PARTNER
	function new_partner(){
		$("#new_partner").dialog({
				modal: true,
				width: "250",
				height: "170",
				resizable: false,
				draggable: false,
				buttons:{ 
						"Сними": function() { save_partner(); },
						"Откажи": function() { $(this).dialog("close"); }
				 }	
		});
    }
	function save_partner(){

		var company = $("input[name=company]").val();
		var city = $("select[name=city]").val();
		var is_customer = 1; //Always insets partner type = Customer
		var ajax = 1;
		
		$.post("<?=site_url('partners/insert')?>",
				{company:company,is_customer:is_customer,postalcode_fk:city,ajax:ajax},
				function(data){
					if(data)
						location.reload(true);
					else
						$("#new_partner").dialog("close");
				}
		);
	} 

	var products = [];
	
	// Function that updates product table (which contains "No records!" in your image attached)
	function updateTable() 
	{	
		  // This variable should contain table where the records should be shown, adjust the selector accordingly
		  var table = $("table#order_grid")[0];
		  //alert(table);
		  
		  // Remove all the rows (except the first one - header row)
		  while (table.rows.length > 1)
		    table.deleteRow(1);
		    
		  // Add as many rows as there are number of products in the array
		  while (table.rows.length < products.length + 1) {
		    var row = table.insertRow(-1);
		  // Add as many cells to the new row as there are in the header row
		  while (row.cells.length < table.rows[0].cells.length)
		    row.insertCell(-1);
	  }
	    
	  // Update information
	  for (var i = 0; i < products.length; i++) 
	  {
	    table.rows[i + 1].cells[0].innerHTML = i+1;
	    //table.rows[i + 1].cells[1].innerHTML = products[i].id;
	    table.rows[i + 1].cells[1].innerHTML = products[i].prodname;
	    table.rows[i + 1].cells[2].innerHTML = products[i].quantity + " " + products[i].uname;
	    table.rows[i + 1].cells[3].innerHTML = products[i].returned_quantity + " " + products[i].uname;
	    //table.rows[i + 1].cells[3].innerHTML = products[i].uname;
	    table.rows[i + 1].cells[4].innerHTML = "<span class=\"removeprod\" onclick=\"removeRecord(" + i + ")\">&nbsp;</span>";
	  }
	}
	
	// 'index' refers to an index of the object inside the 'products' array
	function removeRecord(index) 
	{
	  products.splice(index, 1);
	  updateTable();
	}

	function submit_form()
	{
		var partner_fk = $("select[name=partner_fk]").val(); 
		var dateshipped = $("input[name=dateshipped]").val();
		var distributor_fk = $("select[name=distributor_fk]").val();
		var payment_mode_fk = $("select[name=payment_mode_fk]").val();
		var comments = $("textarea[name=comments]").val();

		  // if (partner_fk == '')
		  // {
		  //   alert('Полето Купувач е задожително');
		  //   $("select[name=partner_fk]").focus();
		  //   return false;
		  // }
		  
		  if (dateshipped == '')
		  {
		    alert('Полето Испорачано на е задожително');
		    $("input[name=dateshipped]").focus();
		    return false;
		  }

		  if (distributor_fk == '')
		  {
		    alert('Полето Дистрибутер е задожително');
		    $("select[name=distributor_fk]").focus();
		    return false;
		  }
		  
		  // if(products.length === 0)
		  // {
			 //  alert("Нарачката е празна. Минимум еден производ е потребен");
			 //  $("select#products").focus();
		  //     return false;
		  // }

		  $("input.save").attr("disabled", true);

			//Converts the JavaScript array into JSON object
			// var components = JSON.stringify(products);
			// //POSTs the JSON object (with components) along with partner_fk(master) and desiredshipping(master)
			// $.post("<?php echo site_url('orders/insert'); ?>",
			// 	   {components:components,distributor_fk:distributor_fk,partner_fk:partner_fk,
			// 	    dateshipped:dateshipped,payment_mode_fk:payment_mode_fk,comments:comments},
			// 	   function(data){
			// 		   //Upon execution of the php scirpt, redirects to Orders, with corresponding success/error message (Flash)
			// 		   location.replace("<?php echo site_url('orders'); ?>");
			// 	   },"json"
				   
			//    );
		return false;
	}

	//Dropdown menu populating! PRODUCTS
	$.getJSON("<?php echo site_url('products/dropdown/salable'); ?>", function(result) {
        var optionsValues = "<select id='products'>";
        JSONObject = result;
        optionsValues += '<option value="">' + '- Производ -' + '</option>';
        $.each(result, function() {
                optionsValues += '<option value="' + this.id + '">' + this.prodname + '</option>';
        });
        optionsValues += '</select>';
        var options = $("select#products");
        options.replaceWith(optionsValues);  
    });	    
	
	$(document).ready(function() {

		$("#quantity, #returned_quantity").keypress(function(e){
		      if(e.which == 13){
		         add_product();
		         return false;
		      }   
		});	
		//Functions following the click of "ADD" button
		$("#add").click(function(){
		  add_product();
		});
				
		//Date Pickers
		$( "input[name=dateshipped]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
		});
		
		//OnChange for Products dropdown menu
		$("select#products").live('change',function() {
				if(this.selectedIndex == '')
				{
					$("span#uom").val('');  
					return false;	
				}
				
			  $("span#uom").html(JSONObject[this.selectedIndex-1].uname);
			});	
	});
</script>