/**
 * Custom JS scripts used to help
 * main functionality of the application.
 * 
 * Loaded on SIAF
 * 
 * @author Marko Aleksic <psybaron@gmail.com>
 */
(function(){

	$(document).on("click",".check-all", function(e) {
		$(this).closest('table.data-grid').find('input[type=checkbox]').prop('checked', this.checked);
	});

	$(document).on("click",".confirm-delete", function(e) {
		var okBtn = "Продолжи";
		var cnlBtn = "Откажи";
		var text = '\
				<h4 class="text-error">Внимание!</h4>\
				<hr>\
			<div class="alert alert-error">\
				<i class="icon-warning-sign"></i>\
				Ставката која сакате да ја избришете <strong>НЕМОЖЕ</strong> да биде повратена!\
			</div>';

		bootbox.animate(false);	
	    bootbox.confirm(text, cnlBtn, okBtn, function(result) {
	    	targetLink = e.target.href;
	    	if(targetLink === undefined){
	    		targetLink = $(e.target).data('link');
	    	}
	        if(result){window.location.href = targetLink;}
	    });
	    return false;
	});
		
	//Prevents Form re-submission
	$('form').submit(function() {
	    $(this).submit(function() {
	        return false;
	    });
	    return true;
	});

})();

/**
 * Diamond CD Global Object
 */
