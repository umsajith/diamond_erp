<?=uif::contentHeader($heading)?>
    <?=form_open('tasks/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Назив','taskname')?>
        <?=uif::controlGroup('text','Основна Единица','ptname')?>
        <?=uif::controlGroup('dropdown','ЕМ','uname_fk',[$uoms])?>
        <?=uif::controlGroup('dropdown','Норматив','bom_fk',[$boms])?>
        <?=uif::controlGroup('text','Основна Цена','rate_per_unit')?>
        <?=uif::controlGroup('text','Бонус Цена','rate_per_unit_bonus')?>
        <?=uif::controlGroup('textarea','Белешка','description')?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>