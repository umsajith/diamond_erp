<?=uif::contentHeader($heading,$master)?>
    <?php if(!$master->locked):?>
        <?=uif::linkButton("payroll_extra/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("payroll_extra/delete/{$master->id}")?>
        <hr>
    <?php endif;?>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
	        <dt>Датум:</dt>
	        <dd><?=$master->for_date?></dd>
	        <dt>Работник:</dt>
	        <dd><?=$master->lname.' '.$master->fname?></dd>
	        <dt>Категорија:</dt>
	        <dd><?=$master->name?></dd>
	        <dt>Износ:</dt>
	        <dd><?=$master->amount?></dd>
	        <dt>Белешка:</dt>
	        <dd><?=uif::isNull($master->description)?></dd>
	 	</dl>
	</div>
	<div class="span7">
		<?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong>Оваа ставка е вкалкулирана во калкулација плата #
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk);?></strong>
        </div>
    	<?php endif;?>
	</div>
</div>