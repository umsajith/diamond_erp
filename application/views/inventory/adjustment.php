<?=uif::contentHeader($heading,$master)?>
    <?=uif::linkDeleteButton("inventory/delete/adj/{$master->id}")?>
<hr>
<div class="row-fluid">
    <div class="span5 well well-small">  
        <dl class="dl-horizontal">
            <dt><?=uif::lng('attr.item')?>:</dt>
            <dd><?=$master->prodname?></dd>
            <dt><?=uif::lng('attr.quantity')?>:</dt>
            <dd><?=$master->quantity .' '.  $master->uname?></dd>
            <dt><?=uif::lng('attr.note')?>:</dt>
            <dd><?=$master->comments?></dd> 
            <?php if($this->session->userdata('admin')):?>
                <dt><?=uif::lng('attr.operator')?>:</dt>
                <dd><?=$master->fname. ' '.$master->lname?></dd>
            <?php endif;?>
        </dl>
    </div>
</div>