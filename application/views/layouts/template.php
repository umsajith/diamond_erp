<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $heading.' - '.$G_title; ?></title>  
    <link rel="icon" type="image/png" href="<?php echo base_url('favicon.ico'); ?>"> 
    <link rel="stylesheet" href="<?php echo base_url('css/normalize.css');?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/smoothness/jquery-ui-1.10.0.custom.min.css');?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/pnotify.css" type="text/css');?>" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css" type="text/css');?>">
    <link rel="stylesheet" href="<?php echo base_url('css/font-awesome.min.css" type="text/css');?>">
    <link rel="stylesheet" href="<?php echo base_url('css/main.css');?>" type="text/css" media="screen" />

</head>
<body>
    <!-- JQuery Loading -->
    <script src="<?php echo base_url('js/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
    <!-- End of JQuery Loading -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <div class="brand" id="diamond-logo">
                    <img src="<?php echo base_url('img/diamond_erp_logo_30.png');?>" alt="">
                </div>
                <div class="nav-collapse collapse">
                    <?php $this->load->view('includes/_navigation'); ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- HEADER -->
    <!-- <header>
        <?php //$this->load->view('includes/_header'); ?>
    </header> -->
    <!-- END OF HEADER -->

    <!-- NAVINGATION AND SUBNAVIGATION -->
    <!-- <nav>
        <?php //$this->load->view('includes/_navigation');?> 
    </nav> -->
    <div class="container-fluid">
        <div class="row-fluid">
            <aside class="span2">
                <div class="sidebar-nav">
                    <?php $this->load->view('includes/_sub_modules'); ?>
                </div>
            </aside>

            <div class="span10" role="main">
                <?=$content?>
                <?php $this->load->view('includes/_pagination');?>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <footer>
        <?php $this->load->view('includes/_footer');?>
    </footer>
    <!-- END OF FOOTER -->

    <!-- JavaScript Loading -->
    <script src="<?php echo base_url('js/modernizr.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/pnotify.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/plugins.js');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/scripts.js');?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/bootstrap.min.js');?>" type="text/javascript"></script> 
    <!-- End of JavaScript Loading -->

    <?php if (strlen($this->session->flashdata('message'))): ?>
           <script>
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