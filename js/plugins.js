/**
 * Macedonian translation for bootstrap-datepicker
 * Marko Aleksic <psybaron@gmail.com>
 */
;(function($){
  $.fn.datepicker.dates['mk'] = {
    days: ["Недела", "Понеделник", "Вторник", "Среда", "Четврток", "Петок", "Сабота", "Недела"],
    daysShort: ["Нед", "Пон", "Вто", "Сре", "Чет", "Пет", "Саб", "Нед"],
    daysMin: ["Не", "По", "Вт", "Ср", "Че", "Пе", "Са", "Не"],
    months: ["Јануари", "Февруари", "Март", "Април", "Мај", "Јуни", "Јули", "Август", "Септември", "Октомври", "Ноември", "Декември"],
    monthsShort: ["Јан", "Фев", "Мар", "Апр", "Мај", "Јун", "Јул", "Авг", "Сеп", "Окт", "Ное", "Дек"],
    today: "Денес"
};
}(jQuery));

/**
 * Select2 Macedonian translation.
 * Author: Marko Aleksic <psybaron@gmail.com>
 */
(function ($) {
    "use strict";

    $.extend($.fn.select2.defaults, {
        formatNoMatches: function () { return "Нема пронајдено совпаѓања"; },
        formatInputTooShort: function (input, min) { var n = min - input.length; return "Ве молиме внесете уште " + n + " карактер" + (n == 1 ? "" : "и"); },
        formatInputTooLong: function (input, max) { var n = input.length - max; return "Ве молиме внесете " + n + " помалку карактер" + (n == 1? "" : "и"); },
        formatSelectionTooBig: function (limit) { return "Можете да изберете само " + limit + " ставк" + (limit == 1 ? "а" : "и"); },
        formatLoadMore: function (pageNumber) { return "Вчитување резултати..."; },
        formatSearching: function () { return "Пребарување..."; }
    });
})(jQuery);

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


