<?=uif::contentHeader($heading,$master)?>
	<?php if(!$master->locked):?>
        <?=uif::linkButton("orders/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("orders/delete/{$master->id}")?>
	<hr>	
    <?php endif;?>
<div class="row-fluid">
	<div class="span5 well well-small">
		<dl class="dl-horizontal">
	        <dt>Датум</dt>
	        <dd><?=uif::date($master->dateshipped)?></dd>
	        <dt>Купувач:</dt>
	        <dd><?=anchor("partners/view/$master->pid",$master->company)?></dd>
	        <dt>Дистрибутер:</dt>
	        <dd><?=$master->lname . ' ' . $master->fname?></dd>
	        <dt>Извештај:</dt>
	        <dd><?=($master->order_list_id) ?
	        	anchor("orders_list/view/{$master->order_list_id}",'#'.$master->order_list_id) : '-' ; ?></dd>
	       	<dt>Плаќање:</dt>
	        <dd><?=uif::isNull($master->name)?></dd>
	        <dt>Белешка:</dt>
	        <dd><?=uif::isNull($master->comments)?></dd>     
		</dl>
	</div>
	<div class="span7">
		<?php if(!$master->locked):?>
		<div class="legend">Додавање артикл</div>
		<div class="well well-small form-inline text-right">
			<?=uif::formElement('dropdown','','prodname_fk',[],' class="input-large"')?>
			<div class="input-append">
				<?=uif::formElement('text','','quantity','','placeholder="Земено" class="input-medium"')?>
				<span class="add-on uom"></span>
			</div>
			<div class="input-append">
				<?=uif::formElement('text','','returned_quantity','','placeholder="Вратено" class="input-small"')?>
				<?=uif::button('icon-plus-sign','success','onClick="addProduct();"')?>
			</div>
		</div>
		<?php endif;?>
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
		<div class="legend">Артикли во овој Налог за Продажба</div>
		<table class="table table-condensed ordered-products">
			<thead>
			    <tr>
			    	<th>&nbsp;</th>
			    	<th>Производ</th>
			    	<th>Категорија</th>
			    	<th>Земено</th>
			    	<th>Вратено</th>
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
							<a href="#" class="editable" data-original-title="Земено" 
							data-name="quantity" data-pk="<?=$row->id?>"><?=$row->quantity?></a>
						<?php else:?>
							<?=$row->quantity?>
						<?php endif;?>
					</td>
					<td>
						<?php if(!$master->locked):?>
							<a href="#" class="editable" data-original-title="Вратено" 
							data-name="returned_quantity" data-pk="<?=$row->id?>"><?=$row->returned_quantity?></a>
						<?php else:?>
							<?=$row->returned_quantity?>
						<?php endif;?>
					</td>
					<td class="left"><?=$row->uname;?></td>
					<td><?=(!$master->locked) ? 
						uif::staticIcon('icon-trash','onClick="removeProduct('.$row->id.')"'):' '?>
					</td>
				</tr><?php $i++;?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
		<?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong>Овој налог за продажба е заклучен по калкулација за плата #
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk)?></strong>
        </div>
    <?php endif;?>
	</div>
</div>
<script>
	$(function(){

		$('.editable').editable({
		    type: 'text',
		    url: "<?=site_url('orders_details/ajxEditQty')?>",
		    title: 'Qty'
		});

		var produtsSelect = $("select[name=prodname_fk]");

	    $.getJSON("<?=site_url('products/dropdown/salable')?>", function(result) {
			JSONObject = result;
			var options = '<option></option>';
			$.each(result, function(i, row){
				options += '<option value="' + row.id + '">' + row.prodname + '</option>';
			});
			produtsSelect.html(options).select2({placeholder:'Артикл'});
		});

		$("select[name=prodname_fk]").on("change",function(e) {
			$(this).val(e.val);
			$("span.uom").html(JSONObject[this.selectedIndex-1].uname);
		});	

	});

	//Add Product Function
	function addProduct(){

		var order_fk = "<?=$master->id?>";
		var product = $("select[name=prodname_fk]");
		var qty = $("input[name=quantity]");
		var rqty = $("input[name=returned_quantity]");

		var exists = false;

		$("table.ordered-products tr.product-row").each(function() {
			if($(this).data("pid") == product.val()){
				exists = true;
			}
		});

		if(exists) {
			alert("Product Exists!");
			return false;
		}

		var out = {
			order_fk : order_fk,
			prodname_fk : product.val(),
			quantity : qty.val(),
			returned_quantity : rqty.val()
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