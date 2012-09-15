<h2><?php echo $heading; ?></h2>
<hr>
	<dl>
        <dt>Назив:</dt>
        <dd><?php echo $master->name;?></dd>
        <dt>Производ:</dt>
        <dd><?php echo $master->prodname;?></dd>
        <dt>Количина:</dt>
        <dd><?php echo $master->quantity.' '.$master->uname2;?></dd>
        <dt>Конверзија:</dt>
        <dd><?php echo $master->quantity . ' ' . $master->uname2 . ' = ' .$master->quantity*$master->conversion.' '.$master->uname;?></dd>
        <dt>Внес:</dt>
        <dd><?php echo $master->dateofentry;?></dd>   
        <dt>Статус:</dt>
        <dd><?php echo ($master->is_active == 1) ? 'Активен' : 'Неактивен' ;?></dd>   
	</dl>
<hr>
	Производ: <?php echo form_dropdown('newprod',$products,'id="newprod"')?>
    Количина: <?php echo form_input('qty')?>
   <span class="add_icon" onclick="addProduct(<?php echo $master->id;?>);">&nbsp;&nbsp;&nbsp;</span>
<hr>
<?php if (isset($details) && is_array($details) && count($details) > 0):?>
<table class="master_table">
    <tr>
    	<th>Производ</th>
    	<th>Категорија</th>
    	<th>Количина</th>
    	<th>&nbsp;</th>
    	<th>&nbsp;</th>
    </tr>
	<?php foreach($details as $row):?>
		<tr id="<?php echo $row->prodname_fk;?>" class="<?php echo $row->id; ?>">
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->pcname;?></td>
			<td class="quantity" id="<?php echo $row->id;?>"><?php echo round($row->quantity,6);?></td>
			<td><?php echo $row->uname;?></td>
			<td><span class="removeprod" onclick="removeProduct('<?php echo $row->id;?>');">&nbsp;</span></td>
		</tr>
	<?php endforeach;?>
</table>
<hr/>
<?php endif;?>
<?php $this->load->view('includes/_del_dialog');?>

<script type="text/javascript">

	//Remove Product Function
	function removeProduct(id) 
	{
		var toremove = id;
		$.post("<?php echo site_url('boms/remove_product'); ?>",
				   {id:id},
				   function(data){
					   	  $('tr.' + toremove).remove();
						  alert("Ставката е успешно избришана!");
				   },"json"			   
			   );
		return false;	
	}

	//Add Product Function
	function addProduct(id) 
	{
		var bom_fk = id;
		var prodname_fk = $("select[name=newprod]").val();
		var quantity = $("input[name=qty]").val();

		if (prodname_fk == '')
		  {
			alert('Внесете производ.');
		    $("select[name=newprod]").focus();
		    return false;
		  }
		if (quantity == '' || quantity <= 0)
		  {
			alert('Внесете валидна количина.');
		    $("input[name=qty]").focus();
		    return false;
		  }

		//Searches if product alredy exists
		var exists = $(".master_table").find("tr#"+prodname_fk);

		//alert(exists.size()); return false;

		if(exists.size() != 0){
			alert('Производот веќе постои. Корегирај количина.');
			   $("select[name=newprod]").val(" ");
			   $("input[name=qty]").val(" ");
			   $("select[name=newprod]").focus();
				return false;
			}
		
		$.post("<?php echo site_url('boms/add_product'); ?>",
				   {bom_fk:bom_fk,prodname_fk:prodname_fk,quantity:quantity},
				   function(data){
						   $("select[name=newprod]").val(" ");
						   $("input[name=qty]").val(" ");
						   location.reload();
				   },"json");
				return false;	
	}
	
	$(document).ready(function() {

		//Inline Edit
		$('.quantity').editable('<?php echo site_url('boms/edit_qty'); ?>', {
		    	indicator : 'Saving...',
		    	tooltip   : 'Click to edit...',
		    	id : 'id',
		    	name : 'quantity'
		});
	});	
</script>