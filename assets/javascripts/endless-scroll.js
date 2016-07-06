/*ENDLESS SCROLLING*/
$(function(){
	function freeze(){
		//FREEZE TOP USER PANNEL
	    var topPosters = $("div.topPosters");
	    var offset = topPosters.offset();
	    var w = $(window).scrollTop();
	    pane = offset.top;
	    win = w
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
  if(appendPane.length){
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
  }
  
  var rightPaneHeight = 0;
  var leftPaneHeight = 0;
  var rightPane = $(".rightPhotoPane");
  var leftPane = $(".leftPhotoPane");
  var type = 'NULL';
  var word = 'NULL';
  var paneSet = 0;
  var firstLoad = true;
  
  if(iscriptType == 'photos' 
     || iscriptType == 'strainphotos' 
	 || iscriptType == 'videos' 
	 || iscriptType == 'strainvideos'){
     var didScroll = true;
  } else {
     var didScroll = false;
  }
  var gifStop = false;
  
  $(window).scroll(function(){
    didScroll = true;
	freeze();
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
		switch(iscriptType){
			case 'feed':
			case 'posts':
			case 'search':
			case 'forums':
			case 'front':
				switch(iscriptType){
					case 'feed':
			        case 'posts':
			        case 'strains':
					case 'front':
						type = iscriptType;
						if(iscriptType == 'strains'){
							url = __LOCATION__ + '/ajax/ajax_strain_feed.php?'+iTime;  
						} else {
							url = __LOCATION__ + '/ajax/ajax_user_feed.php?'+iTime; 
						}
					break;
			        case 'search':
			        case 'forums':
						type = iscriptType;
						word = appendPane.data("word");
					break;	
					break;
					default:
						type = 'NULL';
					break;
				}
			break;
			case 'photos':
				url = __LOCATION__ + '/ajax/ajax_user_photos.php?'+iTime;
			break;
			case 'strainphotos':
				url = __LOCATION__ + '/ajax/ajax_strain_photos.php?'+iTime;
			    type = 'strain';
			break;
			case 'videos':
				url = __LOCATION__ + '/ajax/ajax_user_videos.php?'+iTime;
			break;
			case 'strainvideos':
				url = __LOCATION__ + '/ajax/ajax_strain_videos.php?'+iTime;
				type = 'strain';
			break;
			
		}
        if(!gifStop){
          $.ajax({
            beforeSend: function(){
              if(!gifStop){
                gifStop = true;
                appendPane
				  .append("<i class='fa fa-refresh fa-spin fa-3x fa-fw margin-bottom defaultspinner'></i>");
              }
            },
            type: 'POST',
            cache: 'false',
            data: {user: iuser, 
			       start: istart, 
				   type: type, 
				   word: word},
            url: url,
            success: function(result){
			  if(result){
				 //console.log(result);
				 $result = $.parseJSON(result);
				 iStatus = $result.code;
				 iscriptType = $result.type;
				 switch(iStatus){
					 case 401:
					 case 500:
					 case 201:
					  //DO NOTHING
					 break;
					 default:
						switch(iscriptType){
							case 'photos':
							case 'strainphotos':
								photoBatch = $.parseJSON(result);
                                $.each(photoBatch, function(i,v){
                                      var newPhoto = photoBatch[i];
                                      if(rightPaneHeight == leftPaneHeight 
				                        && (newPhoto.photo && newPhoto.iheight)){
                                        leftPane.append(doPhoto(newPhoto.photo));
                                        leftPaneHeight += newPhoto.iheight;
                                      } else if(rightPaneHeight < leftPaneHeight 
				                        && (newPhoto.photo && newPhoto.iheight)){
                                        rightPane.append(doPhoto(newPhoto.photo));
                                        rightPaneHeight += newPhoto.iheight;
                                      } else if(rightPaneHeight > leftPaneHeight 
				                        && (newPhoto.photo && newPhoto.iheight)){
                                        leftPane.append(doPhoto(newPhoto.photo));
                                        leftPaneHeight += newPhoto.iheight;
                                     }
                               });
                               gifStop = false;
							break;
							case 'videos':
							case 'strainvideos':
							   //VIDEO
                               var j=1;
                               $.each($result, function(i,v){
                                  var newVideo = $result[i];
                                  var timeStamp = (new Date()).getTime();
                                  if(j%2){
                                     if(newVideo.vidtype == 'user' 
									 && newVideo.video
									 && newVideo.photo){
                                       leftPane
									     .append(doVideo(newVideo.video,newVideo.photo,timeStamp));
                                       videojs('video-preview'+timeStamp,{},function(){});
                                    } else if(newVideo.photo){
                                       leftPane
									     .append("<div class='userPicWrap'>"
										         +newVideo.photo
												 +"</div>");
                                    } 
                                  } else {
                                     if(newVideo.vidtype == 'user' 
									 && newVideo.video 
									 && newVideo.photo){
                                       rightPane
										 .append(doVideo(newVideo.video,newVideo.photo,timeStamp));
                                       videojs('video-preview'+timeStamp,{},function(){});
                                     } else if(newVideo.photo) {
                                       rightPane
										 .append("<div class='userPicWrap'>"
										         +newVideo.photo
												 +"</div>");
                                     }
                                  }
                                j++;
                               });
                               gifStop = false;
							break;
							default: 
							   appendPane
							     .append($result.message);
							   gifStop = false;
							break;
						}
					 break;
				 }
			  } else {
				gifStop = true; 
			  }
            },
            complete: function(){
              istart = istart+15;
              appendPane.attr("id",'start-'+istart);
              appendPane.find("i.defaultspinner").remove();
            }
          })
        }
      }
    } else {
      clearInterval();
    }
  },1000)
});