$(function(){
	/*LAT LNG*/
	var mapClass = $("#storeMapWrap").attr("class");
	var strSplit = mapClass.indexOf("|");
	var strEnd = mapClass.length;
	var curLat = mapClass.slice(0,strSplit);
	var curLng = mapClass.slice(strSplit+1,strEnd);
	/*STORE NAME,PHONE,ADDRESS,CASH-TYPE,ATM*/
	var storeName = $(".storeName").html();
	var storeAddress = $("#storeAddress").html();
	var storePhone = $("#storePhone").html();
	var storeCash = $("#storeCashType").html();
	var $num = $("#storeRating").html();
	/*WEBSITE*/
	var storeWebsite = $("#storeWebsite").html();
	if(storeWebsite == 'N/A'){
		storeWebsite = '';
		markerHeight = '98';
	} else {
		markerHeight = '108';
	}
	/*ID & STORE TYPE*/
	var mapInfo = $(".storeInfoWrap").attr("id");
	var infoSplit = mapInfo.indexOf("|");
	var infoEnd = mapInfo.length;
	/*STORE ID*/
	var storeInfo = mapInfo.slice(0,infoSplit);
	var storeSplit = storeInfo.indexOf("-");
	var storeLength = storeInfo.length;
	var storeId = storeInfo.slice(storeSplit+1,storeLength);
	/*STORE TYPE*/
	var storeType = mapInfo.slice(infoSplit+1,infoEnd);
	
	if($num > 0.1 && $num <= .9){
		$stars = "<div class='half_star'></div>";
	} else if ($num > .9 && $num <= 1.4){
		$stars = "<div class='one_star'></div>";
	} else if ($num > 1.4 && $num <= 1.99){
		$stars = "<div class='one_half'></div>";
	} else if ($num > 1.99 && $num <= 2.4){
		$stars	= "<div class='two_star'></div>";
	} else if ($num > 2.4 && $num <= 2.99){
		$stars = "<div class='two_half'></div>";
	} else if ($num > 2.99 && $num <= 3.4){
		$stars = "<div class='three_star'></div>";
	} else if($num > 3.4 && $num  <= 3.99){
		$stars = "<div class='three_half'></div>";
	} else if($num > 3.99 && $num <= 4.4){
		$stars = "<div class='four_star'></div>";
	} else if($num > 4.4 && $num <= 4.99){
		$stars = "<div class='four_half'></div>";
	} else if($num == 5){
		$stars = "<div class='five_star'></div>";
	} else {
		$stars = "<div class='no_stars'></div>";
	}
	
	if(storeCash == 'a'){
		$atm =  "<span>ATM</span>" + "<div class='cash_image_atm clearfix'><img src='"+__LOCATION__+"/assets/images/atm.png'/></div>";
	} else {
		$atm = "<span>Debit Card</span>" + "<div class='cash_image_debit clearfix'><img src='"+__LOCATION__+"/assets/images/debit.png' class='cash_image'/></div>"
	}
	
	storedMarker = Array();
	var map = new google.maps.Map(document.getElementById("nearby-map"), {
		styles: [{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#71ABC3"},{"saturation":-10},{"lightness":-21},{"visibility":"simplified"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"hue":"#7DC45C"},{"saturation":37},{"lightness":-41},{"visibility":"simplified"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#C3E0B0"},{"saturation":23},{"lightness":-12},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#A19FA0"},{"saturation":-98},{"lightness":-20},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#ffa500"},{"saturation":100},{"lightness":0},{"visibility":"simplified"}]}],
		center: new google.maps.LatLng(curLat, curLng),
		center: new google.maps.LatLng(curLat, curLng),
		disableDefaultUI: true,
		zoom: 12,
		zoomControlOptions:{
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.RIGHT_BOTTOM
		},
		streetViewControl: true,
		scrollwhee: false,
		scaleControl: false,
		zoomControl: true,
		mapTypeId: 'roadmap'
	});
	var infoBubble = new InfoBubble({
		map: map,
		shadowStyle: 0,
		padding: 10,
		backgroundColor: '#ffffff',
		borderRadius: 5,
		arrowSize: 15,
		borderWidth: 2,
		borderColor: 'orange',
		disableAutoPan: true,
		maxWidth: 310,
		minWidth: 310,
		maxHeight: markerHeight,
		minHeight: markerHeight,
		arrowPosition: 50,
		backgroundClassName: 'marker',
		arrowStyle: 2
	});
	
	var geoBubble = new InfoBubble({
		map: map,
		shadowStyle: 0,
		padding: 10,
		backgroundColor: '#ffffff',
		borderRadius: 5,
		arrowSize: 15,
		borderWidth: 2,
		borderColor: 'orange',
		disableAutoPan: true,
		maxWidth: 310,
		minWidth: 310,
		maxHeight: markerHeight,
		minHeight: markerHeight,
		arrowPosition: 50,
		backgroundClassName: 'phoney',
		arrowStyle: 2
	});
	
	var customIcons = {
		rec: {
			icon: __LOCATION__ + '/assets/images/rec_icon_40.png' 
		},
		rdel: {
			icon: __LOCATION__ + '/assets/images/rec_icon_del_60.png'
		},
		med:{
			icon: __LOCATION__ + '/assets/images/med_icon_40.png'
		},
		mdel:{
			icon: __LOCATION__ + '/assets/images/med_icon_del_60.png'
		},
		tou:{
			icon: __LOCATION__ + '/images/tour-icon.png'
		}
	};
	
	var icon = customIcons[storeType];
	
	var geolocpoint = new google.maps.LatLng(curLat, curLng);
	var geolocation = new google.maps.Marker({
		position: geolocpoint,
		map: map,
		title: storeName,
		icon: icon.icon
	});
	var markerText = 
		'<div class="marker">'+
		'<div class="info clearfix">'+
		'<h3 class="name" id="'+storeId+'">'+storeName+'</h3>'+
		'<a class="site" href="http://'+storeWebsite+'" target="_blank">'+storeWebsite+'</a>'+
		'<p class="address">'+storeAddress+'</p>'+
		'<p class="phone">'+storePhone+'</p>'+
		$stars+
		'<p class="cash"><b>Non-Cash Type:</b>'+$atm+'</p>'+
		'</div>'+
		'</div>';
	
	google.maps.event.addListener(geolocation, 'click', function(){
		geoBubble.close();
		geoBubble.setContent(markerText);
		geoBubble.open(map, geolocation);
	});
	
	google.maps.event.addListenerOnce(map, 'idle', function(){
	    // do something only the first time the map is loaded
		geoBubble.close();
		geoBubble.setContent(markerText);
		geoBubble.open(map, geolocation);
	});
});

/*HOVER STARS*/
/*******
STARS
*******/

$(document).ready(function(){
	weedRating.create('.stars');
	
	$("#addRating").click(function(){
		$(".stars").animate({
			height: "toggle"
		})
		$(".stars").find("label").find("span").find("input").attr("checked",false);
		$(".stars").find("div").find("a").removeClass("rating");
	})
})

var weedRating = {
	create: function(selector){
		$(selector).each(function(){
			var $list = $('<div></div>');

			$(this)
				.find('input:radio')
				.each(function(i){
					var rating = $(this).val();
					var $item = $('<a href="#"></a>')
						.attr('title', rating / 2)
						.addClass(i % 2 == 1 ? 'rating-right' : '')
						.text(rating);

					weedRating.addHandlers($item);
					$list.append($item);

					if($(this).is(':checked')){
						$item.prevAll().andSelf().addClass('rating');
					}
				});
				$(this).append($list).find('label').hide();
		});
	},
	addHandlers: function(item){
		$(item).click(function(e){
			var $star = $(this);
			var $allLinks = $(this).parent();

			$allLinks
				.parent()
				.find('input:radio[value=' + $star.text() + ']')
				.attr('checked', true);

			$allLinks.children().removeClass('rating');
			$star.prevAll().andSelf().addClass('rating');

			e.preventDefault();
		}).hover(function(){
			$(this).prevAll().andSelf().addClass('rating-over');
		}, function(){
			$(this).siblings().andSelf().removeClass('rating-over')
		});
	}
}



