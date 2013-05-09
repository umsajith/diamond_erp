<?=uif::contentHeader($heading)?>
	<?=form_open("distribution/edit/{$page}/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','dateoforigin',$result)?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity',$result)?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?php if(in_array($page,['out','ret'])):?>
			<?=uif::controlGroup('dropdown',':attr.distributor','distributor_fk',[$distributors,$result])?>
		<?php endif;?>
		<?=uif::controlGroup('text',':attr.document','ext_doc',$result)?>
		<?=uif::controlGroup('textarea',':attr.note','note',$result)?>
		<?=form_hidden('prodname_fk',$result->prodname_fk)?>
		<?=form_hidden('id',$result->id)?>
	<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		$("select").select2();
		cd.datepicker('input[name=dateoforigin]');
		var options = {select : "#products", aux1 : "#uom", aux2 : "#category", 
		args: {salable : 1}, prodname_fk : "<?=$result->prodname_fk?>"};
        cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options); 		
	});
</script>