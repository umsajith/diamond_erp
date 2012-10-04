<h2><?php echo $heading; ?></h2>
<hr>
<div id="buttons">
	<a href="<?php echo site_url('boms/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
	<a href="<?php echo site_url('boms/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
</div>
<hr>
<div class="f_left">
	<dl>
        <dt>Назив:</dt>
        <dd><?php echo $master->name;?></dd>
        <dt>Производ:</dt>
        <dd><?php echo ($master->prodname) ? $master->prodname : '-';?></dd>
        <dt>Количина:</dt>
        <dd><?php echo $master->quantity.' '.$master->uname2;?></dd>
        <dt>Конверзија:</dt>
        <dd><?php echo $master->quantity . ' ' . $master->uname2 . ' = ' .$master->quantity*$master->conversion.' '.$master->uname;?></dd>
        <dt>Внес:</dt>
        <dd><?php echo $master->dateofentry;?></dd>
        <dt>Статус:</dt>
        <dd><?php echo ($master->is_active == 1) ? 'Активен' : 'Неактивен' ;?></dd>      
	</dl>
</div>
<div class="f_right">
    <table class="master_table">
        <tr>
        	<th>Артикл</th>
        	<th>Категорија</th>
        	<th>Количина</th>
        </tr>
    <?php if (isset($details) && is_array($details) && count($details) > 0):?>
    	<?php foreach($details as $row):?>
    	<tr>
    		<td><?php echo $row->prodname;?></td>
    		<td><?php echo $row->pcname;?></td>
    		<td><?php echo round($row->quantity,6). ' ' .$row->uname;?></td>
    	</tr>
    	<?php endforeach;?>
    <?php else:?>
    	<?php $this->load->view('includes/_no_records');?>
    <?php endif;?>
    </table>
</div>