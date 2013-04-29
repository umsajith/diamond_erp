<ul class="nav nav-tabs nav-stacked">
	<!-- <li class="nav-header">Производство</li> -->
	<?php foreach ($sub_modules as $sub_module):?>
		<?php
			/*
			 *  Generates the link, by attaching
			 *  method (if present) to the controller (default)
			 */
			$link = $sub_module->controller;

			//$active = ($heading == $sub_module->title) ? true : false;
			
			if($sub_module->method)
				$link .= '/'.$sub_module->method;

			$link .= "?ref=sub_module&id={$sub_module->parent_id}";
		?>
		<li>
			<a href="<?=site_url($link)?>"><?=$sub_module->title?>
				<i class="icon-chevron-right aside-icon"></i></a>
		</li>
	<?php endforeach;?>
</ul>