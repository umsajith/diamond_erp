<?=uif::contentHeader($heading)?>
	<?=form_open('tproduct/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','ptname')?>
		<?=form_close()?>
	</div>
</div>