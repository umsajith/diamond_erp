<?=uif::contentHeader($heading)?>
	<?=form_open('partners/insert','class="form-horizontal"')?>
    <?=uif::submitButton();?>
	<hr>
<div class="row-fluid">
	<div class="span6">
			<?=uif::load('_validation')?>
		<div class="legend">Основни Информации</div>
			<?=uif::controlGroup('text','Фирма','company')?>
			<?=uif::controlGroup('text','Контакт Лице','contperson')?>
			<?=uif::controlGroup('dropdown','Припаѓа на','mother_fk',[$customers])?>
			<?=uif::controlGroup('text','Код','code')?>
			<?=uif::controlGroup('checkbox','HQ','is_mother',[1])?>		
		<div class="legend">Финансиски Информации</div>	
			<?=uif::controlGroup('text','Банка','bank')?>
			<?=uif::controlGroup('text','Број на Сметка','account_no')?>
			<?=uif::controlGroup('text','ДБ','tax_no')?>	
	</div>
	<div class="span6">
		<div class="legend">Контакт Информации</div>	
			<?=uif::controlGroup('text','Адреса','address')?>
			<?=uif::controlGroup('dropdown','Град','postalcode_fk',[$postalcodes])?>
			<?=uif::controlGroup('text','Телефон 1','phone1')?>
			<?=uif::controlGroup('text','Телефон 2','phone2')?>
			<?=uif::controlGroup('text','Факс','fax')?>
			<?=uif::controlGroup('text','Мобилен','mobile')?>
			<?=uif::controlGroup('text','И-Меил','email')?>
			<?=uif::controlGroup('text','WWW','web')?>
	<?=form_close()?>
	</div>
</div>
<script>
	$(function() {
		$("select").select2();
	});
</script>