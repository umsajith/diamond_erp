<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
		<p>бр.<?php echo $master->id;?></p>
		<p><?php echo $master->dateofentry;?></p>
	</div>
	<div id="buttons">
		<a href="<?php echo site_url('inventory/delete/adj/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>	
<hr>
<dl>
	<?php if($this->session->userdata('admin')):?>
        <dt>Оператор:</dt>
        <dd><?php echo $master->lname. ' '.$master->fname;?></dd>
    <?php endif;?>
    <dt>Артикл:</dt>
    <dd><?php echo $master->prodname;?></dd>
    <dt>Количина:</dt>
    <dd><?php echo $master->quantity .' '.  $master->uname;?></dd>
    <dt>Причина:</dt>
    <dd><?php echo $master->comments;?></dd> 
    <?php if($this->session->userdata('admin')):?>
        <dt>Оператор:</dt>
        <dd><?php echo $master->fname. ' '.$master->lname;?></dd>
    <?php endif;?>
</dl>