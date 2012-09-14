<h2><?php echo $heading?></h2>
<hr>
	<a href="<?php echo site_url('inventory/insert_po');?>" class="button"><span class="add">Внес</span></a>
	<a href="#" class="button"><span class="receive">Прими</span></a>
<div class="filers">
    <?php echo form_open('inventory/purchase_orders');?>
    <?php echo form_dropdown('prodname_fk', $products, set_value('prodname_fk')); ?>
    <?php echo form_dropdown('pcname_fk',$categories, set_value('pcname_fk')); ?>
    <?php echo form_submit('','Филтрирај');?>
    <?php echo form_close();?>
</div>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results) > 0):?>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Нарачано</th>
		<th>Сировина</th>
		<th>Количина</th>
		<th>Добавувач</th>
		<th>Задолжение</th>
		<th>Начин</th>
		<th>Статус</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach($results as $row):?>
		<tr>
			<td class="code"><?php echo form_checkbox('',$row->id,false,"class='po_check'");?></td>
			<td class="code" align="center"><?php echo anchor("inventory/view/po/$row->id",'&nbsp;','class="view_icon"');?></td>
			<td class="code">
			<?php 
				switch ($row->po_status) 
				{
				    case 'approved':
				        echo "<span class='approved'></span>";
				        $status = 'Оддобрено';
				        break;
				    case 'redjected':
				        echo "<span class='redjected'></span>";
				        $status = 'Одбиено';
				        break;
				   	case 'pending':
				        echo "<span class='pending'></span>";
				        $status = 'Во Исчекување';
				        break;
				}
			?>
			</td>
			<td><?php echo (!$row->dateoforder) ? '-' : mdate('%d/%m/%Y',mysql_to_unix($row->dateoforder)); ?></td>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo ($row->quantity == 0) ? '-' : $row->quantity.' '.$row->uname;?></td>
			<td><?php echo ($row->company) ? $row->company : '-' ;?></td>
			<td><?php echo ($row->assigned_to == null) ? '-' : $row->assignfname.' '.$row->assignlname;?></td>
			<td>
				<?php 
					switch ($row->purchase_method) 
					{
					    case '0':
					       echo '-';
					        break;
					    case 'cash':
					        echo 'Готовина';
					        break;
					   	case 'invoice':
					        echo 'Фактура';
					        break;
					}
				?>
			</td>
			<td><?php echo $status;?></td>
			<td class="functions">
				<?php echo anchor('inventory/edit/po/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('inventory/delete/po/'.$row->id,'&nbsp;','class="del_icon"');?>
			</td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>
</table>
<?php $this->load->view('includes/_pagination');?>

<script type="text/javascript">

	$(function() {
		$("span.receive").on("click",function(){
			
			var ids = $('.po_check:checked').map(function(i,n) {
		        return $(n).val();
		    }).get();

			if(ids.length==0)
			{
				$.pnotify({pnotify_text:"Нема селектирани ставки!",pnotify_type: 'info'});
				return false;
			}
			
			var json_ids = JSON.stringify(ids);

			$.post("<?php echo site_url('inventory/receive_po'); ?>",
					   {ids:json_ids},
					   function(data){
								if(data){
								    location.reload(true);		
								}
						},
			"json");
			return false;
		});
	});
	
</script>