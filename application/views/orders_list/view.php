<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders_list/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders_list/delete/{$master->id}")?>
		<?=uif::button('icon-lock','info','onClick=cd.lockOrderList("'.site_url('orders_list/ajxLock').'",'.$master->id.')')?>
    <?php else:?>
		<?=uif::button('icon-unlock','info','onClick=cd.unlockOrderList("'.site_url('orders_list/ajxUnlock').'",'.$master->id.')')?>
    <?php endif;?>
		<?=uif::button('icon-cog','success','onClick=window.open("'.site_url('orders/reportByOrderList/'.$master->id).'")')?>
	<hr>
<div class="row-fluid">
<?php if(!$master->locked):?>
    <div class="span5 well well-small">
		<div class="legend"><?=uif::lng('app.or_new')?></div>	
			<?=uif::controlGroup('text','','customer','','placeholder="'.uif::lng('attr.customer').'"')?>
			<?=form_hidden('partner_fk')?>
			<?=uif::controlGroup('dropdown','','payment_mode_fk',[$pmodes],'data-placeholder="'.uif::lng('attr.payment_method').'"')?>
		<hr>
			<?=uif::controlGroup('dropdown','','prodname_fk',[])?>	
		<div class="input-append">
			<?=uif::formElement('text','','quantity','','placeholder="'.uif::lng('attr.taken').'"')?>
			<span class="add-on uom"></span>
		</div>
		<div class="input-append">
			<?=uif::formElement('text','','returned_quantity','','placeholder="'.uif::lng('attr.returned').'"')?>
			<?=uif::button('icon-plus-sign','success','id="add-product"')?>
			<?=uif::button('icon-save','primary','id="new-order" onClick="newOrder()"')?>
		</div>
		<table class="table table-condensed table-bordered temp-table">
			<thead>
		    	<tr>
		    		<th>&nbsp;</th>
		    		<th><?=uif::lng('attr.item')?></th>
		    		<th><?=uif::lng('attr.taken')?></th>
		    		<th><?=uif::lng('attr.returned')?></th>
		    		<th>&nbsp;</th>
		    	</tr>
	    	</thead>
		</table>
	</div>
<?php endif; ?>
	<div class=<?=($master->locked)?"span12":"span7"?>>
		<?php if($master->locked == 1):?>
			<div class="alert">
				<i class="icon-lock"></i>
				<strong><?=uif::lng('app.this_order_list_is_locked')?></strong>
			</div>
		<?php endif;?>
		<div class="legend"><?=uif::lng('app.or_ors')?></div>
			<div class="text-center">
				<div class="span2"><i class="icon-calendar"> </i> <?=uif::date($master->date)?></div>
				<div class="span4"><i class="icon-user"> </i> <?=$master->distributor?></div>
				<div class="span2"><i class="icon-tag"> </i> <?=uif::isNull($master->ext_doc)?></div>
				<div class="span4"><i class="icon-eye-open"> </i><?=$master->operator?></div>
			</div>
	<?php if ($results): ?>
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th><?=uif::lng('attr.customer')?></th>
					<th><?=uif::lng('attr.payment_method')?></th>
					<th><?=uif::lng('attr.doe')?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($results as $row):?>
				<tr data-id=<?=$row->id?>>
					<td><?=uif::viewIcon('orders',$row->id)?></td>
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
	<?php endif; ?>
