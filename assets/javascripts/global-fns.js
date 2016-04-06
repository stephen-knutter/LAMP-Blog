/*TOP MENU HOVER*/
$(function(){
	
	//BUTTONS
	var userIcon = $("#userIcon");
	var locationIcon = $("#locationIcon");
	var menuIcon = $("#menuIcon");
	var msgIcon = $("#msgIcon");
	var forumIcon = $("#forumIcon");
	var libIcon = $("#libIcon");
	var searchIcon = $("#searchIcon");
	var filterIcon = $("#filterIcon");
	var pickCity = $("#pickCity");
	var chooseFilter = $("#chooseFilter");
	
	//SHOW/HIDE PANES
	var locMenu = $("#locationMenu");
	var listWrap = $("#listingWrap");
	var msgWrap = $("#msgWrap");
	var searchBox = $("#searchBox");
	var forumHead = $("#forumHeadWrap");
	var libHead = $("#libHeadWrap");
	var regionMenu = $("#regionMenu");
	var msgMenu = $(".logMenu");
	var cityHead = $("#cityHeadWrap");
	var filterHead = $("#filterHeadWrap");
	
	//OPEN ITEM CACHE
   	var openItem = listWrap || null;
	
	//LOGIN MENU
	  userIcon.on("click", function(event){
	    event.preventDefault();
		msgWrap.css("display", "none");
	    locMenu.css("display", "none");
	    listWrap.css("display", "none");
	    searchBox.css("display", "none");
	    forumHead.css("display", "none");
	    libHead.css("display", "none");
	    msgMenu.animate({
			height: "toggle"
		}, 200);
		openItem = msgMenu;
	  });
	
	 //LOCATION MENU
	  locationIcon.on("click", function(event){
	    event.preventDefault();
		msgWrap.css("display", "none");
	    msgMenu.css("display", "none");
	    listWrap.css("display", "none");
	    searchBox.css("display", "none");
	    forumHead.css("display", "none");
	    libHead.css("display", "none");
	    locMenu.animate({
	      height: "toggle"
	    }, 200);
	    openItem = locMenu;
	  });
	
	//LISTINGS
	menuIcon.on("click", function(event){
		event.preventDefault();
		locMenu.css("display", "none");
		msgWrap.css("display", "none");
		msgMenu.css("display", "none");
		searchBox.css("display", "none");
		forumHead.css("display", "none");
		libHead.css("display", "none");
		cityHead.css("display", "none");
		filterHead.css("display", "none");
		listWrap.animate({
			height: "toggle"
		}, 200)
		openItem = listWrap;
	});
	
	//MESSAGES
 	msgIcon.on("click", function(event){
    	event.preventDefault();
		listWrap.css("display", "none");
    	locMenu.css("display", "none");
    	msgMenu.css("display", "none");
    	searchBox.css("display", "none");
    	forumHead.css("display", "none");
    	libHead.css("display", "none");
    	msgWrap.animate({
      		height: "toggle"
    		}, 200)
			openItem = msgWrap;
  	});

	//FORUM
	forumIcon.on("click", function(event){
		event.preventDefault();
		msgWrap.css("display", "none");
		locMenu.css("display", "none");
		msgMenu.css("display", "none");
		searchBox.css("display", "none");
		listWrap.css("display", "none");
		libHead.css("display", "none");
		cityHead.css("display", "none");
		filterHead.css("display", "none");
		forumHead.animate({
			height: "toggle"
		}, 200)
		openItem = forumHead;
	});
	
	//LIBRARY
	libIcon.on("click", function(event){
		event.preventDefault();
		msgWrap.css("display", "none");
		locMenu.css("display", "none");
		msgMenu.css("display", "none");
		searchBox.css("display", "none");
		listWrap.css("display", "none");
		forumHead.css("display", "none");
		cityHead.css("display", "none");
		filterHead.css("display", "none");
		libHead.animate({
			height: "toggle"
		}, 200)
		openItem = libHead;
	});
	
	//SEARCH
	searchIcon.on("click", function(event){
		event.preventDefault();
		msgWrap.css("display", "none");
		locMenu.css("display", "none");
		msgMenu.css("display", "none");
		listWrap.css("display", "none");
		forumHead.css("display", "none");
		libHead.css("display", "none");
		cityHead.css("display", "none");
		filterHead.css("display", "none");
		searchBox.animate({
			height: "toggle"
		}, 200)
		openItem = searchBox;
	});

	//CITY FILTER
	pickCity.on("click", function(event){
		event.preventDefault();
		msgWrap.css("display", "none");
		msgMenu.css("display", "none");
		locMenu.css("display", "none");
		listWrap.css("display", "none");
		searchBox.css("display", "none");
		forumHead.css("display", "none");
		libHead.css("display", "none");
		filterHead.css("display", "none");
		cityHead.animate({
			height: "toggle"
		}, 200);
	})
	
	//TYPE FILTER
	chooseFilter.on("click", function(event){
		event.preventDefault();
		msgWrap.css("display", "none");
		msgMenu.css("display", "none");
		locMenu.css("display", "none");
		listWrap.css("display", "none");
		searchBox.css("display", "none");
		forumHead.css("display", "none");
		libHead.css("display", "none");
		
		cityHead.css("display", "none");
		filterHead.animate({
			height: "toggle"
		}, 200);
	})
	
	$i = 0
	$(document).on("click",function(event){
		$i++
		var clickTarget = event.target;
		var clickedId = clickTarget.id;
		var clickedForum = clickTarget.closest("div#forumHeadWrap");
		var clickedForumIcon = clickTarget.closest("a#forumIcon");
		var clickedLib = clickTarget.closest("div#libHeadWrap");
		var clickedLibIcon = clickTarget.closest("a#libIcon");
		var clickedMap = clickTarget.closest("div#locationMenu");
		var clickedMapIcon = clickTarget.closest("a#locationIcon");
		var clickedLog = clickTarget.closest("div#logMenu");
		var clickedLogIcon = clickTarget.closest("a#userIcon");
		var clickedList = clickTarget.closest("div#listingWrap");
		var clickedListIcon = clickTarget.closest("a#menuIcon");
		var clickedMsg = clickTarget.closest("div#msgWrap");
		var clickedMsgIcon = clickTarget.closest("a#msgIcon");
		//console.log(clickedId);
		if( (!clickedForum && !clickedLib && !clickedMap && !clickedLog && !clickedLog && !clickedList && !clickedMsg) &&
			(!clickedForumIcon && !clickedLibIcon && !clickedMapIcon && !clickedLogIcon && !clickedListIcon && !clickedMsgIcon) && ($i > 1) && openItem){
				iScrollTop = $(window).scrollTop();

				//CHECK FOR MAP LISTING DROPDOWN
				if(openItem == listWrap){
					if(iScrollTop > 150){
						openItem.css("display", "none");
						openItem = null;
					} 
				} else if(openItem !== undefined && openItem != null) {
					openItem.css("display", "none");
					openItem = null;
				}

		}
  });
	
});


