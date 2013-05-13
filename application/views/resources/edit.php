<?=uif::contentHeader($heading)?>
        <?=form_open("resources/edit/{$resource->id}",'class="form-horizontal"')?>
        <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text',':attr.name','title',$resource)?>
        <?=uif::controlGroup('dropdown',':attr.parent','parent_id',[$parents,$resource])?>
        <?=uif::controlGroup('text',':attr.permalink','permalink',$resource)?>
        <?=uif::controlGroup('text',':attr.folder','folder',$resource)?>
        <?=uif::controlGroup('text',':attr.controller','controller',$resource)?>
        <?=uif::controlGroup('text',':attr.method','method',$resource)?>
        <?=uif::controlGroup('text',':attr.order','order',$resource)?>
        <?=uif::controlGroup('checkbox',':attr.visible','visible',[1,$resource])?>
        <?=form_hidden('id',$resource->id)?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function(){
        $("select[name=parent_id]").select2();
    });
</script>