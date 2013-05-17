<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <div class="brand" id="diamond-logo">
                <img src="<?=base_url('img/erp_logo.png')?>" alt="">
            </div>
            <div class="nav-collapse collapse">
                <?=uif::load('_navigation')?>
            </div>
            <div class="pull-right">
                <?=uif::linkButton('logout','icon-signout','info')?>
            </div>
        </div>
        
    </div> 
</nav>