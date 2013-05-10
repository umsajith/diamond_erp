<?php if(isset($last)): ?>
	<h4 class="text-center"><?=uif::lng('attr.last_job_order')?></h4>
    <dl class="dl-horizontal">
        <dt><?=uif::lng('attr.date')?>:</dt>
        <dd><?=($last->datedue==null?'-':$last->datedue)?></dd>
        <dt><?=uif::lng('attr.employee')?>:</dt>
        <dd><?=$last->fname.' '.$last->lname?></dd>
        <dt><?=uif::lng('attr.task')?>:</dt>
        <dd><?=$last->taskname?></dd>
        <dt><?=uif::lng('attr.quantity')?></dt>
        <dd><?=$last->assigned_quantity.' '.$last->uname?></dd>
        <dt><?=uif::lng('attr.work_hours')?>:</dt>
        <dd><?=($last->work_hours==null ?'-':$last->work_hours.' час/а')?></dd>
        <dt><?=uif::lng('attr.shift')?>:</dt>
        <dd><?=($last->shift==null?'-':$last->shift)?></dd>
        <dt>&nbsp;</dt>
    	<dd><?=uif::actionGroup('job_orders',$last->id)?></dd>
    </dl>
<?php endif ?>