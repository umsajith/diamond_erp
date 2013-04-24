<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkDeleteButton("inventory/adj/delete/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt>Артикл:</dt>
            <dd><?=$master->prodname?></dd>
            <dt>Количина:</dt>
            <dd><?=$master->quantity .' '.  $master->uname?></dd>
            <dt>Причина:</dt>
            <dd><?=$master->comments?></dd> 
            <?php if($this->session->userdata('admin')):?>
                <dt>Оператор:</dt>
                <dd><?=$master->fname. ' '.$master->lname?></dd>
            <?php endif;?>
        </dl>
    </div>
</div>