<?=uif::contentHeader($heading)?>
	<?=form_open('distribution/insert_inbound','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker',':attr.date','dateoforigin',uif::today())?>
		<?=uif::controlGroup('dropdown',':attr.item','prodname_fk',[],'id="products"')?>
		<?=uif::controlGroup('text',':attr.quantity','quantity')?>
		<?=uif::controlGroup('text',':attr.uom','','','id="uom" disabled')?>
		<?=uif::controlGroup('text',':attr.category','','','id="category" disabled')?>
		<?=uif::controlGroup('text',':attr.document','ext_doc')?>
		<?=uif::controlGroup('textarea',':attr.note','note')?>
		<?=form_hidden('inserted_by',$this->session->userdata('userid'));?>
		<?=form_close()?>
	</div>
</div>
<script>	
	$(function() {
		cd.datepicker("input[name=dateoforigin]");
		$("select").select2();
		var options = {select : "#products", aux1 : "#uom", aux2 : "#category", args: {salable : 1}};
        cd.ddProducts("<?=site_url('products/ajxGetProducts')?>",options); 	
	});
</script>