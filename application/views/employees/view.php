<h2><?php echo $heading; ?></h2>
<hr>
	<div id="meta">
        <p>бр.<?php echo $master->id;?></p>
        <p><?php echo $master->dateofentry;?></p>
	</div>
	<div id="buttons">
		<a href="<?php echo site_url('employees/edit/'.$master->id);?>" class="button"><span class="edit">Корекција</span></a>
		<a href="<?php echo site_url('employees/delete/'.$master->id);?>" class="button" id="delete"><span class="delete">Бришење</span></a>
	</div>
<hr>
<dl>
    <dt>Име и Презиме:</dt>
    <dd><?php echo $master->lname. ' '. $master->fname;?></dd>
    <dt>Датум на Раѓање:</dt>
    <dd><?php echo $master->dateofbirth;?></dd>
    <dt>Матичен Број:</dt>
    <dd><?php echo $master->ssn;?></dd>
    <dt>Пол:</dt>
    <dd><?php echo ($master->gender == 'm' ? 'Машки' : 'Женски');?></dd>
    <dt>Брачна Состојба:</dt>
    <dd><?php echo $master->mstatus;?></dd>

    <dt>Адреса:</dt>
    <dd><?php echo ($master->address == null ? '-' : $master->address);?></dd>
    <dt>Град:</dt>
    <dd><?php echo ($master->name == null ? '-' : $master->name);?></dd>
    <dt>Поштенски Код:</dt>
    <dd><?php echo ($master->postalcode == null ? '-' : $master->postalcode);?></dd>
    <dt>Телефон:</dt>
    <dd><?php echo ($master->phone == null ? '-' : $master->phone);?></dd>
    <dt>Мобилен:</dt>
    <dd><?php echo ($master->mobile == null ? '-' : $master->mobile);?></dd>
    <dt>Службен Мобилен:</dt>
    <dd><?php echo ($master->comp_mobile == null ? '-' : $master->comp_mobile);?></dd>
    <dt>Тел.Субвенција:</dt>
    <dd><?php echo ($master->comp_mobile_sub == null ? '-' : $master->comp_mobile_sub);?></dd>
    <dt>Е-меил:</dt>
    <dd><?php echo ($master->email == null ? '-' : $master->email);?></dd>

    <dt>Банка:</dt>
    <dd><?php echo ($master->bank == null ? '-' : $master->bank);?></dd>
    <dt>Број на Сметка:</dt>
    <dd><?php echo ($master->account_no == null ? '-' : $master->account_no);?></dd>

    <dt>Работно Место:</dt>
    <dd><?php echo ($master->position == null ? '-' : $master->position);?></dd>
    <dt>Почеток:</dt>
    <dd><?php echo ($master->start_date == null ? '-' : $master->start_date);?></dd>
    <dt>Крај:</dt>
    <dd><?php echo ($master->stop_date == null ? '-' : $master->stop_date);?></dd>

    <dt>Фиксна Плата:</dt>
    <dd><?php echo ($master->fixed_wage == null ? '-' : $master->fixed_wage);?></dd>
    <dt>Само Фиксна Плата:</dt>
    <dd><?php echo ($master->fixed_wage_only == '1' ? 'Да' : 'Не');?></dd>
    <dt>Придонеси:</dt>
    <dd><?php echo ($master->social_cont == null ? '-' : $master->social_cont);?></dd>
    <dt>Дистрибутер:</dt>
    <dd><?php echo ($master->is_distributer == '1' ? 'Да' : 'Не');?></dd>
    <dt>Менаџер:</dt>
    <dd><?php echo ($master->is_manager == '1' ? 'Да' : 'Не');?></dd>
    	
    <dt>Корисничка Група:</dt>
    <dd><?php echo ($master->role_name == null ? '-' : $master->role_name);?></dd>
    <dt>Корисничко Име:</dt>
    <dd><?php echo ($master->username == null ? '-' : $master->username);?></dd>

    <dt>Белешка:</dt>
    <dd><?php echo ($master->note == null ? '-' : $master->note);?></dd>   
</dl>