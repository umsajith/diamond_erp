<?=uif::contentHeader($heading)?>
	<?=form_open("roles/edit/{$result->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
		<?=uif::load('_validation')?>
		<?=uif::controlGroup('text','Назив','name',$result)?>
		<?=form_hidden('id',$result->id)?>
		<?=form_close()?>
	</div>
</div>