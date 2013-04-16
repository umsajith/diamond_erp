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
		console.log(e);
		bootbox.animate(false);
	    bootbox.confirm("Sure you want to proceed?", function(result) {
	        if(result){window.location.href = e.target.href;}
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

// $(document).keypress(function(e){
// 	/*
// 	 * If keyboard key "v" is pressed (#118)
// 	 * Redirects to Insert page
// 	 */
// 	  if(e.charCode == 118){  
// 		var link = $('a.insert').attr('href');
		
// 		if(link !== undefined)
// 			location.replace(link);
// 	  }
	  
// 	  /*
// 	   * If keyboard key "enter" is pressed (#13)
// 	   * Form submited to default action
// 	   */
// 	  if(e.charCode == 13){  
// 			$('form').submit(function(){});
// 	  }
// });

//Diamond ERP API global object
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

	obj.completeJobOrders = function(url){

		var ids = $(".job-order:checked").map(function(i,n) {
	        return $(n).val();
	    }).get();

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		}, 'json');
	}

	obj.dropdownTasks = function(url, task_fk){

		var data;
		var tasks = $("select#tasks");
		var employees = $("select#employee");
		var uname = $("input#uname");
		var task = $("input[name=task_fk]");
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
					options += '<option value="' + row.id + '" data-uname="'+ row.uname +'">' + row.taskname + '</option>';
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

	obj.lockOrderList = function(url){
		var ids = $(".order-list:checked").map(function(i,n) {
	        return $(n).val();
	    }).get();

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		}, 'json');
	}

	obj.unlockOrderList = function(url){
		var ids = $(".order-list:checked").map(function(i,n) {
	        return $(n).val();
	    }).get();

		if(ids.length == 0){
			this.notify("Потребно е да селектирате барем една ставка");
			return false;
		}

		$.post(url,{ids:JSON.stringify(ids)}, function(data) {
		  if(data) location.reload(true);
		}, 'json');
	}

	obj.datepicker = function(field, options){

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var args = {
			autoclose: true,
			language: 'mk',
			weekStart: 1
		};

		if(options !== undefined){
			(options.future) ? args.startDate = now : args.endDate = now;
		}

		$(field).datepicker(args);
	}

	// obj.dateRange = function(field1, field2){

	// 	var nowTemp = new Date();
	// 	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		 
	// 	var f1 = $(field1).datepicker({
	// 	  onRender: function(date) {
	// 	    return date.valueOf() < now.valueOf() ? 'disabled' : '';
	// 	  }
	// 	}).on('changeDate', function(ev) {
	// 	  if (ev.date.valueOf() > f2.date.valueOf()) {
	// 	    var newDate = new Date(ev.date)
	// 	    newDate.setDate(newDate.getDate() + 1);
	// 	    f2.setValue(newDate);
	// 	  }
	// 	  f1.hide();
	// 	  $(field2)[0].focus();
	// 	}).data('datepicker');
	// 	var f2 = $(field2).datepicker({
	// 	  onRender: function(date) {
	// 	    return date.valueOf() <= f1.date.valueOf() ? 'disabled' : '';
	// 	  }
	// 	}).on('changeDate', function(ev) {
	// 	  f2.hide();
	// 	}).data('datepicker');
	// }

	return obj;
})();