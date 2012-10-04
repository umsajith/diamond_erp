<h2><?php echo $heading; ?></h2>
	<?php echo form_open('orders_list/search'); ?>
		<div id="searchBox">
			<?php echo form_input('q',set_value('q'),"placeholder='Документ/Код'"); ?>
			<?php echo form_submit('','',"class='search'");  ?>
		</div>
	<?php echo form_close(); ?>
<hr>
	<a href="<?php echo site_url('orders_list/insert');?>" class="button"><span class="add">Внес</span></a>
	<?php if($this->session->userdata('admin')):?>
		<a href="" class="button"><span class="lock">Заклучи</span></a>
		<a href="" class="button"><span class="unlock">Отклучи</span></a>
	<?php endif;?>
	<div class="filters"> 
    <?php echo form_open('orders_list/search');?>
	    <?php echo form_dropdown('distributor_id', $distributors, set_value('distributor_id')); ?>
	    <?php echo form_hidden('q','');  ?>
	    <?php echo form_submit('','',"class='filter'");?>
    <?php echo form_close();?>
	</div>
<table class="master_table">
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<tr>
		<th><?php echo form_checkbox('','',false,"class='check_all'");?>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
			<?php foreach ($columns as $col_name => $col_display):?>
	    		<th <?php if($sort_by==$col_name) echo "class=$sort_order";?>>
	    			<?php echo anchor("orders_list/index/$query_id/$col_name/".(($sort_order=='desc' AND $sort_by==$col_name)?'asc':'desc'),$col_display);?>
	    		</th>
	    	<?php endforeach;?>
		<th>&nbsp;</th>
	</tr>
	<?php foreach ($results as $row):?>
		<tr>
			<td class="code"><?php echo form_checkbox('',$row->id,false,"class='order_check'");?></td>
			<td class="code" align="center"><?php echo anchor('orders_list/view/'.$row->id,'&nbsp;','class="view_icon"');?></td>
			<td class="code" align="center"><?php echo ($row->locked == 0 ? '' : "<span class='lock_icon'></span>");?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->date)); ?></td>
			<td><?php echo $row->distributor; ?></td>
			<td><?php echo ($row->ext_doc) ? $row->ext_doc : '-' ; ?></td>
			<td><?php echo ($row->code) ? $row->code : '-' ; ?></td>
			<td><?php echo mdate('%d/%m/%Y',mysql_to_unix($row->dateofentry));?></td>
			<td class="functions">
			<?php if($row->locked != 1):?>
				<?php echo anchor('orders_list/edit/'.$row->id,'&nbsp;','class="edit_icon"');?> | 
				<?php echo anchor('orders_list/delete/'.$row->id,'&nbsp;','class="del_icon"');?>
			<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php else:?>
	<?php $this->load->view('includes/_no_records');?>
<?php endif;?>	
</table>
<?php $this->load->view('includes/_pagination');?>

<script type="text/javascript">
	
	$(function(){

		$(".check_all").attr("checked", false);

		// Locks the Orders
		$("span.lock").on("click",function(){

			var ids = $(".order_check:checked").map(function(i,n) {
		        return $(n).val();
		    }).get();
			if(ids.length==0)
			{
				$.pnotify({pnotify_text:"Нема селектирани ставки!",pnotify_type: 'info'});
				return false;
			}	
			var json_ids = JSON.stringify(ids);
			$.post("<?php echo site_url('orders_list/ajxLock'); ?>",
					   {ids:json_ids},
					   function(data){
								if(data){
									location.reload(true);		
								}
						},"json"   
				   );
			return false;
		});

		//Unlocks the Orders
		$("span.unlock").on("click",function(){

			var ids = $(".order_check:checked").map(function(i,n) {
		        return $(n).val();
		    }).get();
			if(ids.length == 0)
			{
				$.pnotify({pnotify_text:"Нема селектирани ставки!",pnotify_type: 'info'});
				return false;
			}	
			var json_ids = JSON.stringify(ids);

			$.post("<?php echo site_url('orders_list/ajxUnlock'); ?>",
					   {ids:json_ids},
					   function(data){
								if(data){
								   location.reload(true);		
								}
						},"json");
			return false;
		});

	});

</script>