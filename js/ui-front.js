var scrollBarWidth; // scrollbar width variable

// return the browser scrollbar width
var getScrollBarWidth = function(){
	$("body").append($('<div id="divGetScrollbarWidth" style="position:absolute; left:-100px; top:-500px; width:300px; height:350px; overflow:auto"><div style="height:400px"></div></div>'));
	var w1 = $("#divGetScrollbarWidth").width();
	var w2 = $("#divGetScrollbarWidth > div").width();
	scrollBarWidth = w1 - w2;
};

// layer popup
var popup = {
	open : function(elem){
		if($(".popup-wrap:visible").length == 0){
			//$("html, body").css("overflow","hidden").css("margin-right",scrollBarWidth);
			elem.show();
		}else{
			var $zidx = 0;
			for(var i=0; i<$(".popup-wrap:visible").length; i++){
				if($zidx < $(".popup-wrap:visible").eq(i).css("z-index") * 1 ){
					$zidx = $(".popup-wrap:visible").eq(i).css("z-index") * 1
				}
			}
			elem.css("z-index",$zidx + 1).show();
		}
	},
	close : function(elem){
		elem.hide().css("z-index","");
		if($(".popup-wrap:visible").length == 0){
			//$("html, body").css("overflow","").css("margin-right","");
		}
	}
};

$(function(){

	// gnb
	var $gnbbtn = $("#header .gnb");
	var $gnbmenu = $("#gnbMenu");

	$gnbbtn.on("click touchend", function(e){
		e.preventDefault();
		$gnbmenu.show();
		$gnbmenu.find(".inner").stop().animate({"left": "0"}, 600, "easeOutExpo");
		var height=document.getElementsByTagName("body")[0].scrollHeight;
		$("#all_body").css("display","block");
		$("#all_body").css("width",$(window).width());
		$("#all_body").css("height",height);
	});

	$(document).on("click touchend", function(e){
		var target = "gnbMenu";
		if(event.target.id ==  target){
			$gnbmenu.find(".inner").stop().animate({"left": "-65%"}, 600, "easeOutExpo", function(){
				$("#all_body").css("display","none");
				$gnbmenu.hide();
			});
		}
	});

});
