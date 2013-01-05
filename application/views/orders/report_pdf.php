<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="<?php echo base_url();?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
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
 	font-size: 8pt;
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
	<h1>Рипорт на Продажба</h1>
  <span id="label">Од:</span> <?php echo $datefrom;?>
  <span id="label">До:</span> <?php echo $dateto; ?><br/>
  <span id="label">Дистрибутер:</span> <?php echo (isset($distributer))?$distributer->fname.' '.$distributer->lname:'-'; ?><br/>
  <span id="label">Купувач:</span> <?php echo (isset($partner->company))?$partner->company:'-'; ?><br/> 
  <span id="label">Плаќање:</span> <?php echo (isset($payment->name))?$payment->name:'-'; ?>
</div>

<table id="payroll">
	<thead>
		<tr>
	    	<th class="header">Производ</th>
	    	<th class="header">Земено</th>
	    	<th class="header">Вратено</th>   
	    	<th class="header">% Вратено</th>    	
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->prodname;?></td>
			<td><?php echo $row->quantity.' '.$row->uname;?></td>
			<td><?php echo $row->returned_quantity.' '.$row->uname;?></td>
			<td><?php echo round($row->returned_quantity/$row->quantity,3).' %';?></td>
		</tr>
	<?php endforeach;?>
</tbody>
</table>
<div id="container" style="margin: 0"></div>