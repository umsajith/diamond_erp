<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders/delete/{$master->id}")?>
	<hr>	
    <?php endif;?>
<div class="row-fluid">
	<div class="span5 well well-small">
		<dl class="dl-horizontal">
	        <dt><?=uif::lng('attr.date')?>:</dt>
	        <dd><?=uif::date($master->dateshipped)?></dd>
	        <dt><?=uif::lng('attr.customer')?>:</dt>
	        <dd><?=anchor("partners/view/$master->pid",$master->company)?></dd>
	        <dt><?=uif::lng('attr.distributor')?>:</dt>
	        <dd><?=$master->lname . ' ' . $master->fname?></dd>
	        <dt><?=uif::lng('attr.order_list')?>:</dt>
	        <dd><?=($master->order_list_id) ?
	        	anchor("orders_list/view/{$master->order_list_id}",'#'.$master->order_list_id) : '-' ; ?></dd>
	       	<dt><?=uif::lng('attr.payment_method')?>:</dt>
	        <dd><?=uif::isNull($master->name)?></dd>
	        <dt><?=uif::lng('attr.note')?>:</dt>
	        <dd><?=uif::isNull($master->comments)?></dd>     
		</dl>
	</div>
	<div class="span7">
		<?php if(!$master->locked):?>
		<div class="legend"><?=uif::lng('app.adding_item')?></div>
		<div class="well well-small form-inline text-right">
			<?=uif::formElement('dropdown','','prodname_fk',[],' class="input-large"')?>
			<div class="input-append">
				<?=uif::formElement('text','','quantity','','placeholder="'.uif::lng('attr.taken').'" class="input-medium"')?>
				<span class="add-on uom"></span>
			</div>
			<div class="input-append">
				<?=uif::formElement('text','','returned_quantity','','placeholder="'.uif::lng('attr.returned').'" class="input-small"')?>
				<?=uif::button('icon-plus-sign','success','onClick="addProduct();"')?>
			</div>
		</div>
		<?php endif;?>
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
		<div class="legend"><?=uif::lng('app.items_in_order')?></div>
		<table class="table table-condensed ordered-products">
			<thead>
			    <tr>
			    	<th>&nbsp;</th>
			    	<th><?=uif::lng('attr.item')?></th>
			    	<th><?=uif::lng('attr.category')?></th>
			    	<th><?=uif::lng('attr.taken')?></th>
			    	<th><?=uif::lng('attr.returned')?></th>
			    	<th>&nbsp;</th>
			    </tr>
		    </thead>
		    <tbody><?php $i = 1;?>
				<?php foreach($details as $row):?>
				<tr class="product-row" data-pid=<?=$row->pid?>>
					<td><?=$i?></td>
					<td><?=$row->prodname?></td>
					<td><?=$row->pcname?></td>
					<td>
						<?php if(!$master->locked):?>
							<a href="#" class="editable" data-original-title="<?=uif::lng('attr.taken')?>" 
							data-name="quantity" data-pk="<?=$row->id?>"><?=$row->quantity?></a>
						<?php else:?>
							<?=$row->quantity?>
						<?php endif;?>
					</td>
					<td>
						<?php if(!$master->locked):?>
							<a href="#" class="editable" data-original-title="<?=uif::lng('attr.returned')?>" 
							data-name="returned_quantity" data-pk="<?=$row->id?>"><?=$row->returned_quantity?></a>
						<?php else:?>
							<?=$row->returned_quantity?>
						<?php endif;?>
					</td>
					<td class="left"><?=$row->uname;?></td>
					<td><?=(!$master->locked) ? 
						uif::button('icon-trash','danger btn-mini','onClick="removeProduct('.$row->id.')"'):' '?>
					</td>
				</tr><?php $i++;?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
		<?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong><?=uif::lng('app.order_locked_by_payroll')?>
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk)?></strong>
        </div>
    <?php endif;?>
	</div>
</div>
<script>
	$(function(){

		$('.editable').editable({
		    type: 'text',
		    url: "<?=site_url('orders_details/ajxEditQty')?>"
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

		$("input[name=quantity], input[name=returned_quantity]").keypress(function(e){
			if(e.which == 13){
				addProduct();
				return false;
			}   
		});

	});

	//Add Product Function
	function addProduct(){

		var order_fk = "<?=$master->id?>";
		var product = $("select[name=prodname_fk]");
		var qty = $("input[name=quantity]");
		var rqty = $("input[name=returned_quantity]");
		var exists = false;

		var quantity = qty.val();
		var returned_quantity = rqty.val();

		if(returned_quantity == '') returned_quantity = 0;

		if (product.val() == ''){
			cd.notify("<?=uif::lng('air.pick_item')?>",'error');
			product.focus();
			return false;
		}

		if (!cd.isNumber(quantity) || quantity == 0){
			cd.notify("<?=uif::lng('air.insert_valid_quantity')?>",'error');
			qty.focus();
			return false;
		}

		if (!cd.isNumber(returned_quantity)){
			cd.notify("<?=uif::lng('air.insert_valid_quantity')?>",'error');
			rqty.focus();
			return false;
		}

		$("table.ordered-products tr.product-row").each(function() {
			if($(this).data("pid") == product.val()){
				$(this).addClass('error').delay(5000).queue(function(){
					$(this).removeClass('error')
				});
				exists = true;
				return false;
			}
		});

		if(exists) {
			cd.notify("<?=uif::lng('air.item_exists_edit_quantity')?>");
			product.select2('data','').focus();
			qty.val('');
			rqty.val('');
			$("span.uom").html('');
			return false;
		}

		var out = {
			order_fk : order_fk,
			prodname_fk : product.val(),
			quantity : quantity,
			returned_quantity : returned_quantity
		};
		$.post("<?=site_url('orders_details/ajxAddProduct')?>",out,function(){
			location.reload(true);
		});	
	}

	//Remove Product Function
	function removeProduct(id){
		$.post("<?=site_url('orders_details/ajxRemoveProduct')?>",{id:id},function(){
			location.reload(true);
		});
	}
</script>