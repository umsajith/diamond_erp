<?=uif::contentHeader($heading)?>
	<?=form_open('warehouses/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','wname')?>
		<?=form_close()?>
	</div>
</div>