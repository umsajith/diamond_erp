<div class="last_job_order">
	<h3>Последен работен налог</h3>
	<dl>
	        <dt>Работник:</dt>
	        <dd><?php echo $last->fname.' '.$last->lname;?></dd>
	        <dt>Работна Задача:</dt>
	        <dd><?php echo $last->taskname;?></dd>
	        <dt>За Датум:</dt>
	        <dd><?php echo ($last->datedue == NULL ? '-' : $last->datedue);?></dd>
	        <dt>Зададена Кол.:</dt>
	        <dd><?php echo $last->assigned_quantity . ' ' . $last->uname ;?></dd>
	        <dt>Работни Часови:</dt>
	        <dd><?php echo ($last->work_hours == NULL ? '-' : $last->work_hours . ' час/а');?></dd>
	        <dt>Смена:</dt>
	        <dd><?php echo ($last->shift == NULL ? '-' : $last->shift);?></dd>
	        <dt>Корекција:</dt>
	        <?php echo anchor("job_orders/edit/$last->id",'&nbsp;','class="edit_icon"');?>
	</dl>
</div>