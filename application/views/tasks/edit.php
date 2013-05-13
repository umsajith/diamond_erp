<?=uif::contentHeader($heading)?>
    <?=form_open("tasks/edit/{$task->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','taskname',$task)?>
        <?=uif::controlGroup('text',':attr.base_unit','base_unit',$task)?>
        <?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms,$task])?>
        <?=uif::controlGroup('dropdown',':attr.bom','bom_fk',[$boms,$task])?>
        <?=uif::controlGroup('text',':attr.price_per_uom','rate_per_unit',$task)?>
        <?=uif::controlGroup('text',':attr.price_plus_per_uom','rate_per_unit_bonus',$task)?>
        <?=uif::controlGroup('textarea',':attr.note','description',$task)?>
        <?=form_hidden('id',$task->id)?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>