<?=uif::contentHeader($heading)?>
	<?=form_open('departments/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','department')?>
		<?=form_close()?>
	</div>
</div>