jQuery(document).ready(function($) {
	// Colorbox controls
	$(".colorboxsite").colorbox({width:"80%", height:"80%", iframe:true});
	
	// === Resources page accordian === //
	$('.source-list .sources').hide();
	$('.source-list > h2 > a').hover(
		function () {
			$(this).addClass("hover");
		},
		function () {
			$(this).removeClass("hover");
		}).click(function (e) {
			e.preventDefault();
			$(this).toggleClass("close");
			var selected = $(this).attr('href');
			$(selected + ' .sources').slideToggle('medium');
		});
	$('.collapse').click(function (e) {
		e.preventDefault();
		var clicked = $(this).attr('href');
		$(clicked + ' .sources').slideToggle('fast');
	});
});