var dW = 0;
var dH = 0;
var main_cont_top = 0;

$(document).ready(function() {
	loop();
	setInterval(loop, 1000);

	// CSS
	//alert($("#main_menu_container").css("background-color"));
	/*$(".portfolio_img").hover(function() {
		$(this).css('border', '1px solid #ffffff');
	}, function() {
		$(this).css('border', '0 solid #ffffff');
	});*/
});

function loop(){
	dW = $(window).width();
	dH = $(window).height();
	
	var main_cont_top_tmp = dH-100;
	
	// Change position of main container if window risize
	if(main_cont_top != main_cont_top_tmp){
		main_cont_top = main_cont_top_tmp;
		$("#main_menu_container").css("top", main_cont_top+"px");
	}
	
	$("#main_menu_container_inner").css("margin-left", (dW/2-250)+"px");
	
}