<?=uif::contentHeader($heading)?>
	<?=form_open("warehouses/edit/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','wname',$result)?>
		<?=form_hidden('id',$result->id); ?>
		<?=form_close()?>
	</div>
</div>