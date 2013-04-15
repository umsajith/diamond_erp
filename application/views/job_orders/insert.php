<?=uif::contentHeader($heading)?>
<?=form_open('job_orders/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Датум','datedue')?>
		<?=uif::controlGroup('dropdown','Работник','assigned_to',[$employees],'id="employee"')?>
		<?=uif::controlGroup('dropdown','Работна Задача','task_fk',[],'id="tasks" disabled')?>
		<?=uif::controlGroup('text','Количина','assigned_quantity')?>
		<?=uif::controlGroup('text','Растур','defect_quantity')?>
		<?=uif::controlGroup('text','ЕМ','','','id="uname" disabled')?>
		<?=uif::controlGroup('text','Работни Часови','work_hours')?>
		<div class="control-group">
			<label class="control-label">Смена</label>
			<div class="controls">
				<label class="radio">
					1<?=form_radio('shift','1',false);?>
				</label>
				<label class="radio">
					2<?=form_radio('shift','2',false);?>
				</label>
				<label class="radio">
					3<?=form_radio('shift','3',false);?>
				</label>
			</div>
		</div>
		<?=uif::controlGroup('textarea','Забелешка','description')?>
		<?=form_hidden('task_fk')?>
	<?=form_close()?>
	</div>
	<div class="span6">
		<?=uif::load('_last_job_order_view','job_orders')?>
	</div>
</div>
<script>
	$(function() {

		$("#employee").select2();
		$("#tasks").select2({placeholder: "- Работна Задача -",allowClear: true});

		cd.dropdownTasks("<?=site_url('employees/ajxGetTasks')?>");

		$("input[name=datedue]").datepicker({
			dateFormat: "yy-mm-dd",
			maxDate: +0
		});

	});
</script>