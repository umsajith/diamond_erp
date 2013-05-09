<?=uif::contentHeader($heading)?>
	<?=form_open('cproduct/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','pcname')?>
		<?=form_close()?>
	</div>
</div>