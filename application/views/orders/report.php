<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-cog','success','onClick=cd.doReport("form#report")')?>
		<?=uif::button('icon-print','info',
		'onClick=cd.generatePdf("'.site_url('orders/report_pdf').'","form#report") id="generate-pdf"')?>
	</div>
</div>
<hr>
<div class="row-fluid">
	<div class="span4 well">
	<?=form_open('orders/report',"id='report'")?>
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','','datefrom','','placeholder="Од"')?>
		<?=uif::controlGroup('datepicker','','dateto','','placeholder="До"')?>
		<?=uif::controlGroup('dropdown','','distributor_fk',[$distributors])?>
		<?=uif::controlGroup('dropdown','','partner_fk',[$customers])?>
		<?=uif::controlGroup('dropdown','','payment_mode_fk',[$modes_payment])?>
	<?=form_close()?>
	</div>
	<div class="span8">
		<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
		<table class="table table-stripped table-condensed table-hover tablesorter" id="report-table">
			<thead>
				<tr>
			    	<th>Производ</th>
			    	<th>Земено</th>
			    	<th>Вратено</th>   
			    	<th>% Вратено</th>    	
		    	</tr>
		    </thead>
		    <tbody>
			<?php foreach($results as $row):?>
				<tr>
					<td><?=$row->prodname?></td>
					<td><?=$row->quantity.' '.$row->uname?></td>
					<td><?=$row->returned_quantity.' '.$row->uname?></td>
					<td><?=round($row->returned_quantity/$row->quantity,3).' %'?></td>
				</tr>
			<?php endforeach; ?>
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

