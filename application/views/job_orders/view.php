<?=uif::contentHeader($heading,$master)?>
    <?php if(!$master->locked):?>
        <?=uif::linkButton("job_orders/edit/{$master->id}",'icon-edit','warning')?>
        <?=uif::linkDeleteButton("job_orders/delete/{$master->id}")?>
        <hr>
    <?php endif;?>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt>Датум:</dt>
            <dd><?=uif::isNull($master->datedue)?></dd>
            <dt>Работник:</dt>
            <dd><?=$master->fname.' '.$master->lname;?></dd>
            <dt>Работна Задача:</dt>
            <dd><?=$master->taskname;?></dd>
            <?php if ($master->calculation_rate): ?>
                <dt>Основна Цена:</dt>
                <dd><?=$master->calculation_rate.$G_currency.'/'.$master->uname;?></dd>
            <?php endif; ?>
            <dt>Количина:</dt>
            <dd><?=$master->assigned_quantity.' '.$master->uname ;?></dd>
            <dt>Растур:</dt>
            <dd><?=uif::isNull($master->defect_quantity)?></dd>
            <dt>Работни Часови:</dt>
            <dd><?=uif::isNull($master->work_hours)?></dd>            
            <dt>Смена:</dt>
            <dd><?=uif::isNull($master->shift)?></dd>            
            <dt>Забелешка:</dt>
            <dd><?=uif::isNull($master->description)?></dd>            
            <?php if($this->session->userdata('admin')):?>
                <dt>Оператор:</dt>
                <dd><?=$master->operator;?></dd>  
            <?php endif;?>
        </dl>
    </div>
    <div class="span7">
    <?php if (isset($details) AND is_array($details) AND count($details)):?>
        <table class="table table-condensed table-bordered">
            <caption><h4>Употребени Сировини</h4></caption>
            <thead>
                <tr>
                    <th>Артикл</th>
                    <th>Категорија</th>
                    <th>Количина</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($details as $row):?>
                <tr>
                    <td><?=$row->prodname?></td>
                    <td><?=$row->pcname?></td>
                    <td><?=$row->quantity. ' ' .$row->uname?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php endif;?>
    <?php if($master->payroll_fk):?>
        <div class="alert">
            <i class="icon-lock"></i>
            <strong>Овој работен налог е заклучен по калкулација за плата #
            <?=anchor("payroll/view/{$master->payroll_fk}",$master->payroll_fk);?></strong>
        </div>
    <?php endif;?>  
    </div>
</div>

