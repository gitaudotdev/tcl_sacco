/********

	VARIABLES

********************/

const fadeOutSeconds = 4000;


$(function() {
		$(".close").click(function() {
			$(".alert").hide();
		});
});

function showLoader(panel,content){
 $('#'+panel).html(content);
}

function hideLoader(panel){
 $('#'+panel).html("").hide();
}

let clearDivContent = (div) => {
 $('#'+div).html(" ");
}

let displayNotification = (panel,alert,message) => {
  clearNotificationPanel(panel);
  var content="<div class='alert alert-"+alert+"'><span>"+message+"</span></div>";  
  $('#'+panel).html(content).slideDown();
  setTimeout(function(){
    clearNotificationPanel(panel);
  },fadeOutSeconds);
}

let clearNotificationPanel = (panel) => {
  $('#'+panel).html("").slideUp();
}