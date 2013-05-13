<?=uif::contentHeader($heading)?>
	<?=form_open('uom/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text',':attr.name','uname')?>
		<?=form_close()?>
	</div>
</div>