var cd = (function(){

	var obj = {};

	obj.notify = function(text, type){

		$.pnotify.defaults.title = "Diamond ERP";
		$.pnotify.defaults.sticker = false;
		$.pnotify.defaults.delay = 1750;

		var pnotify_opt = {
			text: text,
			type: type,
			shadow: false,
			opacity: .9
		};
		$.pnotify(pnotify_opt);
	};
	/**
	 * Create select2 dropdown list
	 * @param  {DOM selector} selector
	 * @param  {string} placeholder 
	 * @param  {Object} args        Additional options
	 */
	obj.dd = function(selector, placeholder, args)
	{
		$(selector).select2({
			placeholder: placeholder,
			allowClear: true
		});
	}

	obj.receivePurchaseOrders = function(url, id){
		if(!id) {
			var ids = $(".purchase-order:checked").map(function(i,n) {
	        	return $(n).val();
	    	}).get();
		} else ids = id;

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		});
	}
	/**
	 * Completes all selected(checked) Job Orders
	 * @param  {String} url AJAX API URL
	 */
	obj.completeJobOrders = function(url, id){

	    if(!id) {
			var ids = $(".job-order:checked").map(function(i,n) {
	        	return $(n).val();
	    	}).get();
		} else ids = id;

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		});
	}
	////////////////////////////////////////////
	// DECPRICATED FUNCTION - use ddProducts //
	////////////////////////////////////////////
	obj.dropdownProducts = function(url, options){

		var data;
		var product = $("input[name=prodname_fk]");
		var products = $("select#products");
		var uom = $("#uom"); 
		var category = $("#category");
		var firstRun = true;

		products.select2();

		if(options !== undefined && options.prodname_fk){
			var prodname_fk = options.prodname_fk;
		}
		
		$.getJSON(url, options, function(result) {
			data = result;
			var opts = '<option></option>';
			$.each(result, function(i, row){
				if((row.id === prodname_fk) && firstRun){
	    			products.select2('data',{id:row.id,text:row.prodname});
	    			uom.val(row.uname);
	    			category.val(row.pcname);
	    			firstRun = false;
				}
				opts += '<option value="' + row.id + '">' + row.prodname + '</option>';
			});
			products.html(opts);
		});
		/*
		 * When product is changed, populates the UOM and Category
		 * of corresponding product into field (disabled or add-on)
		 *
		 */
		products.on("change",function(e) {
			product.val($(this).val());
			if(e.val !== ''){
				uom.val(data[this.selectedIndex-1].uname);  
				category.val(data[this.selectedIndex-1].pcname);  
			} else {
				uom.val('');
				category.val('');
			}
		});
	}

	obj.ddProducts = function(url, options){

		var data;
		var current = null;
		var placeholder = (options.placeholder !== undefined) ? options.placeholder : '';
		var product = $(options.hidden);
		var products = $(options.select);
		var uom = $(options.aux1); 
		var category = $(options.aux2);
		var firstRun = true;

		if(options !== undefined && options.prodname_fk){
			var prodname_fk = options.prodname_fk;
		}
		
		$.getJSON(url, options.args, function(result) {
			data = result;
			var opts = '<option></option>';
			$.each(result, function(i, row){
				if((row.id === prodname_fk) && firstRun){
					current = {id:row.id, text:row.prodname};
	    			uom.val(row.uname);
	    			category.val(row.pcname);
	    			firstRun = false;
				}
				opts += '<option value="' + row.id + '">' + row.prodname + '</option>';
			});
			products.html(opts).select2({placeholder:placeholder});
			if(current !== null){
				products.select2('data',current);
			}
		});
		/*
		 * When product is changed, populates the UOM and Category
		 * of corresponding product into field (disabled or add-on)
		 *
		 */
		products.on("change",function(e) {
			products.val($(this).val());
			if(e.val !== ''){
				uom.html(data[this.selectedIndex-1].uname).val(data[this.selectedIndex-1].uname);
				category.html(data[this.selectedIndex-1].pcname).val(data[this.selectedIndex-1].pcname);
			} else {
				uom.html('').val('');
				category.html('').val('');
			}
		});
	}

	obj.cascadeEmployeesTasks = function(url, task_fk){

		var data;
		var task = $("input[name=task_fk]");
		var tasks = $("select#tasks");
		var employees = $("select#employee");
		var uname = $("input#uname");
		var firstRun = true;
		/*
		 * When an employee is changed, searches the tasks assigned
		 * to this employee, and populates the dropdown
		 *
		 */
		employees.on("change",function() {
			var employee = $(this).val();
			tasks.select2('data','');	
		    tasks.select2("enable");
			$.getJSON(url,{employee:employee}, function(result) {
				data = result;
				var options = '<option></option>';
				$.each(result, function(i, row){
					if((row.id === task_fk) && firstRun){
		    			tasks.select2('data',{id:row.id,text:row.taskname});
		    			uname.val(row.uname);
		    			firstRun = false;
					}
					options += '<option value="' + row.id + '">' + row.taskname + '</option>';
				});
				tasks.html(options);
			});	
		});
		
		/*
		 * When task is changed, populates the hidden task ID 
		 *	and unit of measure of the same task
		 */	
		tasks.on("change",function(e) {		
			task.val($(this).val());
			if(e.val !== ''){
				uname.val(data[this.selectedIndex-1].uname);  
			} else {
				uname.val('');
			}
		});
	}

	obj.lockOrderList = function(url, id){

		if(!id) {
			var ids = $(".order-list:checked").map(function(i,n) {
	        	return $(n).val();
	    	}).get();
		} else ids = id;

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		});
	}

	obj.unlockOrderList = function(url, id){
		if(!id) {
			var ids = $(".order-list:checked").map(function(i,n) {
	        	return $(n).val();
	    	}).get();
		} else ids = id;

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		});
	}

	obj.insertPayroll = function(url){

		var payrollData = $("form#payroll-data").serialize();

		$("#insert-payroll").prop('disabled', true);
		$("#insert-payroll i").attr('class','icon-spinner icon-spin');

		$.post(url, payrollData, function(data){
			if(data){
				location.replace(data);
			}else {
				this.notify("Проблем при внесување на плата. Обидете се повторно.");
				location.reload(true);
			}
		});  
	}
	/**
	 * Creates a Twitter Bootstrap Datepicker
	 * on given input field
	 * @param  {string} field   DOM Element
	 * @param  {Object} options Datepicker Options
	 */
	obj.datepicker = function(field, options){

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var args = {
			format: "yyyy-mm-dd",
			autoclose: true,
			language: 'mk',
			weekStart: 1
		};

		if(options !== undefined){
			(options.future) ? args.startDate = now : args.endDate = now;
		}

		$(field).datepicker(args);
	}

	obj.dateRange = function(field1, field2){

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		 
		var f1 = $(field1).datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			language: 'mk',
			weekStart: 1,
			onRender: function(date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  if (ev.date.valueOf() > f2.date.valueOf()) {
		    var newDate = new Date(ev.date)
		    newDate.setDate(newDate.getDate() + 1);
		    f2.setValue(newDate);
		  }
		  f1.hide();
		  $(field2)[0].focus();
		}).data('datepicker'); 
		var f2 = $(field2).datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			language: 'mk',
			onRender: function(date) {
				return date.valueOf() <= f1.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
		  f2.hide();
		}).data('datepicker');
	}
	/**
	 * Submits give form by DOM selector
	 * @param  {string} form DOM selector
	 */
	obj.submit = function(form){
		$(form).submit();
	}
	/**
	 * Generates PDF and downloads it
	 * @param  {string} url  
	 * @param  {string} form DOM selector
	 */
	obj.generatePdf = function(url, form){
		$.download(url,$(form).serialize());
	}
	/**
	 * Checks if supplied number is integer or decimal, 
	 * greater than or equal to 0
	 * @param  {integer / decimal}  n
	 * @return {Boolean}
	 */
	obj.isNumber = function(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n) && n>=0;
	}

	return obj;
})();