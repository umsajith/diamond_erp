<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>#<?php echo $master->code;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>
	<div id="buttons">
		<a href="<?php echo site_url('payroll/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
		<a href="<?php echo site_url('payroll/payroll_pdf/'.$master->id);?>" class="button"><span class="pdf">PDF</span></a>
	</div>
<hr>
	<dl>
        <dt>Работник:</dt>
        <dd><?php echo anchor("employees/view/$master->eid",$master->fname . ' ' . $master->lname); ?></dd>
        <dt>За Месец:</dt>
        <dd><?php echo $master->for_month . '/' . mdate('%Y',strtotime($master->date_from));?></dd>
		<dt>Од Датум:</dt>
        <dd><?php echo $master->date_from;?></dd>
        <dt>До Датум:</dt>
        <dd><?php echo $master->date_to;?></dd>
        <?php if($master->acc_wage != 0):?>
	       <dt>Учинок:</dt>
           <dd><strong><?php echo $master->acc_wage;?></strong></dd>
        <?php endif;?>
        <?php if($master->fixed_wage != 0):?>
	        <dt>Фиксна Плата:</dt>
	        <dd><?php echo '+'.$master->fixed_wage;?></dd>
        <?php endif;?>
        <dt>Придонеси+Здравство:</dt>
        <dd><?php echo '+'. $master->social_cont;?></dd>
        <dt>Тел.Субвенција:</dt>
        <dd><?php echo '+'.$master->comp_mobile_sub;?></dd>
        <dt>Бонуси:</dt>
        <dd><?php echo '+'.$master->bonuses;?></dd>
        <dt>Бруто:</dt>
        <dd><strong><?php echo '= '.$master->gross_wage;?></strong></dd>
        <?php if($master->fixed_wage != 0):?>
	        <dt>Плата на сметка:</dt>
	        <dd><?php echo '-'.$master->fixed_wage;?></dd>
        <?php endif;?>
        <?php if($master->social_cont != 0):?>
	        <dt>Придонеси+Здравство:</dt>
	        <dd><?php echo '-'.$master->social_cont;?></dd>
        <?php endif;?>
        <dt>Трошоци:</dt>
        <dd><?php echo $master->expenses;?></dd>
        <dt>Доплата:</dt>
        <dd><strong><?php echo '= '.$master->paid_wage; ?></strong></dd>
	</dl>

<hr>
<table class="master_table">
<?php if (isset($results) && is_array($results) && count($results)):?>
	<tr>
		<th>Работна Задача</th>
		<th>Работни Налози</th>
		<th>Вкупна Количина</th>
		<th>Единечна Цена</th>
		<th>Вкупно</th>
		<th>&nbsp;</th>	
	</tr>
<?php foreach($results as $row):?>
		<tr>
			<td><?php echo $row->taskname;?></td>
			<td><?php echo $row->count;?></td>
			<td><?php echo $row->final_quantity . ' ' . $row->uname;?></td>
			<td><?php echo $row->rate_per_unit;?></td>
			<td><?php 
					echo $row->rate_per_unit * $row->final_quantity;
				?>
			</td>
			<td width="25px">&nbsp;</td>		
		</tr>
<?php endforeach;?>
</table>

<!-- JOB ORDERS WAGE CALCUALTION -->
<table class="master_table_calc">
	<tr>	
			<td><strong>ПЛАТА ПО УЧИНОК:</strong></td>
			<td width="100px" align="center"><?php echo $master->acc_wage;?></td>
			<td width="10px">&nbsp;</td>
	</tr>
</table>
<!-- JOB ORDERS WAGE CALCUALTION END -->

<?php endif;?>

<table class="master_table">
	<tr>
		<th>Категорија</th>
		<th></th>
		<th></th>
		<th>Вкупно</th>
		<th></th>
	</tr>
	
<?php if(isset($master->fixed_wage) && $master->fixed_wage !=0 ):?>
	<tr>
		<td>Фиксна Плата</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px">
			<?php echo $master->fixed_wage;?>
		</td>
		<td width="25px">&nbsp;</td>
	</tr>
<?php endif;?>

<!-- SOCIAL CONTRIBUTION -->
<?php if($master->social_cont>0):?>
	<tr>
		<td>Придонеси + Здравствено Осигурување</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="social_cont">
			<?php
				echo $master->social_cont;
			?>
		</td>
		<td width="25px">&nbsp;</td>
	</tr>
<?php endif;?>

<!-- COMPANY MOBILE SUBSIDY -->
<?php if($master->comp_mobile_sub>0):?>
	<tr>
		<td>Телефонска Субвенција</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="comp_mobile_sub">
			<?php
				echo $master->comp_mobile_sub;
			?>
		</td>
		<td width="25px">&nbsp;</td>		
	</tr>
<?php endif;?>

<!-- BONUSES -->
<?php if (isset($extras_plus) && is_array($extras_plus) && count($extras_plus)):?>
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
<table class="master_table_calc">
	<tr>	
			<td><strong>БРУТО ПЛАТА:</strong></td>
			<td width="10px">&nbsp;</td><td width="10px">&nbsp;</td>
			<td width="100px" align="center"><?php echo $master->gross_wage;?></td>
			<td width="10px">&nbsp;</td>
	</tr>
</table>
<!-- BRUTO WAGE CALCULATION END -->

<?php if(isset($extras_minus)):?>
<table class="master_table"> 
	<tr>
		<th>Категорија</th>
		<th></th>
		<th></th>
		<th>Вкупно</th>
		<th></th>
	</tr>
<?php endif;?>
	
<!-- FIXED WAGE -->
<?php if($master->fixed_wage>0):?>
	<tr>
		<td>Фиксна Плата на Сметка</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="fixed_wage">
			<?php 
				echo '-'.$master->fixed_wage;
			?>
		</td>
		<td width="25px">&nbsp;</td>
			
	</tr>
<?php endif;?>

<!-- SOCIAL CONTRIBUTION -->
<?php if($master->social_cont>0):?>
	<tr>
		<td>Придонеси + Здравствено Осигурување</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td width="90px" id="social_cont">
			<?php
				echo '-' . $master->social_cont;
			?>
		</td>
		<td width="25px">&nbsp;</td>
	</tr>
<?php endif;?>

<!-- EXPENSES -->
<?php if (isset($extras_minus) && is_array($extras_minus) && count($extras_minus)):?>
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

<?php if (isset($master->fixed_wage) || isset($master->expenses)):?>
<table class="master_table_calc">
	<tr>	
			<td><strong>ТРОШОЦИ:</strong></td>
			<td width="100px" align="center" id="gross_exp"><?php echo $gross_exp = -($master->fixed_wage) + $master->expenses - $master->social_cont;?></td>
			<td width="10px">&nbsp;</td>
	</tr>
</table>
<?php endif;?>

<!-- PAID WAGE -->
<?php if (isset($master->paid_wage)):?>
	<hr>
	<table class="master_table_calc">
		<tr>	
				<td><strong>ДОПЛАТА:</strong></td>
				<td width="100px" align="center"><?php echo $master->paid_wage; ?></td>
				<td width="10px">&nbsp;</td>
		</tr>
	</table>
<?php endif;?>
<hr>