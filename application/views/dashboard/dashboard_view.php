<div id="dashboard_wrapper">
	<div id="dash_title"><span id="dash_title">Контролна Табла</span></div>
	<div id="dash_stats">
		<table id="dash_stats_table">
		<caption><span class="dash_caption">Преглед</span></caption>
			<tr>
				<td>Вкупно Нарачки:</td>
			 	<td align="right"><?php echo $total_orders;?></td>
			</tr>
			<tr>
				<td>Нарачки во Очекување:</td>
			 	<td align="right"><?php echo $waiting_approval;?></td>
			</tr>
			<tr>
				<td>Број на Партнери:</td>
			 	<td align="right"><?php echo $total_partners;?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			 	<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Вкупно Работни Налози:</td>
			 	<td align="right"><?php echo $total_jo;?></td>
			</tr>
			<tr>
				<td>Незавршени Работни Налози:</td>
			 	<td align="right"><?php echo $uncomplete_jo;?></td>
			</tr>
			<tr>
				<td>Број на Вработени:</td>
			 	<td align="right"><?php echo $total_emp;?></td>
			</tr>
			<tr>
				<td>Број на Производи:</td>
			 	<td align="right"><?php echo $total_prod;?></td>
			</tr>
		
		
		</table>
	
	
	</div>
	<div id="dash_job_orders">

	</div>
	
	<div id="dash_orders">

	</div>
	
</div>