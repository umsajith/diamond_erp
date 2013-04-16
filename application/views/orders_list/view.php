<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders_list/edit/$master->id",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders_list/delete/$master->id")?>
        <hr>
    <?php endif;?>
	<?php if($master->locked == 1):?>
		<h4>Ставката е заклучена од страна на администратор.</h4>
	<?php endif;?>
<div class="row-fluid">
    <div class="span5 well"> 
		<dl class="dl-horizontal">
			<dt>Датум</dt>
			<dd><?=$master->date?></dd>
			<dt>Дистрибутер</dt>
			<dd><?=$master->distributor?></dd>
			<dt>Документ</dt>
			<dd><?=uif::isNull($master->ext_doc)?></dd>
			<dt>Код</dt>
			<dd><?=uif::isNull($master->code)?></dd>
			<dt>Белешка</dt>
			<dd><?=uif::isNull($master->note)?></dd>
			<?php if($this->session->userdata('admin')):?>
		        <dt>Оператор:</dt>
		        <dd><?=$master->operator;?></dd>  
		    <?php endif;?>
		</dl>
		<hr>
<?php if($master->locked != 1):?>
	<?php echo form_open('','class="form" id="par_prod_form"'); ?>
		<?=uif::controlGroup('text','Купувач','customer')?>
		<?=uif::controlGroup('dropdown','Плаќање','payment_mode_fk',[$pmodes])?>
		<?=uif::controlGroup('dropdown','Производ','',[],'id="products"')?>
		<?=uif::controlGroup('text','Земена Кол.','')?>
		<?=uif::controlGroup('text','Вратена Кол.','')?>
		<?=uif::button('icon-plus','primary','id="insert_product"')?>
			<?=form_hidden('order_list_id',$master->id); ?>
			<?=form_hidden('distributor_id',$master->distributor_id); ?>
			<?=form_hidden('date',$master->date); ?>
		<?=form_close(); ?>
	
	<table class="table table-condensed">
		<thead>
	    	<tr>
	    		<th>&nbsp;</th>
	    		<th>Производ</th>
	    		<th>Земена Кол.</th>
	    		<th>Вратена Кол.</th>
	    		<th>&nbsp;</th>
	    	</tr>
    	</thead>
	</table>
	<?php endif; ?>
	</div>
	<div class="span7">
	<?php if ($results): ?>
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Датум</th>
					<th>Купувач</th>
					<th>Плаќање</th>
					<th>Внес</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($results as $row):?>
				<tr data-id=<?=$row->id?>>
					<td><?=uif::linkIcon("orders/view/{$row->id}",'icon-file-alt')?></td>
					<td><?=uif::date($row->dateshipped)?></td>
					<td><?php echo $row->company; ?></td>
					<td><?php echo $row->name; ?></td>
					<td><?=uif::date($row->dateofentry)?></td>
					<td>
					<?php if($row->locked != 1):?>
						<?=uif::actionGroup('orders',$row->id)?>
					<?php endif;?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>	
		</table>
	</div>
	<?php endif ?>
</div>
<script>

	$(function(){

		$("select[name=payment_mode_fk]").select2();

		var partnersNames = [];
		var partnersIds = {};
		$(document).bind('typeahead:selected', function(e){
		 	console.log("Customer ID: " + partnersIds[e.target.value]);
		});
		$.getJSON("<?=site_url('partners/ajxAllPartners')?>",function (data){
            $.each( data, function (i,row){
                partnersNames.push( row.name );
                partnersIds[row.name] = row.id;
            });
	        $("input[name=customer]").typeahead({local:partnersNames}).focus();
	     });

		$("#insert_product").on('click',function(){
			add_product();
		});

		$("#quantity, #returned_quantity").keypress(function(e){
		      if(e.which == 13){
		         add_product();
		         return false;
		      }   
		});

		$(document).on('change','select#products',function() {
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

	$.getJSON("<?=site_url('products/dropdown/salable')?>", function(result) {
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