<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-cog','success','onClick=cd.doReport("form#report")')?>
		<?=uif::button('icon-print','info',
		'onClick=cd.generatePdf("'.site_url('job_orders/report_pdf').'","form#report") id="generate-pdf"')?>
	</div>
</div>
<hr>
<div class="row-fluid">
	<div class="span4 well">
	<?=form_open('job_orders/report',"id='report'")?>
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','','datefrom','','placeholder="Од"')?>
		<?=uif::controlGroup('datepicker','','dateto','','placeholder="До"')?>
		<?=uif::controlGroup('dropdown','','assigned_to',[$employees])?>
		<?=uif::controlGroup('dropdown','','task_fk',[$tasks])?>
		<?=uif::controlGroup('checkbox','Смена','shift[]',[[1,2,3],''])?>
	<?=form_close()?>
	</div>
	<div class="span8">
		<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
		<table class="table table-stripped table-condensed table-hover tablesorter" id="report-table">
			<thead>
				<tr>
			    	<th>Работна Задача</th>
			    	<th>Вкупно</th>
			    	<th>Просек</th>
			    	<th>Максимум</th>
			    	<th>Минимум</th>
			    	<th>Р.Налози</th>    	
		    	</tr>
		    </thead>
		    <tbody>
			<?php foreach($results as $row):?>
				<tr>
					<td><?=$row->taskname.' ('.$row->uname.')'?></td>
					<td><?=$row->sum?></td>
					<td><?=round($row->avg,2)?></td>
					<td><?=$row->max?></td>
					<td><?=$row->min?></td>
					<td><?=$row->count;?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
		</table>
		<?php endif;?>
	</div>
</div>

<script>
	$(function() {
		$("select").select2();

		cd.dateRange('input[name=datefrom]','input[name=dateto]');

		var submited = '<?=$submited?>';
		if(submited == 0){
			$("#generate-pdf").hide();
		}
	});
</script>