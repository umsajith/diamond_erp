<ul>
<?php foreach ($sub_modules as $sub_module):?>
	<?php
		/*
		 *  Generates the link, by attaching
		 *  method (if present) to the controller (default)
		 */
		$link = $sub_module->controller;
		
		if($sub_module->method)
			$link .= '/'.$sub_module->method;

		$link .= "?ref=sub_module&id={$sub_module->parent_id}";
	?>
	<li><?php echo anchor($link,$sub_module->title);?></li>
	<?php endforeach;?>
</ul>