//console.log('Global fns is ready');

/*CUSTOM RADIO BUTTONS*/
$(function(){
    var radio = $("input[type=radio]");
    $(radio).each(function(){
      $(this).wrap("<span class='custom-radio'></span>");
      if($(this).is(':checked')){
        $(this).parent().addClass("selected");
      }
    });
    $(radio).click(function(){
      radio.prop("checked", false).parent().removeClass("selected");
      $(this).prop("checked", true).parent().toggleClass("selected");
    });
});

/*LOCK SCROLLING*/
$(function(){
  $('.Scrollable').on('DOMMouseScroll mousewheel wheel scroll', function(ev) {
      var $this = $(this),
          scrollTop = this.scrollTop,
          scrollHeight = this.scrollHeight,
          height = $this.innerHeight(),
          delta = (ev.type == 'DOMMouseScroll' ?
              ev.originalEvent.detail * -40 :
              ev.originalEvent.wheelDelta),
          up = delta > 0;

      var prevent = function() {
          ev.stopPropagation();
          ev.preventDefault();
          ev.returnValue = false;
          return false;
      }

      if (!up && -delta > scrollHeight - height - scrollTop) {
          // Scrolling down, but this will take us past the bottom.
          $this.scrollTop(scrollHeight);

          return prevent();
      } else if (up && delta > scrollTop) {
          // Scrolling up, but this will take us past the top.
          $this.scrollTop(0);
          return prevent();
      }
  });

  
});