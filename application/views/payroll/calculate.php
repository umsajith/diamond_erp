<?=uif::contentHeader($heading)?>
<div class="row-fluid">
	<div class="span3" id="content-main-buttons">
		<?=uif::button('icon-file','primary','onClick=location.reload(true)')?>
		<?=uif::button('icon-plus','danger','id="add_payroll"')?>
	</div>
</div>
<hr>
<div class="row-fluid">
	<div class="span4 well">
	<?=form_open('payroll/calculate')?>
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('datepicker','','datefrom','','placeholder="Од"')?>
		<?=uif::controlGroup('datepicker','','dateto','','placeholder="До"')?>
		<?=uif::controlGroup('dropdown','','employee',[$employees])?>
		<?=uif::button('icon-cog btn-large','success','type="submit"');?>
	<?=form_close()?>
	</div>
	<!-- ======================================JOB ORDERS EMPLOYEES ONLY====================================== -->
	<div class="span8">
	<?php if(isset($fixed_wage_only) AND !$fixed_wage_only):?>
		<?php if (isset($job_orders) AND is_array($job_orders) AND count($job_orders)):?>
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>Работна Задача</th>
					<th>Работни Налози</th>
					<th>Вкупно</th>
					<th>Просек</th>
					<th>Цена</th>
					<th>Вкупно</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($job_orders as $row):?>
				<tr>
					<td><?php echo $row->taskname;?></td>
					<td><?php echo $row->count;?></td>
					<td><?php echo $row->final_quantity.' '.$row->uname;?></td>
					<td><?php echo round($row->final_quantity / $row->count,2).' '.$row->uname;?></td>
					<td><?php echo $row->rate_per_unit;?></td>
					<td><?php echo $row->rate_per_unit * $row->final_quantity;?></tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<!-- JOB ORDERS WAGE CALCUALTION -->
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong>ПЛАТА ПО УЧИНОК:</strong>
				<strong class="pull-right"><?php echo $acc_wage;?></strong>
			</div>
		</div>
	<!-- JOB ORDERS WAGE CALCUALTION END -->
		<?php endif;?>
	<?php endif;?>
	<!-- ======================================FIXED WAGE ONLY EMPLYOEES====================================== -->
	<?php if(isset($fixed_wage_only) AND $fixed_wage_only):?>
		<!-- IF EMPLOYEE ON FIXED WAGE ONLY -->
		<?php if(isset($fixed_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong>ФИКСНА ПЛАТА:</strong>
				<strong class="pull-right"><?php echo $fixed_wage;?></strong>
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
				<th>Вкупна Количина</th>
				<th>&nbsp;</th>
				<th>Вкупно</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($distribution as $row):?>
				<tr>
					<td><?php echo $row->prodname;?></td>
					<td><?php echo $row->pcname;?></td>
					<td><?php echo $row->quantity . ' ' . $row->uname;?></td>
					<td><?php echo $row->commision.' ден.';?></td>
					<td><?php echo round($row->quantity * $row->commision,2).' ден.';?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<!-- ACCUMULATIVE WAGE CALCUATION -->
		<div class="row-fluid">
			<div class="span12 alert alert-info">
				<strong>ПЛАТА ПО УЧИНОК:</strong>
				<strong class="pull-right"><?php echo $acc_wage;?></strong>
			</div>
		</div>	
	<?php endif;?>

	<!-- ======================================GENEARAL CALCULATIONS====================================== -->
	<?php if(isset($social_cont) OR isset($comp_mobile_sub)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Категорија</th>
				<th>Износ</th>
			</tr>
		</thead>
	<?php endif; ?>
	<!-- SOCIAL CONTRIBUTION -->
		<tbody>
		<?php if(isset($social_cont) AND $social_cont>0 ):?>
			<tr>
				<td>Придонеси + Здравствено Осигурување</td>

				<td><?php echo $social_cont;?></td>
				<td></td>
			</tr>
		<?php endif;?>

		<!-- COMPANY MOBILE SUBSIDY -->
		<?php if(isset($comp_mobile_sub) AND $comp_mobile_sub>0):?>
			<tr>
				<td>Телефонска Субвенција</td>
				<td><?php echo $comp_mobile_sub;?></td>	
			</tr>	
		<?php endif;?>

		<!-- BONUSES -->
		<?php if (isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus)):?>
			<?php foreach($extras_plus as $row):?>
				<tr>
					<td><?php echo $row->name;?></td>
					<td><?php echo $row->amount;?></td>
				</tr>
			<?php endforeach;?>
		<?php endif;?>
		</tbody>	
	</table>

	<!-- BRUTO WAGE CALCUALTION -->
	<?php if(isset($gross_wage)): ?>
	<div class="row-fluid">
		<div class="span12 alert alert-info">
			<strong>БРУТО ПЛАТА:</strong>
			<strong class="pull-right"><?php echo $gross_wage;?></strong>
		</div>
	</div>
	<?php endif; ?>
	<!-- BRUTO WAGE CALCULATION END -->

	<?php if(isset($fixed_wage) OR isset($extras_minus)):?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th>Категорија</th>
				<th>Износ</th>
			</tr>
		</thead>
	<?php endif; ?>
	<!-- FIXED WAGE -->
		<tbody>
		<?php if(isset($fixed_wage) AND $fixed_wage>0 AND !$fixed_wage_only):?>
		<tr>
			<td>Фиксна Плата на Сметка</td>
			<td><?php echo '-'.$fixed_wage;?></td>		
		</tr>
		<?php endif;?>

	<!-- SOCIAL CONTRIBUTION -->
		<?php if(isset($social_cont) AND $social_cont>0):?>
		<tr>
			<td>Придонеси + Здравствено Осигурување</td>
			<td><?php echo '-' . $social_cont;?></td>
		</tr>
		<?php endif;?>

	<!-- EXPENSES -->
		<?php if (isset($extras_minus) AND is_array($extras_minus) AND count($extras_minus)):?>
			<?php foreach($extras_minus as $row):?>
				<tr>
					<td><?php echo $row->name;?></td>
					<td><?php echo $row->amount;?></td>	
				</tr>
			<?php endforeach;?>
		<?php endif;?>
		</tbody>
	</table>

	<?php if(isset($gross_exp)):?>
	<div class="row-fluid">
		<div class="span12 alert">
			<strong>ТРОШОЦИ:</strong>
			<strong class="pull-right"><?=$gross_exp;?></strong>
		</div>
	</div>
	<?php endif;?>

	<!-- PAID WAGE -->
	<?php if(isset($paid_wage)):?>
		<div class="row-fluid">
			<div class="span12 alert alert-success">
				<strong>ДОПЛАТА:</strong>
				<strong class="pull-right"><?=$paid_wage?></strong>
			</div>
		</div>
	<?php endif;?>

	<?php if($submited):?>
	<?php echo form_open('',"id='hidden_form'");?>
		<?php echo form_hidden('employee_fk',set_value('employee_fk',$employee));?>
		<?php echo form_hidden('date_from',set_value('date_from',$datefrom));?>
		<?php echo form_hidden('date_to',set_value('date_to',$dateto));?>
		
		<?php echo form_hidden('acc_wage',set_value('acc_wage',$acc_wage));?>
		<?php echo form_hidden('social_cont',set_value('acc_wage',$social_cont));?>
		<?php echo form_hidden('comp_mobile_sub',set_value('acc_wage',$comp_mobile_sub));?>
		<?php echo form_hidden('bonuses',set_value('acc_wage',$bonuses));?>
		<?php echo form_hidden('gross_wage',set_value('gross_wage',$gross_wage));?>
		<?php echo form_hidden('fixed_wage',set_value('fixed_wage',$fixed_wage));?>
		<?php echo form_hidden('expenses',set_value('expenses',$expenses));?>
		<?php echo form_hidden('paid_wage',set_value('paid_wage',$paid_wage));?>
		<?php echo form_hidden('fixed_wage_only',set_value('fixed_wage_only',$fixed_wage_only));?>
		
		<?php echo form_hidden('is_distributer',set_value('is_distributer',$is_distributer));?>
	<?php echo form_close();?>
	<?php endif;?>
	</div>
</div>

<script>

$(function() {

		//Date Pickers From-To.
		$("select[name=employee]").select2();
		cd.dateRange('input[name=datefrom]','input[name=dateto]');

		//SUBMITS the data to the Server
		$("#add_payroll").on("click",function(){
			
			$(this).attr('disabled','disabled');

			$("div#ajx_div_loader").show();

			//Serializes the Hidden Form containing all calculation data
			var variables = $("form#hidden_form").serialize();

			//$(this).attr("disabled", "disabled");
			//event.preventDefault();

			//AJAX Post variables to payroll/insert
			$.post("<?=site_url('payroll/insert')?>",
					variables,
				   function(data){
					   if(data){
						   //Upon success, redirects to new payroll created
						   location.replace(data.redirect);
					   }
					   else {
						   //Hard refresh if fail
						   location.reload(true);
					   }
				   },"json");
			return false;   
		});
	});
	
</script>