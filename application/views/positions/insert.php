<?=uif::contentHeader($heading)?>
    <?=form_open('positions/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Назив','position')?>
        <?=uif::controlGroup('dropdown','Сектор','dept_fk',[$departments])?>
        <?=uif::controlGroup('text','Основна Плата','base_salary')?>
        <?=uif::controlGroup('text','Бонус (%)','bonus')?>
        <?=uif::controlGroup('text','Провизија (%)','commision')?>
        <?=uif::controlGroup('textarea','Квалификации','requirements')?>
        <?=uif::controlGroup('textarea','Белешка','description')?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>