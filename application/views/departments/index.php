<script type="text/javascript">

$(function(){
	$("#categories_fg").flexigrid({
		url: "<?php echo site_url('departments/grid');?>",
		dataType: 'json',
		colModel : [
			{display: 'ID', name : 'id', width : 44, sortable : true, align: 'center'},
			{display: 'ЕМ', name : 'department', width : 200, sortable : true, align: 'left'}
			],
		buttons : [
			{name: 'Внес', bclass: 'add_fg', onpress : add_record},
			{name: 'Измени', bclass: 'edit_fg', onpress : edit_record},
			{name: 'Бриши', bclass: 'delete_fg', onpress : delete_record},
			{separator: true}
			],
		searchitems : [
			{display: 'Name', name : 'wname', isdefault: true}
			],
		sortname: "id",
		sortorder: "asc",
		usepager: true,
		useRp: true,
		rp: 20,
		resizable: false,
		singleSelect: true
	});

	function add_record(){
		
	}
	function edit_record(){

	}
	function delete_record(){

	}
});
 

</script>
<h2><?php echo $heading; ?></h2>
<hr/>
<table id="categories_fg"></table>