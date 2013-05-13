<?=uif::contentHeader($heading)?>
    <?=form_open('tasks/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','taskname')?>
        <?=uif::controlGroup('text',':attr.base_unit','base_unit')?>
        <?=uif::controlGroup('dropdown',':attr.uom','uname_fk',[$uoms])?>
        <?=uif::controlGroup('dropdown',':attr.bom','bom_fk',[$boms])?>
        <?=uif::controlGroup('text',':attr.price_per_uom','rate_per_unit')?>
        <?=uif::controlGroup('text',':attr.price_plus_per_uom','rate_per_unit_bonus')?>
        <?=uif::controlGroup('textarea',':attr.note','description')?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>