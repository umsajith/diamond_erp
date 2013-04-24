<?=uif::contentHeader($heading)?>
	<?=form_open('roles/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','name')?>
		<?=form_close()?>
	</div>
</div>