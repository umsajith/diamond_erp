<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="<?php echo base_url('js/jquery.js'); ?>"></script>
<style>
* {
	padding:0px;
	margin:0px;
	outline:0px;
}
body {
	font-family: 'verdana';
	font-size: 9pt;
}
table#payroll {
	width: 95%;
 	margin: 0 auto;
 	margin-top: 15px;
 	border-collapse: collapse;
}

table#payroll th,td.header {
	text-align: center;
 	background-color: #5F7C9C;
 	color: white;
 	border: thin solid #5F7C9C;
 	font-weight: bold;
 	height: 25px;
}
table#payroll td {
 	border: thin solid #5F7C9C;
 	width: 100%;
 	padding-left: 15px;
}
table#payroll td.strong {
 	font-weight: bold;
}

</style>
<table id="payroll">
	<tr>
		<th colspan="2">Калкулација на плата</th>
	</tr>
	<tr>
		<td>Работник:</td>
		<td class="strong"><?php echo $master->fname.' '.$master->lname;?></td>
	</tr>
	<tr>
		<td>Код:</td>
		<td class="strong"><?php echo $master->code;?></td>
	</tr>
	<tr>
		<td class="header">Ставка</td>
		<td class="header">Износ</td>
	</tr>
	<?php if($master->acc_wage != 0):?>
		<tr>
			<td>Учинок:</td>
			<td class="strong"><?php echo $master->acc_wage;?></td>
		</tr>
	<?php endif;?>
	<?php if($master->fixed_wage_only == 1):?>
		<tr>
			<td>Фиксна Плата:</td>
			<td class="strong"><?php echo $master->fixed_wage;?></td>
		</tr>
	<?php endif;?>
	<tr>
		<td>Придонеси+Здравство:</td>
		<td><?php echo '+'. $master->social_cont;?></td>
	</tr>
	<tr>
		<td>Тел.Субвенција:</td>
		<td><?php echo '+'.$master->comp_mobile_sub;?></td>
	</tr>
	<tr>
		<td>Бонуси:</td>
		<td><?php echo '+'.$master->bonuses;?></td>
	</tr>
	<tr>
		<td>Бруто Плата:</td>
		<td class="strong"><?php echo '= '.$master->gross_wage;?></td>
	</tr>
	<tr>
		<td>Плата на Сметка:</td>
		<td><?php echo '-'.$master->fixed_wage;?></td>
	</tr>
	<tr>
		<td>Придонеси+Здравство:</td>
		<td><?php echo '-'. $master->social_cont;?></td>
	</tr>
	<tr>
		<td>Трошоци:</td>
		<td><?php echo $master->expenses;?></td>
	</tr>
	<tr>
		<td>Доплата:</td>
		<td class="strong"><?php echo '= '.$master->paid_wage; ?></td>
	</tr>
</table>