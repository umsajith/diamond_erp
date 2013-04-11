/**
 * Custom JS scripts used to help
 * main functionality of the application.
 * 
 * Loaded on SIAF
 * 
 * @author Marko Aleksic <psybaron@gmail.com>
 */
(function(){

		/*
		 * jClock time in header
		 */
	 	// $("#clock").jclock();	

		/*
		 * jQuery delete dialog
		 */
		// $("#delete, .del_icon").on("click",function() {
			
		// 	var link = $(this).attr('href');
		// 	var message = "Дали сте сигурни дека сакате да ја избришете оваа ставка?";
			
		// 	$("<div id='delete_dialog'>"+
		// 		"<span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>"+
		// 		message+"</div>")
		// 			.dialog({ 
		// 				modal: true,
		// 				resizable: false,
		// 				position: 'center',
		// 				title:'Бришење',
		// 				buttons: {
		// 					"Откажи": function() { $(this).remove(); },	
		// 					"Бриши": function() { window.location.href = link;},
		// 			 	}
		// 			});
		// 	return false;
			
		// });	
		
		//Disable the form Submit button, preventing multiple inserts
		$('form').submit(function() {
		    $(this).submit(function() {
		        return false;
		    });
		    return true;
		});
		
		/*
		 * Check All checbox
		 */
		$(".check_all").on('click',function(){
			$(this).parents('table.data-grid').find(':checkbox').attr('checked', this.checked);
		});

})();

$(document).on("click",".confirm-delete", function(e) {
	bootbox.animate(false);
    bootbox.confirm("Sure you want to proceed?", function(result) {
        if(result){window.location.href = e.target.href;}
    });
    return false;
});

$(document).keypress(function(e){
	/*
	 * If keyboard key "v" is pressed (#118)
	 * Redirects to Insert page
	 */
	  if(e.charCode == 118){  
		var link = $('span.add').parent().attr('href');
		
		if(link !== undefined)
			location.replace(link);
	  }
	  
	  /*
	   * If keyboard key "enter" is pressed (#13)
	   * Form submited to default action
	   */
	  if(e.charCode == 13){  
			$('form').submit(function(){});
	  }
});