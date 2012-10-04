<h2><?php echo $heading; ?></h2>
<hr>
	
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>
	<?php if($master->locked != 1):?>
	<div id="buttons">
			<a href="<?php echo site_url('orders_list/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
			<a href="<?php echo site_url('orders_list/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
	<hr/>
	<?php endif; ?>
	<?php if($master->locked == 1):?>
		<h4>Ставката е заклучена од страна на администратор.</h4>
	<?php endif;?>
<div class="f_left">
<dl class="order_list_dl">
	<dt>Датум</dt>
	<dd><?php echo $master->date; ?></dd>
	<dt>Дистрибутер</dt>
	<dd><?php echo $master->distributor; ?></dd>
	<dt>Документ</dt>
	<dd><?php echo ($master->ext_doc) ? $master->ext_doc : '-' ; ?></dd>
	<dt>Код</dt>
	<dd><?php echo ($master->code) ? $master->code : '-' ; ?></dd>
	<dt>Белешка</dt>
	<dd><?php echo ($master->note) ? $master->note : '-' ; ?></dd>
	<?php if($this->session->userdata('admin')):?>
        <dt>Оператор:</dt>
        <dd><?php echo $master->operator;?></dd>  
    <?php endif;?>
</dl>
<?php if($master->locked != 1):?>
	<table id="partner_product_ol">
		<?php echo form_open('',"id='par_prod_form'"); ?>
			<caption>Внес на Нов Налог за Продажба</caption>
		<tr>
			<td>Купувач:</td>
			<td><?php echo form_input('customer') ?></td>
		</tr>
		<tr>
			<td>Плаќање:</td>
			<td><?php echo form_dropdown('payment_mode_fk',$pmodes) ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
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
			<span class="add_icon" id="insert_product">&nbsp;</span></td>
		</tr>
			<?php echo form_hidden('order_list_id',$master->id); ?>
			<?php echo form_hidden('distributor_id',$master->distributor_id); ?>
			<?php echo form_hidden('date',$master->date); ?>
		<?php echo form_close(); ?>
	</table>
	<table id="order_grid" class="details">
    	<tr>
    		<th>&nbsp;</th>
    		<th>Производ</th>
    		<th>Земена Кол.</th>
    		<th>Вратена Кол.</th>
    		<th>&nbsp;</th>
    	</tr>
	</table>
	<?php echo form_button('insert_order','Внеси Налог',"class='save'"); ?>
<?php endif; ?>
</div>
<?php if ($results): ?>
	<div class="f_right">
		<h3>Налози за Продажба во овој Извештај</h3>
		<table class="master_table">
			<tr>
			<th>&nbsp;</th>
			<th>Датум</th>
			<th>Купувач</th>
			<th>Плаќање</th>
			<th>Внес</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($results as $row):?>
			<tr>
				<td class="code" align="center"><?php echo anchor('orders/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
				<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateshipped)); ?></td>
				<td><?php echo $row->company; ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
				<td class="functions">
					<?php if($row->locked != 1):?>
						<?php echo anchor('orders/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
						<?php echo anchor('orders/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
					<?php endif;?>
				</td>
			</tr>
		<?php endforeach; ?>	
		</table>
	</div>
<?php endif ?>

<script type="text/javascript">

	$(function(){

		$("input[name=customer]").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('partners/ajx_search'); ?>",
				data: { term: $("input[name=customer]").val()},
				dataType: "json",
				type: "POST",
				success: function(data){
	               response(data);
				}
			});
		},minLength: 2,autoFocus: true});

		$("input[name=customer]").focus();

		$("#insert_product").on('click',function(){
			add_product();
		});

		$("#quantity, #returned_quantity").keypress(function(e){
		      if(e.which == 13){
		         add_product();
		         return false;
		      }   
		});

		$("select#products").live('change',function() {
				if(this.selectedIndex == '')
				{
					$("span#uom").text('');  
					return false;	
				}
			  $("span#uom").html(JSONObject[this.selectedIndex-1].uname);
		});

		/* insert new order */
		$("button[name=insert_order]").on('click',function(){
			submit_form();
		});

	});

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

    var products = [];

	function add_product(){
		
		  var prodname_fk = $("select#products").val();
		  var prodname = $("select#products option:selected").text(); //only for display reasons
		  var quantity = $("#quantity").val(); 
		  var returned_quantity = $("#returned_quantity").val(); 
		  var uom = $("span#uom").html(); //only for display reasons

		  	//Set returned_quantity to defualt 0 if not set
		  	if(returned_quantity == '')
		  		returned_quantity = 0;

			//VALIDATION: Checks if the product or quantity has not been selected
			if (prodname_fk == '')
			{
				alert("Изберете производ!");
				$("select#products").focus();
				return false;
			}

			if (!isNumber(quantity))
			{
				alert("Внесете валидна количина!");
				$("#quantity").focus();
				return false;
			}

			if (!isNumber(returned_quantity))
			{
				alert("Внесете валидна количина!");
				$("#returned_quantity").focus();
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
		  $("span[id=uom]").text("");
		  $("#quantity").val("");
		  $("#returned_quantity").val("");
		  $("select#products").val("").focus();
		  return false;
	}

	//Allows all positive numbers + 0 (integer and decimal)
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n) && n>=0;
	}

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
		var order_list_id = $("input[name=order_list_id]").val();
		var partner_fk = $("input[name=customer]").val(); 
		var dateshipped = $("input[name=date]").val();
		var distributor_fk = $("input[name=distributor_id]").val();
		var payment_mode_fk = $("select[name=payment_mode_fk]").val();


		if (partner_fk == '')
		{
			alert('Полето Купувач е задожително');
			$("input[name=customer]").focus();
			return false;
		}

		if (payment_mode_fk == '')
		{
			alert('Полето Плаќање е задожително');
			$("select[name=payment_mode_fk]").focus();
			return false;
		}

		if(products.length === 0)
		{
			alert("Налогот е празен. Внесете производи!");
			$("select#products").focus();
			return false;
		}

		$("input.save").attr("disabled", true);
		var components = JSON.stringify(products);

		$.post("<?php echo site_url('orders/insert'); ?>",
			{components:components,order_list_id:order_list_id,distributor_fk:distributor_fk,
				partner_fk:partner_fk,dateshipped:dateshipped,payment_mode_fk:payment_mode_fk},
			function(data){
				 if(data)
				 {
				 	location.reload(true);
				 } 	
				 else
				 {
				 	alert("Проблем при внесување! Проверети ги податоците!");
				 	$("input[name=customer]").focus();
				 }
			},"json"
		);
		return false;
	}
</script>