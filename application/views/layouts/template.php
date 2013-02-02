<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $heading.' - '.$G_title; ?></title>  
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/favicon.ico'); ?>"> 
    <link rel="stylesheet" href="<?php echo base_url('css/normalize.css');?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/main.css');?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/smoothness/jquery-ui-1.10.0.custom.min');?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/pnotify.css" type="text/css');?>" media="screen" />
</head>
<body>
    <!-- JQuery Loading -->
        <script src="<?php echo base_url('js/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
    <!-- End of JQuery Loading -->

    <!-- HEADER -->
    <?php $this->load->view('includes/_header'); ?>
    <!-- END OF HEADER -->

    <div id="wrapper">
        <!-- CONTENT -->
		<div id="content">
			<?php echo $content; ?>
		</div>
        <!-- END OF CONTENT -->
	</div>

    <!-- FOOTER -->
    <?php $this->load->view('includes/_footer');?> 
    <!-- END OF FOOTER -->

    <!-- JavaScript Loading -->
    <script src="<?php echo base_url('js/pnotify.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/plugins.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/scripts.js');?>" type="text/javascript"></script> 
    <!-- End of JavaScript Loading -->

    <!-- Notifications -->
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
    <!-- End of Notifications -->

</body>
</html>