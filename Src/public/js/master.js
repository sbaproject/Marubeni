// // Internet Explorer 6-11
// var isIE = /*@cc_on!@*/false || !!document.documentMode;

// // Chrome 1 - 79
// var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

// if (isIE) {
// 	document.write("<link rel='stylesheet' href='css/fixie11.css' />");
// }

//=======================================
// Show loading icon while page is loading
//=======================================

$("#popup-loading").modal('show');
$(window).on('load', function () {
	$("#popup-loading").modal('hide');
});
$(window).on('beforeunload', function () {
	if (!($("#popup-confirm").data('bs.modal') || {})._isShown) {
		$("#popup-loading").modal('show');
	}
});
	// $('form').on('submit', function (event, force) {
	//     if(!force){
	//         event.preventDefault();
	//         // $("#popup-loading").modal('show');
	//         $(this).find('[type="submit"]').prop('disabled', true);
	//         $(this).trigger('submit', true);
	//     }
	// });

$(function () {

	//=======================================
	// Sortable
	//=======================================

	// click on header table to make sorting data
	$('th.sortable').on('click', function () {
		$(this).find('a')[0].click();
	});
});