<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-plus','danger',
		'onClick=cd.insertPayroll("'.site_url('payroll/insert').'") id="insert-payroll" disabled')?>
	</div>
</div>
<hr>
<div class="row-fluid">
	<div class="span4 well">
	<?=form_open('payroll/calculate')?>
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','','datefrom','','placeholder="'.uif::lng('attr.date_from').'"')?>
			<?=uif::controlGroup('datepicker','','dateto','','placeholder="'.uif::lng('attr.date_to').'"')?>
		<?=uif::controlGroup('dropdown','','employee',[$employees])?>
		<?=uif::button('icon-cog btn-large','success','type="submit"');?>
	<?=form_close()?>
	</div>
	<div class="span8">
	<!-- ======================================JOB ORDERS EMPLOYEES ONLY====================================== -->
	<?php if(isset($fixed_wage_only) AND !$fixed_wage_only):?>
	<div class="legend"><?=uif::lng('app.payroll_detailed_view')?></div>
		<?php if (isset($job_orders) AND is_array($job_orders) AND count($job_orders)):?>
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
			<?php foreach($job_orders as $row):?>
				<tr>
					<td><?=$row->taskname;?></td>
					<td><?=$row->count;?></td>
					<td><?=$row->final_quantity.' '.$row->uname;?></td>
					<td><?=round($row->final_quantity / $row->count,2).' '.$row->uname;?></td>
					<td><?=$row->rate_per_unit.$glCurrSh?></td>
					<td><?=round($row->rate_per_unit * $row->final_quantity,2)?></tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<!-- JOB ORDERS WAGE CALCUALTION -->
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong><?=uif::lng('attr.accumulated', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($acc_wage).$glCurrSh?></strong>
			</div>
		</div>
	<!-- JOB ORDERS WAGE CALCUALTION END -->
		<?php endif;?>
	<?php endif;?>
	<!-- ======================================FIXED WAGE ONLY EMPLYOEES====================================== -->
	<?php if(isset($fixed_wage_only) AND $fixed_wage_only):?>
	<div class="legend"><?=uif::lng('app.payroll_detailed_view')?></div>
		<!-- IF EMPLOYEE ON FIXED WAGE ONLY -->
		<?php if(isset($fixed_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong><?=uif::lng('attr.fixed_wage', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($fixed_wage).$glCurrSh?></strong>
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
					<td><?=$row->prodname?></td>
					<td><?=$row->pcname?></td>
					<td><?=$row->quantity . ' ' . $row->uname?></td>
					<td><?=$row->commision.$glCurrSh?></td>
					<td><?=round($row->quantity * $row->commision,2)?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<!-- ACCUMULATIVE WAGE CALCUATION -->
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong><?=uif::lng('attr.accumulated', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($acc_wage).$glCurrSh?></strong>
			</div>
		</div>	
	<?php endif;?>

	<!-- ======================================GENEARAL CALCULATIONS====================================== -->
	<?php if(isset($extras_plus) AND count($extras_plus) 
	OR (isset($social_cont) AND $social_cont>0 ) OR (isset($comp_mobile_sub) AND $comp_mobile_sub>0)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th class="span11"><?=uif::lng('attr.category')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
	<?php endif; ?>
	<!-- SOCIAL CONTRIBUTION -->
		<tbody>
		<?php if(isset($social_cont) AND $social_cont>0 ):?>
			<tr>
				<td><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?></td>
				<td><?=$social_cont;?></td>
			</tr>
		<?php endif;?>

		<!-- COMPANY MOBILE SUBSIDY -->
		<?php if(isset($comp_mobile_sub) AND $comp_mobile_sub>0):?>
			<tr>
				<td><?=uif::lng('attr.subvention')?></td>
				<td><?=$comp_mobile_sub;?></td>	
			</tr>	
		<?php endif;?>

		<!-- BONUSES -->
		<?php if (isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus)):?>
			<?php foreach($extras_plus as $row):?>
				<tr>
					<td><?=$row->name;?></td>
					<td><?=$row->amount;?></td>
				</tr>
			<?php endforeach;?>
		<?php endif;?>
		</tbody>	
	</table>
	<!-- TOTAL BONUSES -->
	<?php if(isset($bonuses)):?>
	<div class="row-fluid">
		<div class="span12 alert">
			<strong><?=uif::lng('attr.bonuses', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($bonuses + $comp_mobile_sub + $social_cont).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif;?>

	<!-- BRUTO WAGE CALCUALTION -->
	<?php if(isset($gross_wage)): ?>
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong><?=uif::lng('attr.gross', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($gross_wage).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif; ?>
	<!-- BRUTO WAGE CALCULATION END -->
	<?php if(isset($extras_minus) AND count($extras_minus) OR (isset($social_cont) AND $social_cont > 0) OR 
	(isset($fixed_wage) AND $fixed_wage > 0 AND !$fixed_wage_only)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th class="span11"><?=uif::lng('attr.category')?></th>
				<th><?=uif::lng('attr.amount')?></th>
			</tr>
		</thead>
	<?php endif; ?>
	<!-- FIXED WAGE -->
		<tbody>
		<?php if(isset($fixed_wage) AND $fixed_wage > 0 AND !$fixed_wage_only):?>
		<tr>
			<td><?=uif::lng('attr.fixed_wage')?></td>
			<td><?='-'.$fixed_wage;?></td>		
		</tr>
		<?php endif;?>

	<!-- SOCIAL CONTRIBUTION -->
		<?php if(isset($social_cont) AND $social_cont > 0):?>
		<tr>
			<td><?=uif::lng('attr.social_contribution')?> + <?=uif::lng('attr.health_insurance')?></td>
			<td><?='-' . $social_cont;?></td>
		</tr>
		<?php endif;?>

	<!-- EXPENSES -->
		<?php if (isset($extras_minus) AND is_array($extras_minus) AND count($extras_minus)):?>
			<?php foreach($extras_minus as $row):?>
				<tr>
					<td><?=$row->name;?></td>
					<td><?=$row->amount;?></td>	
				</tr>
			<?php endforeach;?>
		<?php endif;?>
		</tbody>
	</table>

	<?php if(isset($gross_exp)):?>
	<div class="row-fluid">
		<div class="span12 alert">
			<strong><?=uif::lng('attr.expenses', MB_CASE_UPPER)?>:</strong>
			<strong class="pull-right"><?=uif::cf($gross_exp).$glCurrSh?></strong>
		</div>
	</div>
	<?php endif;?>

	<!-- PAID WAGE -->
	<?php if(isset($paid_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-success">
				<strong><?=uif::lng('attr.paid', MB_CASE_UPPER)?>:</strong>
				<strong class="pull-right"><?=uif::cf($paid_wage).$glCurrSh?></strong>
			</div>
		</div>
	<?php endif;?>

	<?php if($submited):?>
	<?=form_open('','id="payroll-data"')?>
		<?=form_hidden('employee_fk',set_value('employee_fk',$employee))?>
		<?=form_hidden('date_from',set_value('date_from',$datefrom))?>
		<?=form_hidden('date_to',set_value('date_to',$dateto))?>
		
		<?=form_hidden('acc_wage',set_value('acc_wage',$acc_wage))?>
		<?=form_hidden('social_cont',set_value('acc_wage',$social_cont))?>
		<?=form_hidden('comp_mobile_sub',set_value('acc_wage',$comp_mobile_sub))?>
		<?=form_hidden('bonuses',set_value('acc_wage',$bonuses))?>
		<?=form_hidden('gross_wage',set_value('gross_wage',$gross_wage))?>
		<?=form_hidden('fixed_wage',set_value('fixed_wage',$fixed_wage))?>
		<?=form_hidden('expenses',set_value('expenses',$expenses))?>
		<?=form_hidden('paid_wage',set_value('paid_wage',$paid_wage))?>
		<?=form_hidden('fixed_wage_only',set_value('fixed_wage_only',$fixed_wage_only))?>
		
		<?=form_hidden('is_distributer',set_value('is_distributer',$is_distributer))?>
	<?=form_close()?>
	<?php endif;?>
	</div>
</div>

<script>
	$(function() 
	{
		cd.dd("select[name=employee]","<?=uif::lng('attr.employee')?>");
		cd.dateRange('input[name=datefrom]','input[name=dateto]');
		if(<?=$submited?>){ $("#insert-payroll").prop('disabled', false); }
	});
</script>