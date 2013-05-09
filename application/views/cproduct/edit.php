<?=uif::contentHeader($heading)?>
	<?=form_open("cproduct/edit/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','pcname',$result)?>
		<?=form_hidden('id',$result->id); ?>
		<?=form_close()?>
	</div>
</div>