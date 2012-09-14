<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>
	<div id="buttons">
		<a href="<?php echo site_url('products/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('products/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
<hr>
	<dl>
        <dt>Product:</dt>
        <dd><?php echo $master->prodname;?></dd>
        <dt>Code:</dt>
        <dd><?php echo ($master->code!='')?$master->code:'-';?></dd>
        <dt>Product Type:</dt>
        <dd><?php echo $master->ptname;?></dd>
		<dt>Product Category:</dt>
        <dd><?php echo $master->pcname;?></dd>
        <dt>Warehouse:</dt>
        <dd><?php echo $master->wname;?></dd>
        <dt>Base Unit:</dt>
        <dd><?php echo $master->base_unit . ' ' . $master->uname;?></dd>
        <dt>Retail Price:</dt>
        <dd><?php echo $master->retail_price;?></dd>
        <dt>Wholesale Price 1:</dt>
        <dd><?php echo $master->whole_price1;?></dd>
       <dt>Wholesale Price 2:</dt>
        <dd><?php echo $master->whole_price2;?></dd>
        <dt>Tax Rate:</dt>
        <dd><?php echo $master->rate.'%';?></dd>
        <dt>Commision:</dt>
        <dd><?php echo $master->commision;?></dd>
        <dt>Salable:</dt>
        <dd><?php echo ($master->salable==1)?'Yes':'No';?></dd>
       	<dt>Purchasable:</dt>
        <dd><?php echo ($master->purchasable==1)?'Yes':'No';?></dd>
        <dt>Stockable:</dt>
        <dd><?php echo ($master->stockable==1)?'Yes':'No';?></dd>
        <dt>Alert Qty.:</dt>
        <dd><?php echo $master->alert_quantity. ' ' . $master->uname;?></dd>  
        <dt>Status:</dt>
        <dd><?php echo $master->status;?></dd>
        <dt>Date of Entry:</dt>
        <dd><?php echo $master->dateofentry;?></dd>   
	</dl>

<hr>
<?php $this->load->view('includes/_del_dialog');?>