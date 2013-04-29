<ul class="nav">
	<?php foreach($this->session->userdata('modules') as $module):?>
		<?php

		$module_title = $module->title;
		/*
		 *  Generates the link, by attaching
		 *  method (if present) to the controller (default)
		 */
		$link = '';

		if($module->folder)
			$link = $module->folder.'/';	

		$link .= $module->controller;

		if($module->method)
			$link .= '/'.$module->method;

		$link .= "?ref=module&id={$module->id}";
		
	  	//If current controller is active, marks it as down
		$active = '';
		
		if($module->id ==  $this->input->get('id'))
		{	
			$active = "class='active'";	
		}

		?>
		<li <?=$active?>><?php echo anchor($link,$module->title);?></li>
	<?php endforeach;?>
</ul>