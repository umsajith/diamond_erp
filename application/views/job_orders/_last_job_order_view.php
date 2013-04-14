<?php if(isset($last)): ?>
	<h4 class="text-center">Последен Работен Налог</h4>
<dl class="dl-horizontal">
    <dt>Датум:</dt>
    <dd><?=($last->datedue==null?'-':$last->datedue);?></dd>
    <dt>Работник:</dt>
    <dd><?=$last->fname.' '.$last->lname;?></dd>
    <dt>Работна Задача:</dt>
    <dd><?=$last->taskname;?></dd>
    <dt>Количина</dt>
    <dd><?=$last->assigned_quantity.' '.$last->uname ;?></dd>
    <dt>Работни Часови:</dt>
    <dd><?=($last->work_hours==null ?'-':$last->work_hours.' час/а');?></dd>
    <dt>Смена:</dt>
    <dd><?=($last->shift==null?'-':$last->shift);?></dd>
    <dt>&nbsp;</dt>
	<dd><?=uif::actionGroup('job_orders',$last->id)?></dd>
</dl>
<?php endif ?>