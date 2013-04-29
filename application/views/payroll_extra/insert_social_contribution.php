<?=uif::contentHeader($heading)?>
    <?=form_open('payroll_extra/insert_social_contribution','class="form-horizontal"')?>
    <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('datepicker','Датум','for_date')?>
        <?=uif::controlGroup('dropdown','Работник','employee_fk',[$employees])?>
        <?=uif::controlGroup('text','Износ','amount')?>
        <?=uif::controlGroup('textarea','Белешка','description')?>
     <?=form_close()?>
    </div>
</div>
<script>
    $(function() {
        $("select[name=employee_fk]").select2();
        $("select[name=payroll_extra_cat_fk]").select2();
        cd.datepicker("input[name=for_date]");
    });
</script>