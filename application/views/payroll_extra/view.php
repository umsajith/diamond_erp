<h2><?php echo $heading; ?></h2>
<hr>
<?php if($master->locked != 1):?>
	<div id="buttons">
	<a href="<?php echo site_url('payroll_extra/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
	<a href="<?php echo site_url('payroll_extra/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
	<hr>
<?php endif; ?>
	<dl>
        <dt>Работник:</dt>
        <dd><?php echo $master->lname.' '.$master->fname;?></dd>
        <dt>Категорија:</dt>
        <dd><?php echo $master->name;?></dd>
        <dt>Износ:</dt>
        <dd><?php echo $master->amount;?></dd>
        <dt>За Месец:</dt>
        <dd><?php echo ($master->for_month == NULL ? '-' : $master->for_month);?></dd>
        <dt>Опис:</dt>
        <dd><?php echo ($master->description == NULL ? '-' : $master->description);?></dd>
        <dt>Датум на Внес:</dt>
        <dd><?php echo $master->dateofentry;?></dd>
	</dl>
<?php if($master->payroll_fk != NULL):?>	
	<h4>Оваа ставка е вкалкулирана во плата бр. <?php echo anchor('payroll/view/'.$master->payroll_fk,$master->payroll_fk);?></h4>
<?php endif;?>
<hr>
<?php $this->load->view('includes/_del_dialog');?>