<div id="header">
	<div class="erp">
		<img src="<?php echo base_url('assets/erp_logo.png');?>" />
	</div>
	<div class="user_info">
			Добредојде, <?php echo anchor('#',$this->session->userdata('name')); ?>
		<br>
		<div id="clock"></div>
			<?php echo anchor('logout','Одјави се'); ?>
		</div>
</div>
<?php $this->load->view('includes/_navigation');?> 
<?php $this->load->view('includes/_sub_modules');?>