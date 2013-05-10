<?=uif::contentHeader($heading,$master)?>
    <?php if(!$master->locked):?>
        <?=uif::linkButton("job_orders/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("job_orders/delete/{$master->id}")?>
    <?php if(!$master->is_completed):?>
        <?=uif::button('icon-ok-sign','success','onClick=cd.completeJobOrders("'.site_url('job_orders/ajxComplete').'",'.$master->id.')')?>
    <?php endif; ?>
    <hr>
    <?php endif;?>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.date')?>:</dt>
            <dd><?=uif::date($master->datedue)?></dd>
            <dt><?=uif::lng('attr.employee')?>:</dt>
            <dd><?=$master->fname.' '.$master->lname;?></dd>
            <dt><?=uif::lng('attr.task')?>:</dt>
            <dd><?=$master->taskname;?></dd>
            <?php if ($master->calculation_rate): ?>
                <dt><?=uif::lng('attr.unit_price')?>:</dt>
                <dd><?=$master->calculation_rate.' / '.$master->uname;?></dd>
            <?php endif; ?>
            <dt><?=uif::lng('attr.quantity')?>:</dt>
            <dd><?=$master->assigned_quantity.' '.$master->uname ;?></dd>
            <dt><?=uif::lng('attr.spill')?>:</dt>
            <dd><?=uif::isNull($master->defect_quantity)?></dd>
            <dt><?=uif::lng('attr.work_hours')?>:</dt>
            <dd><?=uif::isNull($master->work_hours)?></dd>            
            <dt><?=uif::lng('attr.shift')?>:</dt>
            <dd><?=uif::isNull($master->shift)?></dd>            
            <dt><?=uif::lng('attr.note')?>:</dt>
            <dd><?=uif::isNull($master->description)?></dd> 
            <?php if($master->is_completed):?>
                <dt><?=uif::lng('attr.locked')?>:</dt>
                <dd><?=uif::staticIcon('icon-ok')?></dd>  
            <?php endif;?>           
            <?php if($this->session->userdata('admin')):?>
                <dt><?=uif::lng('attr.operator')?>:</dt>
                <dd><?=$master->operator;?></dd>  
            <?php endif;?>
        </dl>
    </div>
    <div class="span7">
    <?php if (isset($details) AND is_array($details) AND count($details)):?>
        <div class="legend"><?=uif::lng('app.used_raw_materials')?></div>
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?=uif::lng('attr.item')?></th>
                    <th><?=uif::lng('attr.category')?></th>
                    <th><?=uif::lng('attr.quantity')?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($details as $row):?>
                <tr>
                    <td><?=$row->prodname?></td>
                    <td><?=$row->pcname?></td>
                    <td><?=$row->quantity.' '.$row->uname?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php endif;?>
    <?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong><?=uif::lng('app.job_order_locked_by_payroll')?>
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk);?></strong>
        </div>
    <?php endif;?>  
    </div>
</div>