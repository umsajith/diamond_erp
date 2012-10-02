<div id="navigation">
	<ul>
	<?php foreach($this->session->userdata('nav_modules') as $module):?>
	<?php
		/*
		 *  Generates the link, by attaching
		 *  method (if present) to the controller (default)
		 */
		$link = '';
		if($module->folder AND $module != '')
		{
			$link = $module->folder.'/';
		}	
	
		$link .= $module->controller;
		/*
		  	If current controller is active, marks it as down
		 
			$active = '';
			if($module->permalink ==  $this->router->class)
			{	
				$active = 'down';	
				$link = '#';
			}
		*/
	?>
		<li><?php echo anchor($link,$module->title,"class='split'");?></li>
		<?php endforeach;?>
	</ul>
</div>