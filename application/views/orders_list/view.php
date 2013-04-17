<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders_list/edit/$master->id",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders_list/delete/$master->id")?>
		<?=uif::button('icon-lock','info','onClick=cd.lockOrderList("'.site_url('orders_list/ajxLock').'",'.$master->id.')')?>
		<?=uif::button('icon-save','primary','onClick="submit_form()"')?>
    <?php else:?>
		<?=uif::button('icon-unlock','info','onClick=cd.unlockOrderList("'.site_url('orders_list/ajxUnlock').'",'.$master->id.')')?>
    <?php endif;?>
        <hr>
<div class="row-fluid">
<?php if(!$master->locked):?>
    <div class="span5 well"> 
			<?=form_hidden('order_list_id',$master->id)?>
			<?=form_hidden('distributor_id',$master->distributor_id)?>
			<?=form_hidden('date',$master->date)?>	
			<?=uif::controlGroup('text','','customer','','placeholder="Купувач"')?>
			<?=form_hidden('partner_fk')?>
			<?=uif::controlGroup('dropdown','','payment_mode_fk',[$pmodes],'data-placeholder="Плаќање"')?>
		<hr>
			<?=uif::controlGroup('dropdown','','prodname_fk',[])?>	
		<div class="input-append">
			<?=uif::formElement('text','','quantity','','placeholder="Земена Кол."')?>
		</div>
		<span class="uom"></span>
		<div class="input-append">
			<?=uif::formElement('text','','returned_quantity','','placeholder="Вратена Кол."')?>
			<?=uif::button('icon-plus-sign','success','id="add-product"')?>
		</div>
		<hr>
		<table class="table table-condensed temp-table">
			<thead>
		    	<tr>
		    		<th>&nbsp;</th>
		    		<th>Производ</th>
		    		<th>Земено</th>
		    		<th>Вратено</th>
		    		<th>&nbsp;</th>
		    	</tr>
	    	</thead>
		</table>
	</div>
