<script type="text/javascript">

$(function(){
	$("#ptypes_fg").flexigrid({
		url: "<?php echo site_url('tproduct/grid');?>",
		dataType: 'json',
		colModel : [
			{display: 'ID', name : 'id', width : 44, sortable : true, align: 'center'},
			{display: 'Назив', name : 'ptname', width : 500, sortable : true, align: 'left'}
			],
		buttons : [
			{name: 'Внес', bclass: 'add_fg', onpress : add_record},
			{name: 'Измени', bclass: 'edit_fg', onpress : edit_record},
			{name: 'Бриши', bclass: 'delete_fg', onpress : delete_record},
			{separator: true}
			],
		searchitems : [
			{display: 'Name', name : 'ptname', isdefault: true}
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
		var dialog = $("#add_record");
		var input = $("input[name=ptname]");
		dialog.dialog({
			modal: true,
			width: "265",
			height: "120",
			resizable: false,
			draggable: false,
			buttons:{ 
					"Сними": function() {
						var ptname = input.val();
						$.post("<?php echo site_url('tproduct/insert');?>",{ptname:ptname},
								function(data){
									$("#ptypes_fg").flexReload();
									dialog.dialog("close");
									input.val('');
						})},
					"Откажи": function() { dialog.dialog("close"); }
			 }	
		});	
		
	}
	
	function edit_record(){

	}
	
	function delete_record(){
		var grid = $("#ptypes_fg");
		var id = $('.trSelected',grid).attr('id').substr(3);
		$.post("<?php echo site_url('tproduct/delete');?>",{id:id},
				function(data){
					grid.flexReload();
		});	
	}
});
</script>

<div id="add_record" style="display: none;" title="Внес на Тип на Артикл">
	<?php echo form_label('Назив:');?>
    <?php echo form_input('ptname');?>
</div>

<h2><?php echo $heading; ?></h2>

<hr/>

<table id="ptypes_fg"></table>