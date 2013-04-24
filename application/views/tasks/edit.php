<?=uif::contentHeader($heading)?>
    <?=form_open("tasks/edit/{$task->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Назив','taskname',$task)?>
        <?=uif::controlGroup('text','Основна Единица','base_unit',$task)?>
        <?=uif::controlGroup('dropdown','ЕМ','uname_fk',[$uoms,$task])?>
        <?=uif::controlGroup('dropdown','Норматив','bom_fk',[$boms,$task])?>
        <?=uif::controlGroup('text','Основна Цена','rate_per_unit',$task)?>
        <?=uif::controlGroup('text','Бонус Цена','rate_per_unit_bonus',$task)?>
        <?=uif::controlGroup('textarea','Белешка','description',$task)?>
        <?=form_hidden('id',$task->id)?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>