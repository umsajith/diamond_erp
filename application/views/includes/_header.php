<div class="erp">
	<img src="<?php echo base_url('assets/erp_logo.png');?>" />
</div>
<div class="user_info">
	Добредојде, <?php echo anchor('#',$this->session->userdata('name')); ?>
	<br>
<div id="clock"></div>
	<?php echo anchor('logout','Одјави се'); ?>
</div>