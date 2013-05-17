<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title><?=$heading.' &raquo; '.$glAppTitle?></title>  
    <link rel="icon" type="image/png" href="<?=base_url('favicon.ico')?>"> 
    <link rel="stylesheet" href="<?=base_url('css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('css/select2.css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/select2bs.css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/typeahead.css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/datepicker.css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/editable.css')?>" />
    <link rel="stylesheet" href="<?=base_url('css/pnotify.default.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('css/main.css')?>" media="screen"/>
</head>
<body>
    <!-- JQUERY LOADING -->
    <script src="<?=base_url('js/jquery.js')?>"></script>
    <!-- END OF JQUERY LOADING -->

    <!-- HEADER BAR -->
        <?=uif::load('_header')?>
    <!-- END OF HEADER BAR -->

    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row-fluid">
            <aside class="span2">
                <div class="sidebar-nav">
                    <?=uif::load('_sub_modules')?>
                </div>
            </aside>

            <div class="span10" role="main">
                <?=$yield?>
                <?=uif::load('_pagination')?>
            </div>
        </div>
    </div>
    <!-- END OF MAIN CONTENT -->

    <!-- FOOTER -->
    <footer>
        <?=uif::load('_footer')?>
    </footer>
    <!-- END OF FOOTER -->

    <!-- JAVASCRIPT LOADING -->
    <script src="<?=base_url('js/modernizr.js')?>"></script>
    <script src="<?=base_url('js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('js/datepicker.js')?>"></script>
    <script src="<?=base_url('js/bootbox.min.js')?>"></script> 
    <script src="<?=base_url('js/pnotify.min.js')?>"></script>
    <script src="<?=base_url('js/select2.js')?>"></script>
    <script src="<?=base_url('js/typeahead.min.js')?>"></script>
    <script src="<?=base_url('js/editable.min.js')?>"></script>
    <script src="<?=base_url('js/plugins.js')?>"></script>
    <script src="<?=base_url('js/scripts.js')?>"></script> 
    <!-- END OF JAVASCRIPT LOADING -->

    <script>
        //Populating various variables used by CD global object
        cd.air = {
            app_title : "<?=$glAppTitle?>",
            select_at_least_one : "<?=uif::lng('air.select_at_least_one')?>",
            unsuccessful_action : "<?=uif::lng('air.error')?>",
            locale : "<?=$glLocale?>"
        };
    </script>

    <?php if(strlen($this->session->flashdata('message'))): ?>
       <script>
            //Listens for flash data and displays notifications with appropriate color flag
            cd.notify("<?=$this->session->flashdata('message')?>","<?=$this->session->flashdata('type')?>");                
       </script>
    <?php endif; ?>

    <script>
        //Delete Dialog
        $(document).on("click",".confirm-delete", function(e) {
            var okBtn = "<?=uif::lng('common.ok')?>";
            var cnlBtn = "<?=uif::lng('common.cancel')?>";
            var text = "\
                    <h4 class='text-error'><?=uif::lng('common.warning')?></h4>\
                    <hr>\
                <div class='alert alert-error'>\
                    <i class='icon-warning-sign'></i>\
                    <?=uif::lng('common.action_irreversible')?>\
                </div>";
            bootbox.animate(false); 
            bootbox.confirm(text, cnlBtn, okBtn, function(result) {
                targetLink = e.target.href;
                if(targetLink === undefined){
                    targetLink = $(e.target).data('link');
                }
                if(result){window.location.href = targetLink;}
            });
            return false;
        });
    </script>

</body>
</html>