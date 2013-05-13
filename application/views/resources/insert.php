<?=uif::contentHeader($heading)?>
        <?=form_open('resources/insert','class="form-horizontal"')?>
        <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','title')?>
        <?=uif::controlGroup('dropdown',':attr.parent','parent_id',[$parents])?>
        <?=uif::controlGroup('text',':attr.permalink','permalink')?>
        <?=uif::controlGroup('text',':attr.folder','folder')?>
        <?=uif::controlGroup('text',':attr.controller','controller')?>
        <?=uif::controlGroup('text',':attr.method','method')?>
        <?=uif::controlGroup('text',':attr.order','order')?>
        <?=uif::controlGroup('checkbox',':attr.visible','visible',[1])?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function(){
        $("select[name=parent_id]").select2();
    });
</script>