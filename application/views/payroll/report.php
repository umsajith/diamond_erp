<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-cog','success','onClick=cd.submit("form#report")')?>
		<?=uif::button('icon-print','info',
		'onClick=cd.generatePdf("'.site_url('payroll/report_pdf').'","form#report") id="generate-pdf"')?>
	</div>
</div>
<hr>
<div class="row-fluid">
	<div class="span4 well">
		<?=form_open('payroll/report',"id='report'")?>
			<?=uif::load('_validation')?>
			<?=uif::controlGroup('datepicker','','date_from','','placeholder="Од"')?>
			<?=uif::controlGroup('datepicker','','date_to','','placeholder="До"')?>
			<?=uif::controlGroup('dropdown','','employee_fk',[$employees])?>
		<?=form_close()?>
	</div>
	<div class="span8">
	<?php if (isset($results) AND count($results)):?>
		<table class="table table-stripped table-condensed table-hover tablesorter" id="report-table">
			<thead>
				<tr>
			    	<th>Ставка</th>
			    	<th>Вкупно</th>
			    	<th>Просек</th>
			    	<th>Максимум</th>
			    	<th>Минимум</th>    	
		    	</tr>
		    </thead>
		    <tbody>
				<tr>
					<td><?='Учинок'?></td>
					<td><?=$results->sum_acc_wage?></td>
					<td><?=round($results->avg_acc_wage,2)?></td>
					<td><?=$results->max_acc_wage?></td>
					<td><?=$results->min_acc_wage?></td>
				</tr>
				<tr>
					<td><?='Придонес'?></td>
					<td><?=$results->sum_social_cont?></td>
					<td><?=round($results->avg_social_cont,2)?></td>
					<td><?=$results->max_social_cont?></td>
					<td><?=$results->min_social_cont?></td>
				</tr>
				<tr>
					<td><?='Додатоци'?></td>
					<td><?=$results->sum_bonuses?></td>
					<td><?=round($results->avg_bonuses,2)?></td>
					<td><?=$results->max_bonuses?></td>
					<td><?=$results->min_bonuses?></td>
				</tr>
				<tr>
					<td><?='Бруто'?></td>
					<td><?=$results->sum_gross_wage?></td>
					<td><?=round($results->avg_gross_wage,2)?></td>
					<td><?=$results->max_gross_wage?></td>
					<td><?=$results->min_gross_wage?></td>
				</tr>
				<tr>
					<td><?='Трошоци'?></td>
					<td><?=$results->sum_expenses?></td>
					<td><?=round($results->avg_expenses,2)?></td>
					<td><?=$results->max_expenses?></td>
					<td><?=$results->min_expenses?></td>
				</tr>
				<tr>
					<td><?='Фиксна Плата'?></td>
					<td><?=$results->sum_fixed_wage?></td>
					<td><?=round($results->avg_fixed_wage,2)?></td>
					<td><?=$results->max_fixed_wage?></td>
					<td><?=$results->min_fixed_wage?></td>
				</tr>
				<tr>
					<td><?='Тел. Субвенција'?></td>
					<td><?=$results->sum_comp_mobile_sub?></td>
					<td><?=round($results->avg_comp_mobile_sub,2)?></td>
					<td><?=$results->max_comp_mobile_sub?></td>
					<td><?=$results->min_comp_mobile_sub?></td>
				</tr>
				<tr>
					<td><?='Доплата'?></td>
					<td><?=$results->sum_paid_wage?></td>
					<td><?=round($results->avg_paid_wage,2)?></td>
					<td><?=$results->max_paid_wage?></td>
					<td><?=$results->min_paid_wage?></td>
				</tr>
			</tbody>
		</table>
		<div class="alert alert-info">
			<i class="icon-info-sign"></i> Вредностите се изразени во парична единица:<strong><?=$glCurrLn?></strong>
		</div>
	<?php endif;?>
	</div>
</div>

<script>
	$(function() {

		cd.dd("select[name=employee_fk]",'Работник');

		cd.dateRange('input[name=date_from]','input[name=date_to]');

		var submited = '<?=$submited?>';
		if(submited == 0){
			$("#generate-pdf").hide();
		}
	});
</script>