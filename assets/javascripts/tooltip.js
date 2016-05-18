/*TOOLTIP*/
$(function(){
	timeoutID = null;
	
	$("body").on("mouseenter", ".grab, .grab-prod", function(){
		$(".userToolTip").remove();
		$link = $(this);
		if(timeoutID){
			clearTimeout(timeoutID);
		}
		timeoutID = setTimeout(function(){
			var linkSplit = $link.attr("class").split(' ');
			var linkClass = linkSplit[0]
			var username = $link.text();
			username = username.replace(/\&/g, 'and');
			username = username.replace(/\'/g, '');
			if(linkClass.indexOf("-") > 0){
				url = __LOCATION__ +'/ajax/ajax_prod_tooltip.php';
			} else {
				url = __LOCATION__+'/ajax/ajax_user_tooltip.php';
			}
			$.ajax({
				url: url,
				type: 'POST',
				data: {user: username},
				success: function(result){
					if(result){
					  $("body").append(result);
					  var offset = $link.offset();
					  var iTop = offset.top;
					  var iLeft = offset.left;
					  $(".userToolTip").css({
						  'top':iTop+33+"px",
						  'left':iLeft-120+"px"
					  })
				    }
				}
			})
		},150)
	});
	
	$("body").on("mouseleave", ".grab, .grab-prod", function(){
			if(timeoutID){
				clearTimeout(timeoutID);
			}
			timeoutID = setTimeout(function(){
				$(".userToolTip").remove();
			},250)
	});
	
	$("body").on("mouseenter", ".userToolTip", function(){
		if(timeoutID){
			clearTimeout(timeoutID);
		}
	})
	
	$("body").on("mouseleave", ".userToolTip", function(){
		$(".userToolTip").remove();
		if(timeoutID){
			clearTimeout(timeoutID);
		}
	})
	
})