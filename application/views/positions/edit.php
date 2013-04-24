<?=uif::contentHeader($heading)?>
    <?=form_open("positions/edit/{$position->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Назив','position',$position)?>
        <?=uif::controlGroup('dropdown','Сектор','dept_fk',[$departments,$position])?>
        <?=uif::controlGroup('text','Основна Плата','base_salary',$position)?>
        <?=uif::controlGroup('text','Бонус (%)','bonus',$position)?>
        <?=uif::controlGroup('text','Провизија (%)','commision',$position)?>
        <?=uif::controlGroup('textarea','Квалификации','requirements',$position)?>
        <?=uif::controlGroup('textarea','Белешка','description',$position)?>
        <?=form_hidden('id',$position->id); ?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>