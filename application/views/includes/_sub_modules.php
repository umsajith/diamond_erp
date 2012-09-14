<div id="modnav">
	<ul>
	<?php foreach ($nav_smodules as $sub_module):?>
	<?php
		/*
		 *  Generates the link, by attaching
		 *  method (if present) to the controller (default)
		 */
		$link = $sub_module->controller;
		
		if($sub_module->method && $sub_module != '')
			$link .= '/'.$sub_module->method;
	?>
		<li><?php echo anchor($link,$sub_module->title);?></li>
		<?php endforeach;?>
	</ul>
</div>