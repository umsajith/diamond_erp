<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkDeleteButton("payroll/delete/{$master->id}")?>
    <?=uif::linkButton("payroll/payroll_pdf/{$master->id}",'icon-print','info')?>
	<hr>
<div class="row-fluid">
	<div class="span4 well well-small">
		<dl class="dl-horizontal">
			<dt><?=uif::lng('attr.employee')?>:</dt>
			<dd><?=anchor("employees/view/{$master->eid}",$master->fname . ' ' . $master->lname)?></dd>
			<dt><?=uif::lng('attr.month')?>:</dt>
			<dd><?=uif::date($master->date_from,'%m/%Y')?></dd>
			<dt><?=uif::lng('attr.date_from')?>:</dt>
			<dd><?=uif::date($master->date_from)?></dd>
			<dt><?=uif::lng('attr.date_to')?>:</dt>
			<dd><?=uif::date($master->date_to)?></dd>
			<?php if($master->acc_wage != 0):?>
				<dt><?=uif::lng('attr.accumulated')?>:</dt>
				<dd><strong><?=$master->acc_wage.$glCurrSh?></strong></dd>
			<?php endif;?>
			<?php if($master->fixed_wage_only == 1):?>
				<dt><?=uif::lng('attr.fixed_wage')?>:</dt>
				<dd><?='+'.$master->fixed_wage.$glCurrSh?></dd>
			<?php endif;?>
			<dt><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?>:</dt>
			<dd><?='+' . $master->social_cont?></dd>
			<dt><?=uif::lng('attr.subvention')?>:</dt>
			<dd><?='+'.$master->comp_mobile_sub?></dd>
			<dt><?=uif::lng('attr.bonuses')?>:</dt>
			<dd><?='+'.$master->bonuses?></dd>
			<dt><?=uif::lng('attr.gross')?>:</dt>
			<dd><strong><?=$master->gross_wage.$glCurrSh?></strong></dd>
			<?php if($master->fixed_wage AND !$master->fixed_wage_only):?>
				<dt><?=uif::lng('attr.fixed')?>:</dt>
				<dd><?='-'.$master->fixed_wage?></dd>
			<?php endif;?>
			<?php if($master->social_cont):?>
				<dt><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?>:</dt>
				<dd><?='-'.$master->social_cont?></dd>
			<?php endif;?>
			<dt><?=uif::lng('attr.expenses')?>:</dt>
			<dd><?=$master->expenses?></dd>
			<dt><?=uif::lng('attr.paid')?>:</dt>
			<dd><strong><?=$master->paid_wage.$glCurrSh?></strong></dd>
			<dt><?=uif::lng('attr.code')?>:</dt>
			<dd><?=$master->code;?></dd>
		</dl>
	</div>
<div class="span8">
	<div class="legend"><?=uif::lng('app.payroll_detailed_view')?></div>
