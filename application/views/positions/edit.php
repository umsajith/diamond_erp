<?=uif::contentHeader($heading)?>
    <?=form_open("positions/edit/{$position->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','position',$position)?>
        <?=uif::controlGroup('dropdown',':attr.department','dept_fk',[$departments,$position])?>
        <?=uif::controlGroup('text',':attr.wage','base_salary',$position)?>
        <?=uif::controlGroup('text',':attr.bonus','bonus',$position)?>
        <?=uif::controlGroup('text',':attr.commision','commision',$position)?>
        <?=uif::controlGroup('textarea',':attr.qualifications','requirements',$position)?>
        <?=uif::controlGroup('textarea',':attr.note','description',$position)?>
        <?=form_hidden('id',$position->id)?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>