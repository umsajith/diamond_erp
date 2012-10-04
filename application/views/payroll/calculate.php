<h2><?php echo $heading?></h2>
<hr>
	<div id="buttons">
		<a href="#" class="button" id="new_calculation"><span class="calculator">Нова</span></a>
		<a href="#" class="button" id="change_calculation"><span class="edit">Измена</span></a>
		<a href="#" class="button" id="add_payroll"><span class="add">Внес</span></a>
	</div>
<hr>
<div class="report_calc">
	<?php echo form_open('payroll/calculate');?>
	<table id="calculation">
		<tr>
		    <td class="label"><?php echo form_label('Работник:');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('employee',$employees, set_value('employee')); ?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('Од:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('datefrom',(!isset($datefrom) ? '' : $datefrom),'id="datefrom"'); ?></td>
		</tr>
		<tr>
		    <td class="label"><?php echo form_label('До:');?><span class='req'>*</span></td>
		    <td><?php echo form_input('dateto',(!isset($dateto) ? '' : $dateto),'id="dateto"'); ?></td>
		</tr>
		<tr>    
		    <td class="label"><?php echo form_label('Месец:');?><span class='req'>*</span></td>
		    <td><?php echo form_dropdown('for_month',$G_months, set_value('for_month')); ?></td>
		</tr>
		<tr>
		    <td>&nbsp;</td>
			<td><?php echo form_submit('','Пресметај');?></td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>
<?php if($submited == 1):?>
	<dl id="current_calculation">
		<dt>Работник</dt>
		<dd><?php echo $employee_master->fname . ' ' . $employee_master->lname;?></dd>
		<dt>Од:</dt>
		<dd><?php echo $datefrom;?></dd>
		<dt>До:</dt>
		<dd><?php echo $dateto;?></dd>
		<dt>Месец:</dt>
		<dd><?php echo $for_month;?></dd>
	</dl>
<?php endif;?>

<!-- ======================================JOB ORDERS EMPLOYEES ONLY====================================== -->
<?php if(isset($fixed_wage_only) AND $fixed_wage_only == 0):?>
<hr/>
	<table class="master_table">
	<?php if (isset($job_orders) AND is_array($job_orders) AND count($job_orders)):?>
		<tr>
			<th>Работна Задача</th>
			<th>Работни Налози</th>
			<th>Вкупна Количина</th>
			<th>Просек</th>
			<th>Единечна Цена</th>
			<th>Вкупно</th>
			<th>&nbsp;</th>	
		</tr>
		<?php foreach($job_orders as $row):?>
				<tr>
					<td><?php echo $row->taskname;?></td>
					<td><?php echo $row->count;?></td>
					<td><?php echo $row->final_quantity . ' ' . $row->uname;?></td>
					<td><?php echo round($row->final_quantity / $row->count,2) . ' ' .$row->uname;?></td>
					<td><?php echo $row->rate_per_unit;?></td>
					<td><?php 
							echo $row->rate_per_unit * $row->final_quantity;
						?>
					</td>
					<td width="25px">&nbsp;</td>
					
				</tr>
		<?php endforeach;?>
	</table>
	<!-- ACCUMULATIVE WAGE CALCUATION -->
		<table class="master_table_calc">
			<tr>	
				<td><strong>ПЛАТА ПО УЧИНОК:</strong></td>
				<td width="100px" align="center"><?php echo $acc_wage;?></td>
				<td width="10px">&nbsp;</td>
			</tr>
		</table>	
	<?php endif;?>
<?php endif;?>
<!-- ======================================FIXED WAGE ONLY EMPLYOEES====================================== -->
<?php if(isset($fixed_wage_only) AND $fixed_wage_only == 1):?>
	<!-- IF EMPLOYEE ON FIXED WAGE ONLY -->
	<?php if(isset($fixed_wage)):?>
		<table class="master_table_calc">
			<tr>	
				<td><strong>ФИКСНА ПЛАТА:</strong></td>
				<td width="100px" align="center"><?php echo $fixed_wage;?></td>
				<td width="10px">&nbsp;</td>
			</tr>
		</table>	
	<?php endif;?>
<?php endif;?>	
<!-- ======================================FOR DISTRIBUTORS====================================== -->
<?php if(isset($distribution) AND is_array($distribution)):?>
	<table class="master_table">
		<tr>
			<th>Производ</th>
			<th>Категорија</th>
			<th>Вкупна Количина</th>
			<th>&nbsp;</th>
			<th>Вкупно</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach($distribution as $row):?>
				<tr>
					<td><?php echo $row->prodname;?></td>
					<td><?php echo $row->pcname;?></td>
					<td><?php echo $row->quantity . ' ' . $row->uname;?></td>
					<td><?php echo $row->commision.' ден.';?></td>
					<td><?php echo round($row->quantity * $row->commision,2).' ден.';?></td>
					<td>&nbsp;</td>
				</tr>
		<?php endforeach;?>
	</table>
	<!-- ACCUMULATIVE WAGE CALCUATION -->
	<table class="master_table_calc">
		<tr>	
			<td><strong>ПЛАТА ПО УЧИНОК:</strong></td>
			<td width="100px" align="center"><?php echo $acc_wage;?></td>
			<td width="10px">&nbsp;</td>
		</tr>
	</table>	
<?php endif;?>

<!-- ======================================GENEARAL CALCULATIONS====================================== -->
<?php if(isset($social_cont) || isset($comp_mobile_sub)):?>
<table class="master_table">
	<tr>
		<th>Категорија</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Вкупно</th>
		<th>&nbsp;</th>
	</tr>
<?php endif; ?>

<!-- SOCIAL CONTRIBUTION -->
<?php if(isset($social_cont) AND $social_cont>0 ):?>
	<tr>
		<td>Придонеси + Здравствено Осигурување</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="social_cont">
			<?php
				echo $social_cont;
			?>
		</td>
		<td width="25px">&nbsp;</td>
	</tr>
<?php endif;?>

<!-- COMPANY MOBILE SUBSIDY -->
<?php if(isset($comp_mobile_sub) AND $comp_mobile_sub>0):?>
	<tr>
		<td>Телефонска Субвенција</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="comp_mobile_sub">
			<?php
				echo $comp_mobile_sub;
			?>
		</td>
		<td width="25px">&nbsp;</td>		
	</tr>	
<?php endif;?>

<!-- BONUSES -->
<?php if (isset($extras_plus) AND is_array($extras_plus) AND count($extras_plus)):?>
	<?php foreach($extras_plus as $row):?>
		<tr>
			<td><?php echo $row->name;?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td width="90px">
				<?php 
					echo $row->amount;
				?>
			</td>
			<td width="25px">&nbsp;</td>
			
		</tr>
	<?php endforeach;?>	
</table>
<?php endif;?>

<!-- BRUTO WAGE CALCUALTION -->
<?php if(isset($gross_wage)):?>
	<table class="master_table_calc">
		<tr>	
				<td><strong>БРУТО ПЛАТА:</strong></td>
				<td width="10px">&nbsp;</td><td width="10px">&nbsp;</td>
				<td width="100px" align="center"><?php echo $gross_wage;?></td>
				<td width="10px">&nbsp;</td>
		</tr>
	</table>
<?php endif;?>
<!-- BRUTO WAGE CALCULATION END -->

<?php if(isset($fixed_wage) || isset($extras_minus)):?>
<table class="master_table"> 
	<tr>
		<th>Категорија</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>Вкупно</th>
		<th>&nbsp;</th>
	</tr>
<?php endif;?>
	
<!-- FIXED WAGE -->
<?php if(isset($fixed_wage) AND $fixed_wage>0):?>
	<tr>
		<td>Фиксна Плата на Сметка</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="fixed_wage">
			<?php 
				echo '-'.$fixed_wage;
			?>
		</td>
		<td width="25px">&nbsp;</td>			
	</tr>
<?php endif;?>

<!-- SOCIAL CONTRIBUTION -->
<?php if(isset($social_cont) AND $social_cont>0):?>
	<tr>
		<td>Придонеси + Здравствено Осигурување</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="social_cont">
			<?php
				echo '-' . $social_cont;
			?>
		</td>
		<td width="25px">&nbsp;</td>
	</tr>
<?php endif;?>

<!-- EXPENSES -->
<?php if (isset($extras_minus) AND is_array($extras_minus) AND count($extras_minus)):?>
	<?php foreach($extras_minus as $row):?>
		<tr>
			<td><?php echo $row->name;?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td width="90px">
				<?php 
					echo $row->amount;
				?>
			</td>
			<td width="25px">&nbsp;</td>		
		</tr>
	<?php endforeach;?>
</table>
<?php endif;?>

<?php if(isset($gross_exp)):?>
<table class="master_table_calc">
	<tr>	
			<td><strong>ТРОШОЦИ:</strong></td>
			<td width="100px" align="center" id="gross_exp"><?php echo $gross_exp;?></td>
			<td width="10px">&nbsp;</td>
	</tr>
</table>
<?php endif;?>

<!-- PAID WAGE -->
<?php if(isset($paid_wage)):?>
<hr>
	<table class="master_table_calc">
		<tr>	
				<td><strong>ДОПЛАТА:</strong></td>
				<td width="100px" align="center"><?php echo $paid_wage; ?></td>
				<td width="10px">&nbsp;</td>
		</tr>
	</table>
<?php endif;?>

<?php if($submited == 1):?>
<?php echo form_open('',"id='hidden_form'");?>
	<?php echo form_hidden('employee_fk',set_value('employee_fk',$employee));?>
	<?php echo form_hidden('date_from',set_value('date_from',$datefrom));?>
	<?php echo form_hidden('date_to',set_value('date_to',$dateto));?>
	<?php echo form_hidden('for_month',set_value('for_month',$for_month));?>
	
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

<script type="text/javascript">

$(function() {
		var submited = <?php echo $submited;?>;
		if(submited == 1)
			$("table#calculation").hide();

		//Date Pickers From-To.
		var dates = $( "#datefrom, #dateto" ).datepicker({
			dateFormat: "yy-mm-dd",
			onSelect: function( selectedDate ) {
				var option = this.id == "datefrom" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});

		$("#new_calculation").on("click",function(){
			location.replace("<?php echo site_url('payroll/calculate');?>");
		});

		$("#change_calculation").on("click",function(){
			$("#current_calculation").hide();
			$("table#calculation").fadeIn();
		});
		
		//SUBMITS the data to the Server
		$("#add_payroll").on("click",function(event){

			if(submited == 0)
			{
				alert("Пополнете го правилно формулатор за калкулација на плата!");
				return false;
			}	
			
			//Serializes the Hidden Form containing all calculation data
			var variables = $("form#hidden_form").serialize();

			$(this).attr("disabled", "disabled");
			event.preventDefault();

			//AJAX Post variables to payroll/insert
			$.post("<?php echo site_url('payroll/insert'); ?>",
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