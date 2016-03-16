/*ENDLESS SCROLLING*/
$(function(){
	
	function freeze(){
		//FREEZE TOP USER PANNEL
		//var winWidth = document.documentElement.clientWidth;
	    var topPosters = $("div.topPosters");
	    var offset = topPosters.offset();
	    var w = $(window).scrollTop();
	    pane = offset.top;
	    win = w
	    //alert(w);
	    if(win > pane){
	      paneSet = pane;
	      topPosters.css({
	        "position": "fixed",
	        "top": 55+"px"
	      })
	    } else if(win < paneSet) {
	      paneSet = 0;
	      topPosters.css({
	        "position":"relative",
	        "top": "0"
	      })
	    }
	}
	
	
  var appendPane = $("div.dropPane");
  appendPane = appendPane.length ? appendPane : $("#rightInfoPane");
  var ipane = appendPane.data("pane");
  var istartWrap = appendPane.attr("id");
  
  var feedStart = istartWrap.indexOf("-")+1
  var feedEnd = istartWrap.length;
  var istart = istartWrap.slice(feedStart,feedEnd);
  istart = Number(istart);
  
  var strSplit = ipane.indexOf("-");
  var strEnd = ipane.length;
  var iuser = ipane.slice(strSplit+1,strEnd)
  var iscriptType = ipane.slice(0,strSplit);
  
  var rightPaneHeight = 0;
  var leftPaneHeight = 0;
  var rightPane = $(".rightPhotoPane");
  var leftPane = $(".leftPhotoPane");
  var type = 'NULL';
  var word = 'NULL';
  var paneSet = 0;
  var firstLoad = true;
  
  if(iscriptType == 'photos' || iscriptType == 'strainphotos' || iscriptType == 'videos' || iscriptType == 'strainvideos'){
    var didScroll = true;
  } else {
    var didScroll = false;
  }
  var gifStop = false;
  
  $(window).scroll(function(){
    didScroll = true;

	if(firstLoad){
		setTimeout(function(){
			freeze();
			firstLoad = false;
		},5000);
	} else {
		freeze();
	}
  });
  
  setInterval(function(){
    /*GRAB SCROLL*/
    if(didScroll && iscriptType != 'none'){
      didScroll = false;
      
      idocHeight = appendPane.height();
      istartAjax = $(window).height();
      iscrollTop = $(window).scrollTop();
      if(iscrollTop >= idocHeight - istartAjax){
        var iTime = new Date().getTime();
        if(iscriptType == 'feed' || iscriptType == 'posts' || iscriptType == 'strains' 
			|| iscriptType == 'search' || iscriptType == 'forums' || iscriptType == 'front'){
          url = 'https://www.budvibes.com/ajax-feed.php?"'+iTime+'"';
		  	switch(iscriptType){
				case 'feed':
					type = 'feed';
				break;
				case 'posts':
					type = 'posts';
				break;
				case 'strains':
					type = 'strains';
				break;
				case 'search':
					type = 'search';
					word = appendPane.data("word");
				break;
				case 'forums':
					type = 'forums';
					word = appendPane.data("word");
				break;
				case 'front':
					type = 'front';
				break;
				default: 
					type = 'NULL';
				break;
			}
        } else if(iscriptType == 'photos'){
          url = 'https://www.budvibes.com/ajax-photos.php?"'+iTime+'"';
        } else if(iscriptType == 'strainphotos'){
          url = 'https://www.budvibes.com/ajax-photos.php?"'+iTime+'"';
          type = 'strain';
        } else if(iscriptType == 'videos'){
          url = 'https://www.budvibes.com/ajax-videos.php?"'+iTime+'"';
        } else if(iscriptType == 'strainvideos'){
          url = 'https://www.budvibes.com/ajax-videos.php?"'+iTime+'"';
          type = 'strain';
        } 
        
        if(!gifStop){
          $.ajax({
            beforeSend: function(){
              if(!gifStop){
                gifStop = true;
                appendPane.append("<img class='defaultspinner' style='display: block; margin: 0 auto; clear:left;' src='https://www.budvibes.com/images/defaultspinner.gif' />")
              }
            },
            type: 'POST',
            cache: 'false',
            data: {user: iuser, start: istart, type: type, word: word},
            url: url,
            success: function(result){
              //alert(result);
              if(result == 0){
                //STOP LOADING GIF
                gifStop = true;
              } else if(iscriptType == 'photos' || iscriptType == 'strainphotos'){
                photoBatch = $.parseJSON(result);
                $.each(photoBatch, function(i,v){
                  var newPhoto = photoBatch[i];
                  if(rightPaneHeight == leftPaneHeight && (newPhoto.photo && newPhoto.iheight)){
                    leftPane.append(
                      "<div class='userPicWrap'>"+
                        "<img src='"+newPhoto.photo+"'>"+
                        "<div class='photoInfoPane'>"+
                          "<span class='photoReplyCount'>0 Replies</span>"+
                        "</div>"+
                      "</div>"
                    );
                    leftPaneHeight += newPhoto.iheight;
                    //leftPaneHeight += newHeight;
                  } else if(rightPaneHeight < leftPaneHeight && (newPhoto.photo && newPhoto.iheight)){
                    rightPane.append(
                      "<div class='userPicWrap'>"+
                        "<img src='"+newPhoto.photo+"'>"+
                        "<div class='photoInfoPane'>"+
                          "<span class='photoReplyCount'>0 Replies</span>"+
                        "</div>"+
                      "</div>"
                    );
                    rightPaneHeight += newPhoto.iheight;
                    //rightPaneHeight += newHeight;
                  } else if(rightPaneHeight > leftPaneHeight && (newPhoto.photo && newPhoto.iheight)){
                    leftPane.append(
                      "<div class='userPicWrap'>"+
                        "<img src='"+newPhoto.photo+"'>"+
                        "<div class='photoInfoPane'>"+
                          "<span class='photoReplyCount'>0 Replies</span>"+
                        "</div>"+
                      "</div>"
                    );
                    leftPaneHeight += newPhoto.iheight;
                    //leftPaneHeight += newHeight;
                  }
                });
                gifStop = false;
              } else if(iscriptType == 'videos' || iscriptType == 'strainvideos'){
                //VIDEO
                photoBatch = $.parseJSON(result);
                var j = 1;
                $.each(photoBatch, function(i,v){
                  var newPhoto = photoBatch[i];
                  var timeStamp = (new Date()).getTime();
                  if(j%2){
                    if(newPhoto.vidtype == 'user'){
                      leftPane.append(
                        "<div class='userPicWrap'>"+
                          '<video style="margin: 0 auto; position: relative; display: block;" id="video-preview'+timeStamp+'" class="video-js vjs-default-skin" controls preload="auto" width="281" height="281" poster="'+newPhoto.photo+'">'+
                           '<source src="'+newPhoto.video+'">'+
                           '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
                          '</video>'+
                          "<div class='photoInfoPane'>"+
                            "<span class='photoReplyCount'>0 Replies</span>"+
                          "</div>"+
                        "</div>"
                      );
                      videojs('video-preview'+timeStamp,{},function(){});
                    } else {
                      leftPane.append(
                        "<div class='userPicWrap'>"+
                          newPhoto.photo+
                        "</div>"
                      );
                    }
                  } else {
                    if(newPhoto.vidtype == 'user'){
                      rightPane.append(
                        "<div class='userPicWrap'>"+
                          '<video style="margin: 0 auto; position: relative; display: block;" id="video-preview'+timeStamp+'" class="video-js vjs-default-skin" controls preload="auto" width="281" height="281" poster="'+newPhoto.photo+'">'+
                           '<source src="'+newPhoto.video+'">'+
                           '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
                          '</video>'+
                          "<div class='photoInfoPane'>"+
                            "<span class='photoReplyCount'>0 Replies</span>"+
                          "</div>"+
                        "</div>"
                      );
                      videojs('video-preview'+timeStamp,{},function(){});
                    } else {
                      rightPane.append(
                        "<div class='userPicWrap'>"+
                          newPhoto.photo+
                        "</div>"
                      );
                    }
                  }
                  j++;
                });
                gifStop = false;
              } else {
                appendPane.append(result);
                gifStop = false;
              }
            },
            complete: function(){
              istart = istart+15;
              appendPane.attr("id",'start-'+istart);
              appendPane.find("img.defaultspinner").remove();
            }
          })
        }
      }
    } else {
      clearInterval();
    }
  },1000)
});