</div>
<script>
	$(function(){

		$(".temp-table").hide();

		$("select[name=payment_mode_fk]").select2({placeholder:"<?=uif::lng('attr.payment_method')?>"});

		//Typeahead Configuration

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

		// End of Typeahead configuration

		$("#add-product").on('click',function(){
			addProduct();
		});
		
		$("input[name=quantity], input[name=returned_quantity]").keypress(function(e){
			if(e.which == 13){
				addProduct();
				return false;
			}   
		});

		$("select[name=payment_mode_fk]").on("change",function(e) {
			$(this).val(e.val);
		});

		var options = {
			select : "select[name=prodname_fk]",
			placeholder : "<?=uif::lng('attr.item')?>",
			aux1 : "span.uom",
			args : {
				salable : 1
			}
		};
		cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options);
	});

    	var products = [];

	function addProduct(){

		var partner_fk = $("input[name=partner_fk]").val();
		var payment_mode_fk = $("select[name=payment_mode_fk]").val();

		var prodname = $("select[name=prodname_fk] option:selected").text();
		var prodname_fk = $("select[name=prodname_fk]").val();
		var quantity = $("input[name=quantity]").val(); 
		var returned_quantity = $("input[name=returned_quantity]").val(); 
		var uom = $("span.uom").html();

		if (partner_fk == ''){
			cd.notify("<?=uif::lng('air.customer_required')?>",'error');
			$("input[name=customer]").focus();
			return false;
		}

		if (payment_mode_fk == ''){
			cd.notify("<?=uif::lng('air.payment_method_required')?>",'error');
			$("select[name=payment_mode_fk]").focus();
			return false;
		}

	  	//Set returned_quantity to defualt 0 if not set
	  	if(returned_quantity == '') returned_quantity = 0;

		if (prodname_fk == ''){
			cd.notify("<?=uif::lng('air.pick_item')?>",'error');
			$("select[name=prodname_fk]").focus();
			return false;
		}

		if (!cd.isNumber(quantity) || quantity == 0){
			cd.notify("<?=uif::lng('air.insert_valid_quantity')?>",'error');
			$("input[name=quantity]").focus();
			return false;
		}

		if (!cd.isNumber(returned_quantity)){
			cd.notify("<?=uif::lng('air.insert_valid_quantity')?>",'error');
			$("input[name=returned_quantity]").focus();
			return false;
		}
		//-------------------------------------------------------------------
		  
		// Check if product already exists and increase it's quantity instead of adding new record
		var exists = false;
		for (var i = 0; i < products.length; i++) {
			if (products[i].id == prodname_fk) {
				exists = true;
				products[i].quantity += Number(quantity);
				products[i].returned_quantity += Number(returned_quantity);
				break;
			}
		}
		if (!exists){
			products.push({ id: prodname_fk, prodname: prodname, uname:uom, 
			quantity: Number(quantity), returned_quantity:Number(returned_quantity) });
		}

		// Update the information of the product table
		updateTable();
		
		//Lock form to currently selected Customer and PaymentMode
		$("input[name=customer]").prop('disabled',true);
		$("select[name=payment_mode_fk]").prop('disabled',true);

		//Emptys the product and quantity of the COMPONENTS after successfull ADD
		$("span.uom").html('');
		$("input[name=quantity]").val("");
		$("input[name=returned_quantity]").val("");
		$("select[name=prodname_fk]").select2("data",'').focus();
	}

	// Function that updates product table (which contains "No records!" in your image attached)
	function updateTable() 
	{	
		var table = $("table.temp-table")[0];
		$("table.temp-table").show();

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
			table.rows[i + 1].cells[1].innerHTML = products[i].prodname;
			table.rows[i + 1].cells[2].innerHTML = products[i].quantity + " " + products[i].uname;
			table.rows[i + 1].cells[3].innerHTML = products[i].returned_quantity + " " + products[i].uname;
			table.rows[i + 1].cells[4].innerHTML = '<?=uif::button("icon-trash","danger btn-mini","onClick=removeRecord('+i+')")?>';
		}
	}
	
	//Remove Product from temporary table
	function removeRecord(index) {
		products.splice(index, 1);
		updateTable();
	}

	//Insert New Order
	function newOrder(){

		if(!products.length > 0) return false;

		//Disable the submit button, preventing insertion
		//of same entries multiple times
		$("#new-order").prop('disabled', true);

		var partner_fk = $("input[name=partner_fk]").val(); 
		var payment_mode_fk = $("select[name=payment_mode_fk]").val();

		var out = {
			products:JSON.stringify(products),
			order_list_id:<?=$master->id?>,
			distributor_fk:<?=$master->distributor_id?>,
			partner_fk:partner_fk,
			dateshipped:"<?=$master->date?>",
			payment_mode_fk:payment_mode_fk
		};

		$.post("<?=site_url('orders/insert')?>",out,function(){location.reload(true);});
	}
</script>