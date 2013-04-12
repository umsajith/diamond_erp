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

	//Pnotify function for displaying notifications
	obj.notify = function(text, type){
		//Change Pnotify defaults settings
		$.pnotify.defaults.title = "Diamond ERP";

		$.pnotify.defaults.sticker = false;

		$.pnotify.defaults.delay = 1750;

		//Pnotify options setter
		var pnotify_opt = {
			text: text,
			type: type,
			shadow: false,
			opacity: .9
		};
		//Display the Pnotify dialog
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

	return obj;
})();