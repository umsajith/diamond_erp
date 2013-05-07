<?=uif::contentHeader($heading)?>
	<?=form_open("partners/edit/{$partner->id}",'class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend">Основни Информации</div>
			<?=uif::controlGroup('text','Фирма','company',$partner)?>
			<?=uif::controlGroup('text','Контакт Лице','contperson',$partner)?>
			<?=uif::controlGroup('dropdown','Припаѓа на','mother_fk',[$customers,$partner])?>
			<?=uif::controlGroup('text','Код','code',$partner)?>
			<?=uif::controlGroup('checkbox','HQ','is_mother',[1,$partner])?>		
		<div class="legend">Финансиски Информации</div>	
			<?=uif::controlGroup('text','Банка','bank',$partner)?>
			<?=uif::controlGroup('text','Број на Сметка','account_no',$partner)?>
			<?=uif::controlGroup('text','ДБ','tax_no',$partner)?>	
	</div>
	<div class="span6">
		<div class="legend">Контакт Информации</div>	
			<?=uif::controlGroup('text','Адреса','address',$partner)?>
			<?=uif::controlGroup('dropdown','Град','postalcode_fk',[$postalcodes,$partner])?>
			<?=uif::controlGroup('text','Телефон 1','phone1',$partner)?>
			<?=uif::controlGroup('text','Телефон 2','phone2',$partner)?>
			<?=uif::controlGroup('text','Факс','fax',$partner)?>
			<?=uif::controlGroup('text','Мобилен','mobile',$partner)?>
			<?=uif::controlGroup('text','И-Меил','email',$partner)?>
			<?=uif::controlGroup('text','WWW','web',$partner)?>
			<?=form_hidden('id',$partner->id)?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>