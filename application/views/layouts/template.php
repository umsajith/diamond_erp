<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $G_title . ' - ' . $heading; ?></title>  
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/favicon.ico"> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/normalize.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery_ui_theme/jquery-ui-1.8.17.custom.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/flexigrid.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/flexigrid.pack.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.pnotify.default.css" type="text/css" media="screen" />
</head>
<body>
    <?php $this->load->view('includes/_header'); ?>
	<div id="wrapper">
        <?php $this->load->view('includes/_navigation');?> 
        <?php $this->load->view('includes/_sub_modules');?>
        <!-- JQuery Loading -->
		    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
	    <!-- End of JQuery Loading -->
		<div id="content">
			<?php echo $content; ?>
		</div>
	</div>
    <?php $this->load->view('includes/_footer');?> 
    <!-- JavaScript Loading -->
    <script src="<?php echo base_url();?>js/flexigrid.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/flexigrid.pack.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/jquery.pnotify.min.js" type="text/javascript"></script> 
    <script src="<?php echo base_url();?>js/hc/highcharts.js" type="text/javascript"></script>   
    <script src="<?php echo base_url();?>js/plugins.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/scripts.js" type="text/javascript"></script> 
    <!-- End of JavaScript Loading -->  
    <?php if (strlen($this->session->flashdata('message'))): ?>
		   <script type="text/javascript">
		   		$(function(){
				   		$.pnotify({
				   			pnotify_title: "<?php echo $G_title;?>",
				   		    pnotify_text: "<?php echo $this->session->flashdata('message'); ?>",
				   		 	pnotify_type:"<?php echo $this->session->flashdata('type');?>"
				   		});
			   	});   				
		   </script>
	<?php endif; ?>
</body>
</html>