<?php if(validation_errors()):?>
	<div class="alert alert-error">
		<?=validation_errors('<li>', '</li>')?>
	</div>
<?php endif;?>