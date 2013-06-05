<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-cog','success','onClick=cd.submit("form#report")')?>
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
			<?=uif::controlGroup('checkbox',':attr.shift','shift[]',[[1,2,3]])?>
		<?=form_close()?>
	</div>
	<div class="span8">
		<?php if (isset($results) AND is_array($results) AND count($results)):?>
		<table class="table table-stripped table-condensed table-hover tablesorter" id="report-table">
			<thead>
				<tr>
			    	<th><?=uif::lng('attr.task')?></th>
			    	<th><?=uif::lng('attr.total')?></th>
			    	<th><?=uif::lng('attr.average')?></th>
			    	<th><?=uif::lng('attr.max')?></th>
			    	<th><?=uif::lng('attr.min')?></th>
			    	<th><?=uif::lng('app.job_jobs')?></th>    	
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
					<td><?=$row->count?></td>
				</tr>
				<?php endforeach;?>
		</tbody>
		</table>
		<?php endif;?>
	</div>
</div>
<script>
	$(function() {

		$("#generate-pdf").hide();

		cd.dd("select[name=assigned_to]","<?=uif::lng('attr.employee')?>");
		cd.dd("select[name=task_fk]","<?=uif::lng('attr.task')?>");

		cd.dateRange('input[name=datefrom]','input[name=dateto]');
		
		//If report form has been submitted, show PDF icon
		<? if ($submitted):?>
			$("#generate-pdf").show();
		<? endif;?>
	});
</script>