<!-- ======================================JOB ORDERS EMPLOYEES ONLY====================================== -->
	<?php if (isset($results) AND is_array($results) AND count($results)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th><?=uif::lng('attr.task')?></th>
				<th><?=uif::lng('app.job_jobs')?></th>
				<th><?=uif::lng('attr.total')?></th>
				<th><?=uif::lng('attr.average')?></th>
				<th><?=uif::lng('attr.unit_price')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($results as $row):?>
			<tr>
				<td><?=$row->taskname;?></td>
				<td><?=$row->count;?></td>
				<td><?=$row->final_quantity.' '.$row->uname;?></td>
				<td><?=round($row->final_quantity / $row->count,2).' '.$row->uname;?></td>
				<td><?=$row->calculation_rate.$glCurrSh?></td>
				<td><?=$row->calculation_rate * $row->final_quantity?></td>	
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
<!-- JOB ORDERS WAGE CALCUALTION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong><?=uif::lng('attr.accumulated', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($master->acc_wage).$glCurrSh?></strong>
		</div>
	</div>
<!-- JOB ORDERS WAGE CALCUALTION END -->
	<?php endif;?>

<!-- ======================================FIXED WAGE ONLY EMPLYOEES====================================== -->
	<?php if(isset($master->fixed_wage_only) AND $master->fixed_wage_only):?>
		<!-- IF EMPLOYEE ON FIXED WAGE ONLY -->
		<?php if(isset($master->fixed_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong><?=uif::lng('attr.fixed_wage', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($master->fixed_wage).$glCurrSh?></strong>
			</div>
		</div>	
		<?php endif;?>
	<?php endif;?>	
<!-- ======================================FOR DISTRIBUTORS====================================== -->
	<?php if(isset($distribution) AND is_array($distribution)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th><?=uif::lng('attr.item')?></th>
				<th><?=uif::lng('attr.category')?></th>
				<th><?=uif::lng('attr.quantity')?></th>
				<th><?=uif::lng('attr.commision')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($distribution as $row):?>
			<tr>
				<td><?=$row->prodname;?></td>
				<td><?=$row->pcname;?></td>
				<td><?=$row->quantity . ' ' . $row->uname;?></td>
				<td><?=$row->commision_rate.$glCurrSh;?></td>
				<td><?=round($row->quantity * $row->commision_rate,2)?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<!-- ACCUMULATIVE WAGE CALCUATION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong><?=uif::lng('attr.accumulated', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($master->acc_wage).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif;?>

<!-- ======================================GENEARAL CALCULATIONS====================================== -->
<?php if($master->social_cont>0 OR $master->comp_mobile_sub>0 OR 
(isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus))  ):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th class="span11"><?=uif::lng('attr.category')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
<!-- SOCIAL CONTRIBUTION -->
		<tbody>
		<?php if($master->social_cont>0):?>
			<tr>
				<td><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?></td>
				<td><?=$master->social_cont?></td>
			</tr>
		<?php endif;?>
<!-- COMPANY MOBILE SUBSIDY -->
		<?php if($master->comp_mobile_sub>0):?>
			<tr>
				<td><?=uif::lng('attr.subvention')?></td>
				<td><?=$master->comp_mobile_sub;?></td>		
			</tr>
		<?php endif;?>
<!-- BONUSES -->
	<?php if (isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus)):?>
		<?php foreach($extras_plus as $row):?>
			<tr>
				<td><?=$row->name?></td>
				<td><?=$row->amount?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	<?php endif;?>
	</table>
	<!-- TOTAL BONUSES -->
	<?php if(isset($master->bonuses)):?>
	<div class="row-fluid">
		<div class="span12 alert">
			<strong><?=uif::lng('attr.bonuses', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($master->bonuses + $master->comp_mobile_sub + $master->social_cont).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif;?>
<?php endif;?>
<!-- BRUTO WAGE CALCUALTION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong>Б<?=uif::lng('attr.gross', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($master->gross_wage).$glCurrSh?></strong>
		</div>
	</div>	
<!-- BRUTO WAGE CALCULATION END -->

	<?php if (isset($extras_minus) AND is_array($extras_minus) AND count($extras_minus)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th class="span11"><?=uif::lng('attr.category')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
<!-- FIXED WAGE -->
	<?php if($master->fixed_wage AND !$master->fixed_wage_only):?>
		<tbody>
			<tr>
				<td><?=uif::lng('attr.fixed_wage')?></td>
				<td><?='-'.$master->fixed_wage;?></td>
			</tr>
	<?php endif;?>

<!-- SOCIAL CONTRIBUTION -->
	<?php if($master->social_cont>0):?>
		<tr>
			<td><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?></td>
			<td><?='-' . $master->social_cont;?></td>
		</tr>
	<?php endif;?>

<!-- EXPENSES -->
	<?php foreach($extras_minus as $row):?>
		<tr>
			<td><?=$row->name;?></td>
			<td><?=$row->amount?></td>
		</tr>
	<?php endforeach;?>
		</tbody>
	<?php endif;?>
	</table>
	<?php if (isset($master->fixed_wage) OR isset($master->expenses)):?>
	<div class="row-fluid">
		<div class="span12 alert">
			<strong><?=uif::lng('attr.expenses', MB_CASE_UPPER)?>:</strong>
			<?php 
				$gross_exp = $master->expenses;
				if(!$master->fixed_wage_only)
				{
					$gross_exp -= $master->fixed_wage;
				}
				$gross_exp -= $master->social_cont;
			?>
			<strong class="pull-right"><?=uif::cf($gross_exp).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif;?>
	<!-- PAID WAGE -->
	<?php if (isset($master->paid_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-success">
				<strong><?=uif::lng('attr.paid', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($master->paid_wage).$glCurrSh?></strong>
			</div>
		</div>
	<?php endif;?>
	</div>
</div>