<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkButton("employees/edit/{$master->id}",'icon-edit','warning')?>
    <?=uif::linkDeleteButton("employees/delete/{$master->id}")?>
    <hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.first_name').' '.uif::lng('attr.last_name')?>:</dt>
            <dd><?=$master->fname. ' '. $master->lname?></dd>
            <dt><?=uif::lng('attr.dob')?>:</dt>
            <dd><?=uif::date($master->dateofbirth)?></dd>
            <dt><?=uif::lng('attr.ssn')?>:</dt>
            <dd><?=$master->ssn?></dd>
            <dt><?=uif::lng('attr.marital_status')?>:</dt>
            <dd>
                <?php 
                    if($master->mstatus == 'single') echo uif::lng('attr.ms_single');
                    if($master->mstatus == 'married') echo uif::lng('attr.ms_married');
                    if($master->mstatus == 'divorced') echo uif::lng('attr.ms_divorced');
                ?>
            </dd>
            <dt><?=uif::lng('attr.sex')?>:</dt>
            <dd><?=($master->gender == 'm' ? uif::lng('attr.sex_male') : uif::lng('attr.sex_female'))?></dd>
            <dt><?=uif::lng('attr.address')?>:</dt>
            <dd><?=uif::isNull($master->address)?></dd>
            <dt><?=uif::lng('attr.city')?>:</dt>
            <dd><?=uif::isNull($master->name)?></dd>
            <dt><?=uif::lng('attr.postal_code')?>:</dt>
            <dd><?=uif::isNull($master->postalcode)?></dd>
            <dt><?=uif::lng('attr.company_mobile')?>:</dt>
            <dd><?=uif::isNull($master->comp_mobile)?></dd>
            <dt><?=uif::lng('attr.mobile')?>:</dt>
            <dd><?=uif::isNull($master->mobile)?></dd>
            <dt><?=uif::lng('attr.phone')?>:</dt>
            <dd><?=uif::isNull($master->phone)?></dd>
            <dt><?=uif::lng('attr.email')?>:</dt>
            <dd><?=uif::isNull($master->email)?></dd>
            <dt><?=uif::lng('attr.role')?>:</dt>
            <dd><?=uif::isNull($master->role_name)?></dd>
            <dt><?=uif::lng('common.username')?>:</dt>
            <dd><?=uif::isNull($master->username)?></dd>
            <dt><?=uif::lng('attr.bank')?>:</dt>
            <dd><?=uif::isNull($master->bank)?></dd>
            <dt><?=uif::lng('attr.account_number')?>:</dt>
            <dd><?=uif::isNull($master->account_no)?></dd>
            <dt><?=uif::lng('attr.fixed')?>:</dt>
            <dd><?=($master->fixed_wage_only) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt><?=uif::lng('attr.fixed_wage')?>:</dt>
            <dd><?=uif::isNull($master->fixed_wage)?></dd>
            <dt><?=uif::lng('attr.social_contribution')?>:</dt>
            <dd><?=uif::isNull($master->social_cont)?></dd>
            <dt><?=uif::lng('attr.subvention')?>:</dt>
            <dd><?=uif::isNull($master->comp_mobile_sub)?></dd>
            <dt><?=uif::lng('attr.position')?>:</dt>
            <dd><?=uif::isNull($master->position)?></dd>
            <dt><?=uif::lng('attr.distributor')?>:</dt>
            <dd><?=($master->is_distributer) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>
            <dt><?=uif::lng('attr.manager')?>:</dt>
            <dd><?=($master->is_manager) ? 
                uif::staticIcon('icon-ok') : uif::staticIcon('icon-remove')?></dd>            
            <dt><?=uif::lng('attr.date_start')?>:</dt>
            <dd><?=uif::isNull($master->start_date)?></dd>
            <dt><?=uif::lng('attr.date_end')?>:</dt>
            <dd><?=uif::isNull($master->stop_date)?></dd>                  	
            <dt><?=uif::lng('attr.note')?>:</dt>
            <dd><?=uif::isNull($master->note)?></dd>   
        </dl>
    </div>
    <div class="span7">
        <?php if(isset($payrolls) AND count($payrolls)):?>
        <div class="legend"><?=uif::lng('app.last_payrolls_of_employee')?></div>
        <table class="table table-condensed assigned-tasks">
            <thead>
                <tr>
                    <th><?=uif::lng('attr.link')?></th>
                    <th><?=uif::lng('attr.month')?></th>
                    <th><?=uif::lng('attr.accumulated')?></th>
                    <th><?=uif::lng('attr.gross')?></th>
                    <th><?=uif::lng('attr.paid')?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payrolls as $row):?>
                <tr data-tid=<?=$row->id?>>
                    <td><?=anchor("payroll/view/{$row->id}",'#'.$row->id)?></td>
                    <td><?=uif::date($row->date_from,'%m/%Y')?></td>
                    <td><?=$row->acc_wage?></td>
                    <td><?=$row->gross_wage?></td>
                    <td><?=$row->paid_wage?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif;?>
         <hr>
        <div class="legend"><?=uif::lng('app.assigning_tasks')?></div>
        <?=form_open("employees/assignTask")?>
            <div class="well well-small form-horizontal">
                <?=uif::formElement('dropdown','','task_fk',[$tasks],"id='task' class='input-xlarge'")?>
                <?=form_hidden('employee_fk',$master->id)?>
                <?=uif::button('icon-plus-sign','success',"id='assign-task'")?>
            </div>  
        <?=form_close()?>
        <?php if(isset($assigned_tasks) AND count($assigned_tasks)):?>
        <div class="legend"><?=uif::lng('app.assigned_tasks')?></div>
        <table class="table table-condensed assigned-tasks">
            <thead>
                <tr>
                    <th><?=uif::lng('attr.task')?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assigned_tasks as $task):?>
                <tr data-tid=<?=$task->id?>>
                    <td><?=$task->taskname?></td>
                    <td><?=uif::linkButton("employees/unassignTask/{$task->id}",'icon-trash','danger btn-mini')?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif;?>
    </div>
</div>
<script>
    $(function(){
        cd.dd("select[name=task_fk]","<?=uif::lng('attr.task')?>");

        $("#assign-task").on("click",function(){
            var task = $("#task option:selected");
            if(task.val() == ''){
                cd.notify("<?=uif::lng('air.pick_task')?>");
                $("#task").focus();
                return false;
            }
        });
    });
</script>