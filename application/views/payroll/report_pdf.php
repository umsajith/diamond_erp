<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="<?php echo base_url('js/jquery.js'); ?>"></script>
<style media="all">
* {
	padding:0px;
	margin:0px;
	outline:0px;
}

@page {

	margin-top: 50px;
}
h1 {
	width: 100%;
	text-align: center;
	margin: 0 auto;
}
body {
	font-family: 'verdana';
	font-size: 9pt;
}

table#payroll {
 	width: 90%;
 	margin: 0 auto;
 	margin-top: 25px;
 	border-collapse: collapse;
 	font-size: 7pt;
}

table#payroll th,td.header {
	text-align: center;
 	background-color: #5F7C9C;
 	color: white;
 	border: thin solid #5F7C9C;
 	font-weight: bold;
}
table#payroll td {
 	border: thin solid #5F7C9C;
 	padding-left: 15px;
}
table#payroll td.strong {
 	font-weight: bold;
}
div#period {
	width: 90%;
	margin: 0 auto;
	text-align: left;
	margin-top: 30px;
}
span#label{
	font-weight: bold;
}
</style>

<div id="period">
	<h1>Рипорт на Плати</h1>
	<span id="label">Од:</span> <?php echo $date_from;?>
	<span id="label">До:</span> <?php echo $date_to; ?><br/>
	<span id="label">Работник:</span> <?php echo (isset($employee))?$employee->fname.' '.$employee->lname:'-'; ?><br/>
</div>

<table id="payroll"> 
<?php if (isset($results) AND count($results) > 0):?>
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
			<td><?php echo 'Учинок';?></td>
			<td><?php echo $results->sum_acc_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_acc_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_acc_wage. $G_currency;?></td>
			<td><?php echo $results->min_acc_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Придонес';?></td>
			<td><?php echo $results->sum_social_cont. $G_currency;?></td>
			<td><?php echo round($results->avg_social_cont,2). $G_currency;?></td>
			<td><?php echo $results->max_social_cont. $G_currency;?></td>
			<td><?php echo $results->min_social_cont. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Додатоци';?></td>
			<td><?php echo $results->sum_bonuses. $G_currency;?></td>
			<td><?php echo round($results->avg_bonuses,2). $G_currency;?></td>
			<td><?php echo $results->max_bonuses. $G_currency;?></td>
			<td><?php echo $results->min_bonuses. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Бруто';?></td>
			<td><?php echo $results->sum_gross_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_gross_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_gross_wage. $G_currency;?></td>
			<td><?php echo $results->min_gross_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Трошоци';?></td>
			<td><?php echo $results->sum_expenses. $G_currency;?></td>
			<td><?php echo round($results->avg_expenses,2). $G_currency;?></td>
			<td><?php echo $results->max_expenses. $G_currency;?></td>
			<td><?php echo $results->min_expenses. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Фиксна Плата';?></td>
			<td><?php echo $results->sum_fixed_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_fixed_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_fixed_wage. $G_currency;?></td>
			<td><?php echo $results->min_fixed_wage. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Тел. Субвенција';?></td>
			<td><?php echo $results->sum_comp_mobile_sub. $G_currency;?></td>
			<td><?php echo round($results->avg_comp_mobile_sub,2). $G_currency;?></td>
			<td><?php echo $results->max_comp_mobile_sub. $G_currency;?></td>
			<td><?php echo $results->min_comp_mobile_sub. $G_currency;?></td>
		</tr>
		<tr>
			<td><?php echo 'Доплата';?></td>
			<td><?php echo $results->sum_paid_wage. $G_currency;?></td>
			<td><?php echo round($results->avg_paid_wage,2). $G_currency;?></td>
			<td><?php echo $results->max_paid_wage. $G_currency;?></td>
			<td><?php echo $results->min_paid_wage. $G_currency;?></td>
		</tr>
<?php else:?>
	<?php if(empty($results)):?>
		<?php $this->load->view('includes/_no_records');?>
	<?php endif;?>
<?php endif;?>
</tbody>
</table>
<div id="container" style="margin: 0"></div>