<?php if(validation_errors()):?>
	<div class="alert alert-error">
		<ul style="margin-bottom:0;">
			<?=validation_errors('<li>', '</li>')?>
		</ul>
	</div>
<?php endif;?>