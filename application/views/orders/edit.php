<h2><?php echo $heading; ?></h2>
<hr>
<?php echo form_open('orders/edit/'.$master->id,"id='order'");?>
	<div id="buttons">
		<?php echo form_submit('','Сними','class="save"'); ?>
	</div>
<hr>
<div id="west">
<fieldset class="data_form">
	<legend>Основни Информации</legend>
	<table class="data_forms_wide">	
        <tr>
        	<td class="label"><?php echo form_label('Купувач:');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('partner_fk',$customers,set_value('partner_fk',$master->partner_fk));?></td>
        </tr>
        <tr>
             <td class="label"><?php echo form_label('Испорачано на:');?><span class='req'>*</span></td>
            <td><?php echo form_input('dateshipped',set_value('dateshipped',$master->dateshipped)); ?></td>
            
            <td  class="label"><?php echo form_label('Дистрибутер:');?><span class='req'>*</span></td>
            <td><?php echo form_dropdown('distributor_fk',$distributors,set_value('distributor_fk',$master->distributor_fk)); ?></td>
        </tr>
        <tr>
            <td class="label"><?php echo form_label('Плаќање:');?><span class='req'>*</span></td>
            <td><?php echo form_dropdown('payment_mode_fk', $modes_payment,set_value('payment_mode_fk',$master->payment_mode_fk)); ?></td>
            <td class="label"><?php echo form_label('Статус на Нарачка: ');?></td>
            <td><?php echo form_dropdown('ostatus',array('pending'=>'Примена','completed'=>'Испорачана','rejected'=>'Одбиена'),set_value('ostatus',$master->ostatus)); ?></td>	
        </tr>
	</table>
</fieldset>
<fieldset class="data_form">
	<legend>Белешка</legend>
	<table class="data_forms">	
        <tr>
            <td colspan="4"><?php echo form_textarea('comments',set_value('comments',$master->comments),"class='wide'");?></td>
        </tr> 
	</table >
</fieldset>

<?php echo form_hidden('id',$master->id);?>
<?php echo form_close();?>
<fieldset class="data_form">
	<legend>Продизводи</legend>
   Производ: <?php echo form_dropdown('',$products,'',"id='newprod'")?>
   Количина: <?php echo form_input('','',"id='qty'")?>
   <span class="add_icon" onclick="addProduct(<?php echo $master->id;?>);">&nbsp;&nbsp;&nbsp;</span>
</fieldset>
<?php if (isset($details) && is_array($details) && count($details) > 0):?>
<table id="order_grid" class="details">
	<tr>
   		<th>&nbsp;</th>
    	<th>Производ</th>
    	<th>Категорија</th>
    	<th>Количина</th>
    	<th>&nbsp;</th>
    	<th>Вратена Кол.</th>
    	<th>&nbsp;</th>
    	<th>&nbsp;</th>
	</tr>
	<?php $i = 1;?>
		<?php foreach($details as $row):?>
		<tr id="<?php echo $row->pid;?>">
				<td><?php echo $i;?></td>
				<td><?php echo $row->prodname;?></td>
				<td><?php echo $row->pcname;?></td>
				<td class="ordered_qty" id="<?php echo $row->id;?>"><?php echo $row->quantity;?></td>
				<td class="left"><?php echo $row->uname;?></td>
				<td class="returned_qty" id="<?php echo $row->id;?>"><?php echo ($row->returned_quantity == NULL ? '-' : $row->returned_quantity); ?></td>
				<td class="left"><?php echo $row->uname;?></td>
				<td><span class="removeprod" onclick="removeProduct('<?php echo $row->id;?>');">&nbsp;</span></td>		
		</tr>
		<?php $i++;?>
	<?php endforeach;?>
</table>
<?php endif;?>
</div>

<script>

	//Remove Product Function
	function removeProduct(id) 
	{
		var toremove = id;
		$.post("<?php echo site_url('orders_details/ajxRemoveProduct'); ?>",
				   {id:id},
				   function(data){
					   if(data){
						   $("#"+toremove).parent("tr").remove();
						   $.pnotify(data.message);
					   }
				   },"json"
			   );
		return false;	
	}

	//Add Product Function
	function addProduct(id) 
	{
		var product = $("select#newprod");
		var qty = $("input#qty");

		if (product.val() == '')
		  {
			$.pnotify({pnotify_text:"Изберете Производ!",pnotify_type: 'error'});
			product.focus();
		    return false;
		  }
		if (qty.val() == '' || qty.val() <= 0)
		  {
			$.pnotify({pnotify_text:"Внесете валидна количина!",pnotify_type: 'error'});
			qty.focus();
		    return false;
		  }

		//Searches if product alredy exists
		var exists = $("table#order_grid").find("tr#"+product.val());

		if(exists.size() == 1)
		{
			$.pnotify({pnotify_text:"Производот веќе постои!",pnotify_type: 'error'});
			qty.val(" ");
			product.val(" ").focus();
			return false;
		}
		
		$.post("<?php echo site_url('orders_details/ajxAddProduct'); ?>",
				   {order_fk:id,prodname_fk:product.val(),quantity:qty.val()},
				   function(data){
						   product.val(" ");
						   qty.val(" ");
						   location.reload(true);
					},"json");
		
		return false;	
	}
	
$(function() {
		
		//Date Pickers
		$( "input[name=dateshipped]" ).datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0,
		});

		//Edit in Place
		$(".ordered_qty").editable("<?php echo site_url('orders_details/ajxEditQty'); ?>", {
		    	indicator : 'Saving...',
		    	tooltip   : 'Click to edit...',
		    	id : 'id',
		    	name : 'quantity'
		});

		$(".returned_qty").editable("<?php echo site_url('orders_details/ajxEditRetQty'); ?>", {
	    	indicator : 'Saving...',
	    	tooltip   : 'Click to edit...',
	    	id : 'id',
	    	name : 'returned_quantity'
		});

		//Client Side Validation
		$("#order").submit(function(){
			
			var partner_fk = $("select[name=partner_fk]").val(); 
			var dateshipped = $("input[name=dateshipped]").val();
			var distributor_fk = $("select[name=distributor_fk]").val();
			var payment_mode = $("select[name=payment_mode_fk]").val();

			  if (partner_fk == '')
			  {
			    $.pnotify({pnotify_text:"Полето Купувач е задожително!",pnotify_type: 'error'});
			    $("input[name=partner_fk]").focus();
			    return false;
			  }
			  
			  if (dateshipped == '')
			  {
			    $.pnotify({pnotify_text:"Полето 'Испорачано на' е задожително!",pnotify_type: 'error'});
			    $("input[name=dateshipped]").focus();
			    return false;
			  }

			  if (distributor_fk == '')
			  {
			    $.pnotify({pnotify_text:"Полето 'Дистрибутер' е задожително!",pnotify_type: 'error'});
			    $("select[name=distributor_fk]").focus();
			    return false;
			  }

			  if (payment_mode == '')
			  {
			    $.pnotify({pnotify_text:"Полето 'Плаќање' е задожително!",pnotify_type: 'error'});
				$("select[name=payment_mode_fk]").focus();
			    return false;
			  }

			  $("input[name=submit]").attr("disabled", "disabled");
		});
});	
	
</script>