<?=uif::contentHeader($heading)?>
        <?=form_open("resources/edit/{$resource->id}",'class="form-horizontal"')?>
        <?=uif::submitButton();?>
    <hr>
<div class="row-fluid">
    <div class="span6">
        <?=uif::load('_validation')?>
        <?=uif::controlGroup('text','Title','title',$resource)?>
        <?=uif::controlGroup('dropdown','Parent','parent_id',[$parents,$resource])?>
        <?=uif::controlGroup('text','Permalink','permalink',$resource)?>
        <?=uif::controlGroup('text','Folder','folder',$resource)?>
        <?=uif::controlGroup('text','Controller','controller',$resource)?>
        <?=uif::controlGroup('text','Method','method',$resource)?>
        <?=uif::controlGroup('text','Order','order',$resource)?>
        <?=uif::controlGroup('checkbox','Visible','visible',[1,$resource])?>
        <?=form_hidden('id',$resource->id)?>
        <?=form_close()?>
    </div>
</div>
<script>
    $(function(){
        $("select[name=parent_id]").select2();
    });
</script>