<?php endif; ?>
	<?php if ($results): ?>
	<div class="span7">
		<?php if($master->locked == 1):?>
			<div class="alert">
				<i class="icon-lock"></i>
				<strong>Ставката е заклучена! Потребно е да ја отклучите за натамошна работа</strong>
			</div>
		<?php endif;?>
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
		<table class="table table-condensed">
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
</div>
<script>
	$(function(){

		$(".temp-table").hide();

		$("select[name=payment_mode_fk]").select2();
		$("select[name=prodname_fk]").select2();

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

		$("#add-product").on('click',function(){
			add_product();
		});
		
		$("input[name=quantity], input[name=returned_quantity]").keypress(function(e){
			if(e.which == 13){
				add_product();
				return false;
			}   
		});

		$("select[name=prodname_fk]").on("change",function(e) {
			$(this).val(e.val);
			$("span.uom").html(JSONObject[this.selectedIndex].uname);
		});
		$("select[name=payment_mode_fk]").on("change",function(e) {
			$(this).val(e.val);
		});

		/* insert new order */
		// $("button[name=insert_order]").on('click',function(){
		// 	submit_form();
		// });

	});

	var produtsSelect = $("select[name=prodname_fk]");

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
		  var payment_mode_fk = $("select[name=payment_mode_fk]").val();

		  var prodname = $("select[name=prodname_fk] option:selected").text(); //only for display reasons
		  var prodname_fk = $("select[name=prodname_fk]").val();
		  var quantity = $("input[name=quantity]").val(); 
		  var returned_quantity = $("input[name=returned_quantity]").val(); 
		  var uom = $("span.uom").html(); //only for display reasons

		  console.log(partner_fk);
		  console.log(prodname_fk);
		  console.log(prodname);
		  console.log(quantity);
		  console.log(returned_quantity);
		  console.log(payment_mode_fk);
		  //return;

		  // if(partner_fk == ''){
		  // 	$("input[name=customer]").focus();
		  // 	return;
		  // }
		  // if(partner_fk == ''){
		  // 	$("input[name=customer]").focus();
		  // 	return;
		  // }

		  	//Set returned_quantity to defualt 0 if not set
		  	if(returned_quantity == ''){	
		  		returned_quantity = 0;
		  	}

			//VALIDATION: Checks if the product or quantity has not been selected
			if (prodname_fk == '')
			{
				alert("Изберете производ!");
				$("select[name=prodname_fk]").focus();
				return false;
			}

			if (!isNumber(quantity))
			{
				alert("Внесете валидна количина!");
				$("input[name=quantity]").focus();
				return false;
			}

			if (!isNumber(returned_quantity))
			{
				alert("Внесете валидна количина!");
				$("input[name=returned_quantity]").focus();
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
		      products[i].returned_quantity += Number(returned_quantity);

		      break;
		    }
		  }
		  //Pushes the Objects(products [id,quantity,prodname,uom]) into the Array  
		  if (!exists)
		    products.push({ id: prodname_fk, prodname: prodname, uname:uom, 
		    	quantity: Number(quantity), returned_quantity:Number(returned_quantity) });
		    
		  // Update the information of the product table
		  updateTable();

		  //Emptys the product and quantity of the COMPONENTS after successfull ADD
		  $("span.uom").html(' ');
		  $("input[name=quantity]").val("");
		  $("input[name=returned_quantity]").val("");
		  $("select[name=prodname_fk]").select2("data",'').focus();
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
		while (table.rows.length > 1) {
			table.deleteRow(1);
		}

		// Add as many rows as there are number of products in the array
		while (table.rows.length < products.length + 1) {
			var row = table.insertRow(-1);
			// Add as many cells to the new row as there are in the header row
			while (row.cells.length < table.rows[0].cells.length)
			row.insertCell(-1);
		}
	    
		// Update information
		for (var i = 0; i < products.length; i++) {
			table.rows[i + 1].cells[0].innerHTML = i+1;
			//table.rows[i + 1].cells[1].innerHTML = products[i].id;
			table.rows[i + 1].cells[1].innerHTML = products[i].prodname;
			table.rows[i + 1].cells[2].innerHTML = products[i].quantity + " " + products[i].uname;
			table.rows[i + 1].cells[3].innerHTML = products[i].returned_quantity + " " + products[i].uname;
			//table.rows[i + 1].cells[3].innerHTML = products[i].uname;
			table.rows[i + 1].cells[4].innerHTML = '<i class="icon-trash" onclick="removeRecord('+i+')"></i>';
		}
	}

	// 'index' refers to an index of the object inside the 'products' array
	function removeRecord(index) {
	  products.splice(index, 1);
	  updateTable();
	}

	function submit_form(){


		var order_list_id = $("input[name=order_list_id]").val();
		var partner_fk = $("input[name=partner_fk]").val(); 
		var dateshipped = $("input[name=date]").val();
		var distributor_fk = $("input[name=distributor_id]").val();
		var payment_mode_fk = $("select[name=payment_mode_fk]").val();

		console.log('========');
		console.log(order_list_id);
		console.log(partner_fk);
		console.log(dateshipped);
		console.log(distributor_fk);
		console.log(payment_mode_fk);
		//alert(1); return;

		if (partner_fk == ''){
			alert('Полето Купувач е задожително');
			$("input[name=customer]").focus();
			return false;
		}

		if (payment_mode_fk == ''){
			alert('Полето Плаќање е задожително');
			$("select[name=payment_mode_fk]").focus();
			return false;
		}

		if(products.length === 0){
			alert("Налогот е празен. Внесете производи!");
			$("select#products").focus();
			return false;
		}

		$("input.save").attr("disabled", true);

		var out = {
			components:JSON.stringify(products),
			order_list_id:order_list_id,
			distributor_fk:distributor_fk,
			partner_fk:partner_fk,
			dateshipped:dateshipped,
			payment_mode_fk:payment_mode_fk
		};

		$.post("<?=site_url('orders/insert')?>",out,function(data){
			 if(data){
			 	location.reload(true);
			 }else{
			 	alert("Проблем при внесување! Проверети ги податоците!");
			 	$("input[name=customer]").focus();
			 }
		},"json");
		return false;
	}
</script>