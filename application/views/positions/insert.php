<?=uif::contentHeader($heading)?>
    <?=form_open('positions/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','position')?>
        <?=uif::controlGroup('dropdown',':attr.department','dept_fk',[$departments])?>
        <?=uif::controlGroup('text',':attr.wage','base_salary')?>
        <?=uif::controlGroup('text',':attr.bonus','bonus')?>
        <?=uif::controlGroup('text',':attr.commision','commision')?>
        <?=uif::controlGroup('textarea',':attr.qualifications','requirements')?>
        <?=uif::controlGroup('textarea',':attr.note','description')?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select").select2();
    });
</script>