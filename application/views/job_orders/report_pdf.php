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
	<h1>Преглед на Производство</h1>
  <span id="label">Од:</span> <?php echo $datefrom;?>
  <span id="label">До:</span> <?php echo $dateto; ?><br/>
  <span id="label">Вработен:</span> <?php echo (isset($employee))?$employee->fname.' '.$employee->lname:'-'; ?><br/>
  <span id="label">Работна Задача:</span> <?php echo (isset($task->taskname))?$task->taskname:'-'; ?><br/> 
  <!-- <span id="label">Смена:</span> <?php// echo (isset($shift))?$shift:'-'; ?> -->
</div>

<table id="payroll"> 
<?php if (isset($results) AND is_array($results) AND count($results) > 0):?>
	<thead>
		<tr>
	    	<th>Работна Задача</th>
	    	<th>Вкупно</th>
	    	<th>Просек</th>
	    	<th>Максимум</th>
	    	<th>Минимум</th>
	    	<th>Раб.Налози</th>    	
    	</tr>
    </thead>
    <tbody>
	<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo $row->sum.' '.$row->uname;?></td>
			<td><?php echo round($row->avg,2).' '.$row->uname;?></td>
			<td><?php echo $row->max.' '.$row->uname;?></td>
			<td><?php echo $row->min.' '.$row->uname;?></td>
			<td><?php echo $row->count;?></td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<?php if(empty($results)):?>
		<?php $this->load->view('includes/_no_records');?>
	<?php endif;?>
<?php endif;?>
</tbody>
</table>
<div id="container" style="margin: 0"></div>