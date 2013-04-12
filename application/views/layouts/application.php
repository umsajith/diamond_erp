<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?=$heading.' - '.$G_title?></title>  
    <link rel="icon" type="image/png" href="<?=base_url('favicon.ico')?>"> 
    <link rel="stylesheet" href="<?=base_url('css/normalize.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('css/smoothness/jquery-ui-1.10.0.custom.min.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css" type="text/css')?>">
    <link rel="stylesheet" href="<?=base_url('css/font-awesome.min.css" type="text/css')?>">
    <link rel="stylesheet" href="<?=base_url('css/select2.css" type="text/css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/select2-bootstrap.css" type="text/css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/pnotify.default.css" type="text/css')?>"/>
    <link rel="stylesheet" href="<?=base_url('css/main.css')?>" media="screen"/>
</head>
<body>
    <!-- JQUERY LOADING -->
    <script src="<?=base_url('js/jquery.js')?>"></script>
    <script src="<?=base_url('js/jquery-ui.js')?>"></script>
    <!-- END OF JQUERY LOADING -->

    <!-- NAV BAR -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <div class="brand" id="diamond-logo">
                    <img src="<?php echo base_url('img/diamond_erp_logo_30.png');?>" alt="">
                </div>
                <div class="nav-collapse collapse">
                    <?php $this->load->view('includes/_navigation'); ?>
                </div>
                <div class="pull-right">
                    <!-- <button href="#" class="btn btn-info"></button> -->
                    <a href="<?=site_url('logout')?>" class="btn btn-info" alt="Sign Out"><i class="icon-signout"></i></a>
                </div>
            </div>
            
        </div> 
    </nav>
    <!-- END OF NAV BAR -->

    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row-fluid">
            <aside class="span2">
                <div class="sidebar-nav">
                    <?php $this->load->view('includes/_sub_modules'); ?>
                </div>
            </aside>

            <div class="span10" role="main">
                <?=$yield?>
                <?php $this->load->view('includes/_pagination');?>
            </div>
        </div>
    </div>
    <!-- END OF MAIN CONTENT -->

    <!-- FOOTER -->
    <footer>
        <?php $this->load->view('includes/_footer');?>
    </footer>
    <!-- END OF FOOTER -->

    <!-- JAVASCRIPT LOADING -->
    <script src="<?=base_url('js/modernizr.js')?>"></script>
    <script src="<?=base_url('js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('js/bootbox.min.js')?>"></script> 
    <script src="<?=base_url('js/pnotify.min.js')?>"></script>
    <script src="<?=base_url('js/select2.js')?>"></script>
    <script src="<?=base_url('js/plugins.js')?>"></script>
    <script src="<?=base_url('js/scripts.js')?>"></script> 
    <!-- END OF JAVASCRIPT LOADING -->

    <?php if(strlen($this->session->flashdata('message'))): ?>
       <script>
            cd.notify("<?=$this->session->flashdata('message')?>","<?=$this->session->flashdata('type')?>");                
       </script>
    <?php endif; ?>
</body>
</html>