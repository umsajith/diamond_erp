<?=uif::contentHeader($heading,$master)?>
    <?php if(!$master->locked):?>
        <?=uif::linkButton("payroll_extra/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("payroll_extra/delete/{$master->id}")?>
        <hr>
    <?php endif;?>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
	        <dt><?=uif::lng('attr.date')?>:</dt>
	        <dd><?=$master->for_date?></dd>
	        <dt><?=uif::lng('attr.employee')?>:</dt>
	        <dd><?=$master->lname.' '.$master->fname?></dd>
	        <dt><?=uif::lng('attr.category')?>:</dt>
	        <dd><?=$master->name?></dd>
	        <dt><?=uif::lng('attr.amount')?>:</dt>
	        <dd><?=$master->amount.$glCurrSh?></dd>
	        <dt><?=uif::lng('attr.note')?>:</dt>
	        <dd><?=uif::isNull($master->description)?></dd>
	 	</dl>
	</div>
	<div class="span7">
		<?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong><?=uif::lng('app.payroll_extra_calculated_in_payroll')?>
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk);?></strong>
        </div>
    	<?php endif;?>
	</div>
</div>