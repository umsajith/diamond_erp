<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkDeleteButton("payroll/delete/{$master->id}")?>
    <?=uif::linkButton("payroll/payroll_pdf/{$master->id}",'icon-print','info')?>
	<hr>
<div class="row-fluid">
	<div class="span5 well well-small">
		<dl class="dl-horizontal">
			<dt>Работник:</dt>
			<dd><?=anchor("employees/view/{$master->eid}",$master->fname . ' ' . $master->lname)?></dd>
			<dt>Месец:</dt>
			<dd><?=uif::date($master->date_from,'%m/%Y')?></dd>
			<dt>Од Датум:</dt>
			<dd><?=uif::date($master->date_from)?></dd>
			<dt>До Датум:</dt>
			<dd><?=uif::date($master->date_to)?></dd>
			<?php if($master->acc_wage != 0):?>
				<dt>Учинок:</dt>
				<dd><strong><?=$master->acc_wage?></strong></dd>
			<?php endif;?>
			<?php if($master->fixed_wage_only == 1):?>
				<dt>Фиксна Плата:</dt>
				<dd><?='+'.$master->fixed_wage?></dd>
			<?php endif;?>
			<dt>Придонеси + Здр.:</dt>
			<dd><?='+' . $master->social_cont?></dd>
			<dt>Тел.Субвенција:</dt>
			<dd><?='+'.$master->comp_mobile_sub?></dd>
			<dt>Бонуси:</dt>
			<dd><?='+'.$master->bonuses?></dd>
			<dt>Бруто:</dt>
			<dd><strong><?=$master->gross_wage?></strong></dd>
			<?php if($master->fixed_wage AND !$master->fixed_wage_only):?>
				<dt>Плата на сметка:</dt>
				<dd><?='-'.$master->fixed_wage;?></dd>
			<?php endif;?>
			<?php if($master->social_cont):?>
				<dt>Придонеси + Здр.:</dt>
				<dd><?='-'.$master->social_cont;?></dd>
			<?php endif;?>
			<dt>Трошоци:</dt>
			<dd><?=$master->expenses?></dd>
			<dt>Доплата:</dt>
			<dd><strong><?=$master->paid_wage?></strong></dd>
			<dt>Код:</dt>
			<dd><?=$master->code;?></dd>
		</dl>
	</div>
<!-- ======================================JOB ORDERS EMPLOYEES ONLY====================================== -->
<div class="span7">
	<?php if (isset($results) AND is_array($results) AND count($results)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Работна Задача</th>
				<th>Работни Налози</th>
				<th>Вкупно</th>
				<th>Цена</th>
				<th>Износ</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($results as $row):?>
			<tr>
				<td><?=$row->taskname;?></td>
				<td><?=$row->count;?></td>
				<td><?=$row->final_quantity.' '.$row->uname;?></td>
				<td><?=$row->calculation_rate;?></td>
				<td><?=$row->calculation_rate * $row->final_quantity;?></td>	
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
<!-- JOB ORDERS WAGE CALCUALTION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong>ПЛАТА ПО УЧИНОК:</strong>
			<strong class="pull-right"><?=$master->acc_wage;?></strong>
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
				<strong>ФИКСНА ПЛАТА:</strong>
				<strong class="pull-right"><?=$master->fixed_wage;?></strong>
			</div>
		</div>	
		<?php endif;?>
	<?php endif;?>	
<!-- ======================================FOR DISTRIBUTORS====================================== -->
	<?php if(isset($distribution) AND is_array($distribution)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Производ</th>
				<th>Категорија</th>
				<th>Вкупно</th>
				<th>Провизија</th>
				<th>Износ</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($distribution as $row):?>
			<tr>
				<td><?=$row->prodname;?></td>
				<td><?=$row->pcname;?></td>
				<td><?=$row->quantity . ' ' . $row->uname;?></td>
				<td><?=$row->commision_rate.$G_currency;?></td>
				<td><?=round($row->quantity * $row->commision_rate,2);?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<!-- ACCUMULATIVE WAGE CALCUATION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong>ПЛАТА ПО УЧИНОК:</strong>
			<strong class="pull-right"><?=$master->acc_wage;?></strong>
		</div>
	</div>
	<?php endif;?>

<!-- ======================================GENEARAL CALCULATIONS====================================== -->
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Категорија</th>
				<th>Износ</th>
			</tr>
		</thead>
<!-- SOCIAL CONTRIBUTION -->
		<tbody>
		<?php if($master->social_cont>0):?>
			<tr>
				<td>Придонеси + Здравствено Осигурување</td>
				<td><?=$master->social_cont?></td>
			</tr>
		<?php endif;?>
<!-- COMPANY MOBILE SUBSIDY -->
		<?php if($master->comp_mobile_sub>0):?>
			<tr>
				<td>Телефонска Субвенција</td>
				<td><?=$master->comp_mobile_sub;?></td>		
			</tr>
		<?php endif;?>
<!-- BONUSES -->
	<?php if (isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus)):?>
		<?php foreach($extras_plus as $row):?>
			<tr>
				<td><?=$row->name;?></td>
				<td><?=$row->amount?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	<?php endif;?>
	</table>

<!-- BRUTO WAGE CALCUALTION -->
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong>БРУТО ПЛАТА:</strong>
			<strong class="pull-right"><?=$master->gross_wage;?></strong>
		</div>
	</div>	
<!-- BRUTO WAGE CALCULATION END -->

	<?php if (isset($extras_minus) AND is_array($extras_minus) AND count($extras_minus)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Категорија</th>
				<th>Износ</th>
			</tr>
		</thead>
<!-- FIXED WAGE -->
	<?php if($master->fixed_wage AND !$master->fixed_wage_only):?>
		<tbody>
			<tr>
				<td>Фиксна Плата на Сметка</td>
				<td><?='-'.$master->fixed_wage;?></td>
			</tr>
	<?php endif;?>

<!-- SOCIAL CONTRIBUTION -->
	<?php if($master->social_cont>0):?>
		<tr>
			<td>Придонеси + Здравствено Осигурување</td>
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
			<strong>ТРОШОЦИ:</strong>
			<?php 
				$gross_exp = $master->expenses;
				if(!$master->fixed_wage_only)
				{
					$gross_exp -= $master->fixed_wage;
				}
				$gross_exp -= $master->social_cont;
			?>
			<strong class="pull-right"><?=$gross_exp;?></strong>
		</div>
	</div>
	<?php endif;?>
	<!-- PAID WAGE -->
	<?php if (isset($master->paid_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-success">
				<strong>ДОПЛАТА:</strong>
				<strong class="pull-right"><?=$master->paid_wage?></strong>
			</div>
		</div>
	<?php endif;?>
	</div>
</div>