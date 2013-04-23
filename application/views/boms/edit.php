<?=uif::contentHeader($heading)?>
	<?=form_open("boms/edit/{$master->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','prodname',$master)?>
		<?=uif::controlGroup('text','Количина','quantity',$master)?>
		<?=uif::controlGroup('dropdown','ЕМ','uname_fk',[$uoms,$master])?>
		<?=uif::controlGroup('text','Конверзија','conversion',$master)?>
		<?=uif::controlGroup('dropdown','Артикл','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uom" disabled')?>
		<?=uif::controlGroup('text','Категорија','','','id="category" disabled')?>
		<?=uif::controlGroup('textarea','Белешка','description',$master)?>
		<?=form_hidden('prodname_fk',$master->prodname_fk)?>
		<?=form_hidden('id',$master->id)?>
	<?=form_close()?>
	</div>
	<div class="span6">
		<?=uif::formElement('dropdown','Производ','',[],'id="prod"')?>
		<?=uif::formElement('text','Количина','')?>
   		<?=uif::button('icon-plus','success')?>
		<?php if (isset($details) AND is_array($details) AND count($details)):?>
		<table class="table">
			<thead>
		    <tr>
		    	<th>Производ</th>
		    	<th>Категорија</th>
		    	<th>Количина</th>
		    	<th>&nbsp;</th>
		    	<th>&nbsp;</th>
		    </tr>
		    </thead>
		    <tbody>
			<?php foreach($details as $row):?>
				<tr id="<?php echo $row->prodname_fk;?>" class="<?php echo $row->id; ?>">
					<td><?php echo $row->prodname;?></td>
					<td><?php echo $row->pcname;?></td>
					<td class="quantity" id="<?php echo $row->id;?>"><?php echo round($row->quantity,6);?></td>
					<td><?php echo $row->uname;?></td>
					<td><span class="removeprod" onclick="removeProduct('<?php echo $row->id;?>');">&nbsp;</span></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php endif;?>
	</div>
</div>
	
<script>
	$(function() {
		$("select").select2();
		var options = {
			hidden : "input[name=prodname_fk]",
			select : "#products",
			aux1 : "#uom",
			aux2 : "#category",
			prodname_fk : "<?=$master->prodname_fk?>",
			args : {
				salable : 1
			}
		};
		cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options);
		var options2 = {
			select : "#prod"
		};
		cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options2);	
	});
</script>

<script>

// 	//Remove Product Function
// 	function removeProduct(id) 
// 	{
// 		var toremove = id;
// 		$.post("<?php echo site_url('boms/remove_product'); ?>",
// 				   {id:id},
// 				   function(data){
// 					   	  $('tr.' + toremove).remove();
// 						  alert("Ставката е успешно избришана!");
// 				   },"json"			   
// 			   );
// 		return false;	
// 	}

// 	//Add Product Function
// 	function addProduct(id) 
// 	{
// 		var bom_fk = id;
// 		var prodname_fk = $("select[name=newprod]").val();
// 		var quantity = $("input[name=qty]").val();

// 		if (prodname_fk == '')
// 		  {
// 			alert('Внесете производ.');
// 		    $("select[name=newprod]").focus();
// 		    return false;
// 		  }
// 		if (quantity == '' || quantity <= 0)
// 		  {
// 			alert('Внесете валидна количина.');
// 		    $("input[name=qty]").focus();
// 		    return false;
// 		  }

// 		//Searches if product alredy exists
// 		var exists = $(".master_table").find("tr#"+prodname_fk);

// 		//alert(exists.size()); return false;

// 		if(exists.size() != 0){
// 			alert('Производот веќе постои. Корегирај количина.');
// 			   $("select[name=newprod]").val(" ");
// 			   $("input[name=qty]").val(" ");
// 			   $("select[name=newprod]").focus();
// 				return false;
// 			}
		
// 		$.post("<?php echo site_url('boms/add_product'); ?>",
// 				   {bom_fk:bom_fk,prodname_fk:prodname_fk,quantity:quantity},
// 				   function(data){
// 						   $("select[name=newprod]").val(" ");
// 						   $("input[name=qty]").val(" ");
// 						   location.reload(true);
// 				   },"json");
// 				return false;	
// 	}
	
// 	$(function() {

// 		//Inline Edit
// 		$('.quantity').editable("<?php echo site_url('boms/edit_qty'); ?>", {
// 		    	indicator : 'Saving...',
// 		    	tooltip   : 'Клик за корекција...',
// 		    	id : 'id',
// 		    	name : 'quantity'
// 		});
// 	});	
</script>