<?php if(validation_errors()):?>
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert" style="font-size:14px">&times;</button>
		<ul style="margin-bottom:0;">
			<?=validation_errors('<li>', '</li>')?>
		</ul>
	</div>
<?php endif;?>