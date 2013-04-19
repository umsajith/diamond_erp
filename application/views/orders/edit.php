<?=uif::contentHeader($heading,$master)?>
    <?=uif::button('icon-save','primary','onClick="submit_form()"')?>
<hr>
<div class="row-fluid">
	<div class="span5 well">
	<?=form_open("orders/edit/{$master->id}",'id="order-form"')?>
		<?=uif::controlGroup('datepicker','','dateshipped',$master)?>
		<?=uif::controlGroup('dropdown','','partner_fk',[$customers,$master],'placeholder="Купувач"')?>
		<?=uif::controlGroup('dropdown','','distributor_fk',[$distributors,$master],'placeholder="Дистрибутер"')?>
		<?=uif::controlGroup('dropdown','','payment_mode_fk',[$modes_payment,$master],'placeholder="Плаќање"')?>	
		<?=uif::controlGroup('dropdown','','ostatus',
		[['pending'=>'Примена','completed'=>'Испорачана','rejected'=>'Одбиена'],$master],'placeholder="Статус"')?>	
		<?=uif::controlGroup('textarea','','comments',$master,'placeholder="Белешка"')?>	
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
	<div class="span7">
		<div class="well well-small form-inline text-right">
			<?=uif::formElement('dropdown','','prodname_fk',[$products],' class="input-large"')?>
			<div class="input-append">
				<?=uif::formElement('text','','quantity','','placeholder="Земено" class="input-medium"')?>
				<span class="add-on uom"></span>
			</div>
			<div class="input-append">
				<?=uif::formElement('text','','returned_quantity','','placeholder="Вратено" class="input-small"')?>
				<?=uif::button('icon-plus-sign','success','onClick="addProduct();"')?>
			</div>
		</div>
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
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
						<td><?=$i;?></td>
						<td><?=$row->prodname;?></td>
						<td><?=$row->pcname;?></td>
						<td>
							<a href="#" class="editable" data-original-title="Земено" data-name="quantity" data-pk="<?=$row->id?>"><?=$row->quantity;?></a>
						</td>
						<td>
							<a href="#" class="editable" data-original-title="Вратено" data-name="returned_quantity" data-pk="<?=$row->id?>"><?=$row->returned_quantity;?></a>
						</td>
						<td class="left"><?=$row->uname;?></td>
						<td><?=uif::staticIcon('icon-trash','onClick="removeProduct('.$row->id.')"')?></td>
				</tr><?php $i++;?>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php else:?>
			<?=uif::load('_no_records')?>
		<?php endif;?>
	</div>
</div>

<script>
	$(function() {
		
		$("select").select2();
		var options = {future: false};
		cd.datepicker(".datepicker",options);

		$('.editable').editable({
		    type: 'text',
		    url: "<?=site_url('orders_details/ajxEditQty')?>",
		    title: 'Qty'
		});
	});

	function submit_form(){
		$("#order-form").submit();
	}

	//Remove Product Function
	function removeProduct(id){
		$.post("<?=site_url('orders_details/ajxRemoveProduct')?>",{id:id},function(){
			location.reload(true);
		});
	}

	//Add Product Function
	function addProduct(){

		var product = $("select[name=prodname_fk]");
		var qty = $("input[name=quantity]");
		var rqty = $("input[name=returned_quantity]");
		var order_fk = "<?=$master->id?>";

		var exists = false;

		$("table.ordered-products tr.product-row").each(function() {
			var pid = $(this).data("pid");
			if(pid == product.val()){
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
	
	
	
</script>