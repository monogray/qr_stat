var dW = 0;
var dH = 0;
var main_cont_margin_left = 0;

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
	
	// Click by portfolio image
	$(".portfolio_img").click(function() {
		// If image not open for viewing
		if($(this).css("position") != "fixed"){
			$("#portfolio_item_invisible_background").css("display", "block");
			
			var _cur_prop = $(this).width()/$(this).height()
			var _h = $(window).height()*0.9;
			
			$(this).css("position", "fixed");
			$(this).css("left", $(this).position().left);
			$(this).css("top", $(this).position().top);
			
			var _new_w = _h*_cur_prop;
			var _cur_new_w = $(window).width()/2 - _new_w/2;
		
			$(this).animate({
				top:	20,
				left:	_cur_new_w,
				height:	_h
			}, 200, function() {
				// Animation complete.
			});
		}else{		// If image currently viewing
			$("#portfolio_item_invisible_background").css("display", "none");
			
			$(this).css("position", "static");
			$(this).animate({
				height:	200
			}, 200, function() {
				// Animation complete.
			});
		}
	});
	
	// Click by background behind viewing image
	$("#portfolio_item_invisible_background").click(function() {
		$("#portfolio_item_invisible_background").css("display", "none");
		$(".portfolio_img").css("position", "static");
		$(".portfolio_img").css("height", "200px");
	});
});

function loop(){
	dW = $(window).width();
	dH = $(window).height();
	
	var main_cont_margin_left_tmp = dW/2-960/2;
	if(main_cont_margin_left_tmp < 225)
		main_cont_margin_left_tmp = 225;
	
	// Change position of main container if window risize
	if(main_cont_margin_left != main_cont_margin_left_tmp){
		main_cont_margin_left = main_cont_margin_left_tmp;
		$("#main_container_caption").css("margin-left", main_cont_margin_left+"px");
		$("#main_container").css("margin-left", main_cont_margin_left+"px");
	}
}