/**
 * Macedonian i18n for the jQuery UI date picker plugin.
 * @author Marko Aleksic <psybaron@gmail.com>
 */
// jQuery(function($){
// 	$.datepicker.regional['mk'] = {
// 		closeText: 'Затвори',
// 		prevText: '&#x3c;',
// 		nextText: '&#x3e;',
// 		currentText: 'Денес',
// 		monthNames: ['Јануари','Февруари','Март','Април','Мај','Јуни',
// 		'Јули','Август','Септември','Октомври','Ноември','Декември'],
// 		monthNamesShort: ['Јан','Феб','Мар','Апр','Мај','Јун',
// 		'Јул','Авг','Сеп','Окт','Ное','Дек'],
// 		dayNames: ['Недела','Понеделник','Вторник','Среда','Четврток','Петок','Сабота'],
// 		dayNamesShort: ['Нед','Пон','Вто','Сре','Чет','Пет','Саб'],
// 		dayNamesMin: ['Не','По','Вт','Ср','Че','Пе','Са'],
// 		weekHeader: 'Нед',
// 		dateFormat: 'dd/mm/yy',
// 		firstDay: 1,
// 		isRTL: false,
// 		showMonthAfterYear: false,
// 		yearSuffix: ''};
// 	$.datepicker.setDefaults($.datepicker.regional['mk']);
// });

/*
 * Jeditable - jQuery in place edit plugin
 *
 * Copyright (c) 2006-2009 Mika Tuupola, Dylan Verheul
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/jeditable
 *
 * Based on editable by Dylan Verheul <dylan_at_dyve.net>:
 *    http://www.dyve.net/jquery/?editable
 *
 */
/*
 * --------------------------------------------------------------------
 * jQuery-Plugin - $.download - allows for simple get/post requests for files
 * by Scott Jehl, scott@filamentgroup.com
 * http://www.filamentgroup.com
 * reference article: http://www.filamentgroup.com/lab/jquery_plugin_for_requesting_ajax_like_file_downloads/
 * Copyright (c) 2008 Filament Group, Inc
 * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
 * --------------------------------------------------------------------
 */
 
jQuery.download = function(url, data, method){
  //url and data options required
  if( url && data ){ 
    //data can be string of parameters or array/object
    data = typeof data == 'string' ? data : jQuery.param(data);
    //split params into form inputs
    var inputs = '';
    jQuery.each(data.split('&'), function(){ 
      var pair = this.split('=');
      inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />'; 
    });
    //send request
    jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>')
    .appendTo('body').submit().remove();
  };
};


