<?=uif::contentHeader($heading)?>
        <?=form_open('resources/insert','class="form-horizontal"')?>
        <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Title','title')?>
        <?=uif::controlGroup('dropdown','Parent','parent_id',[$parents])?>
        <?=uif::controlGroup('text','Permalink','permalink')?>
        <?=uif::controlGroup('text','Folder','folder')?>
        <?=uif::controlGroup('text','Controller','controller')?>
        <?=uif::controlGroup('text','Method','method')?>
        <?=uif::controlGroup('text','Order','order')?>
        <?=uif::controlGroup('checkbox','Visible','visible',[1])?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function(){
        $("select[name=parent_id]").select2();
    });
</script>