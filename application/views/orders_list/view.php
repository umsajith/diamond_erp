<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders_list/edit/$master->id",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders_list/delete/$master->id")?>
        <hr>
    <?php endif;?>
<div class="row-fluid">
    <div class="span4 well"> 
<?php if(!$master->locked):?>
			<?=uif::controlGroup('text','','customer','','placeholder="Купувач"')?>
			<?=uif::controlGroup('dropdown','','payment_mode_fk',[$pmodes])?>
		<hr>
			<?=uif::controlGroup('dropdown','','',[],'id="products"')?>	
			<?=form_hidden('order_list_id',$master->id)?>
			<?=form_hidden('distributor_id',$master->distributor_id)?>
			<?=form_hidden('date',$master->date)?>	
		<div class="input-append">
			<?=uif::formElement('text','','quantity','','placeholder="Земена Кол."')?>
		</div>
		<div class="input-append">
			<?=uif::formElement('text','','returned_quantity','','placeholder="Вратена Кол."')?>
			<?=uif::button('icon-plus','success','id="insert_product"')?>
		</div>
		<span class="uom"></span>
		<?=form_hidden('prodname_fk')?>
		<?=form_hidden('partner_fk')?>
		<hr>
		<table class="table table-condensed table-bordered temp-table">
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
	<?php if ($results): ?>
	<div class="span8">
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
					<td><?=uif::viewIcon('orders',$row->id)?></td>
					<td><?=uif::date($row->dateshipped)?></td>
					<td><?=$row->company?></td>
					<td><?=$row->name?></td>
					<td><?=uif::date($row->dateofentry)?></td>
					<td>
					<?php if(!$row->locked AND !$master->locked):?>
						<?=uif::actionGroup('orders',$row->id)?>
					<?php endif;?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>	
		</table>
	</div>
	<?php endif ?>
	<?php if($master->locked == 1):?>
		<div class="alert span7">
			<i class="icon-lock"></i>
			<strong>Ставката е заклучена! Потребно е да ја отклучите за натамошна работа</strong>
		</div>
	<?php endif;?>
</div>
<script>
	$(function(){

		$(".temp-table").hide();

		$("select[name=payment_mode_fk]").select2();
		$("select#products").select2();

		var partnersNames = [];
		var partnersIds = {};
		$(document).bind('typeahead:selected', function(e){
			$("input[name=partner_fk]").val(partnersIds[e.target.value]);
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
		
		$("input[name=quantity], input[name=returned_quantity]").keypress(function(e){
		      if(e.which == 13){
		         add_product();
		         return false;
		      }   
		});

		$("select#products").on("change",function(e) {
				// if(this.selectedIndex == '')
				// {
				// 	$("span#uom").text('');  
				// 	return false;	
				// }
				$("input[name=prodname_fk]").val(e.val);
			 	//$("span.uom").html(JSONObject[this.selectedIndex-1].uname);
			 	$("span.uom").html(JSONObject[this.selectedIndex].uname);
				// if(e.val !== ''){
				// 	uname.val(data[this.selectedIndex-1].uname);  
				// } else {
				// 	uname.val('');
				// }
		});

		/* insert new order */
		$("button[name=insert_order]").on('click',function(){
			submit_form();
		});

	});

	var produtsSelect = $("select#products");

    $.getJSON("<?=site_url('products/dropdown/salable')?>", function(result) {
		JSONObject = result;
		var options = '';
		$.each(result, function(i, row){
			options += '<option value="' + row.id + '">' + row.prodname + '</option>';
		});
		produtsSelect.html(options);
	});	

    var products = [];

	function add_product(){
		//
		//TODO When product is added, lock Parnter and Payment Method!
		//
		  var partner_fk = $("input[name=partner_fk]").val();
		  var prodname = $("select#products option:selected").text(); //only for display reasons
		  var prodname_fk = $("input[name=prodname_fk]").val();
		  var quantity = $("input[name=quantity]").val(); 
		  var returned_quantity = $("input[name=returned_quantity]").val(); 
		  var uom = $("span.uom").html(); //only for display reasons

		  console.log(partner_fk);
		  console.log(prodname_fk);
		  console.log(prodname);
		  console.log(quantity);
		  console.log(returned_quantity);
		  //return;

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
		  $("span.uom").text("");
		  $("input[name=quantity]").val("");
		  $("input[name=returned_quantity]").val("");
		  $("select#products").select2("data",'').focus();

		  return false;
	}

	//Allows all positive numbers + 0 (integer and decimal)
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n) && n>=0;
	}

	// Function that updates product table (which contains "No records!" in your image attached)
	function updateTable() 
	{	
		  //$("select[name=payment_mode_fk]").select2('disable');
		  //$("input[name=customer]").attr('disabled', 'disabled');
		  // This variable should contain table where the records should be shown, adjust the selector accordingly
		  var table = $("table.temp-table")[0];
		   $("table.temp-table").show();
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
	    table.rows[i + 1].cells[2].innerHTML = products[i].quantity;
	    table.rows[i + 1].cells[3].innerHTML = products[i].returned_quantity + " " + products[i].uname;
	    //table.rows[i + 1].cells[3].innerHTML = products[i].uname;
	    table.rows[i + 1].cells[4].innerHTML = '<i class="icon-trash" onclick="removeRecord('+i+')"></i>';
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