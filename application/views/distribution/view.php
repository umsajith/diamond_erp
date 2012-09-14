<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
			<p>бр.<?php echo $master->id;?></p>
			<p><?php echo $master->dateofentry;?></p>
		</div>
	<div id="buttons">
		<a href="<?php echo site_url('distribution/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('distribution/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>	
<hr>
	<dl>
        <dt>Производ:</dt>
        <dd><?php echo $master->prodname;?></dd>
        <dt>Количина:</dt>
        <dd><?php echo $master->quantity .' '.  $master->uname;?></dd> 
        <dt>Датум:</dt>
        <dd><?php echo ($master->dateoforigin == null) ? '-' : mdate('%d/%m/%Y',mysql_to_unix($master->dateoforigin)); ?></dd>         
        <dt>Дистрибутер:</dt>
        <dd><?php echo ($master->distributor_fk == null ? '-' : $master->fname. ' '.$master->lname); ?></dd> 
        <dt>Документ:</dt>
        <dd><?php echo ($master->ext_doc == null ? '-' : $master->ext_doc); ?></dd> 
        <dt>Оператор:</dt>
        <dd><?php echo $master->assignfname .' '.  $master->assignlname;?></dd>
        <dt>Забелешка:</dt>
        <dd><?php echo ($master->note) ? $master->note : '-';?></dd>
	</dl>
<hr/>
<?php if (isset($details) && is_array($details) && count($details)):?>
	<h3>Употребени Сировини</h3>
	<table class="master_table">
    <tr>
    	<th>Производ</th>
    	<th>Категорија</th>
    	<th>Количина</th>
    </tr>
	<?php foreach($details as $row):?>
	<tr>
		<td><?php echo $row->prodname;?></td>
		<td><?php echo $row->pcname;?></td>
		<td><?php echo $row->quantity. ' ' .$row->uname;?></td>
	</tr>
	<?php endforeach;?>
	</table>
<?php elseif(isset($details) && !count($details)):?>
	<?php echo 'Нема дефинирано норматив за овој прозивод!';?>
<?php endif;?>
<?php $this->load->view('includes/_del_dialog');?>