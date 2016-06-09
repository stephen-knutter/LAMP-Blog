//TEST AJAX VERSION
$(function(){
  if(window.FormData === undefined){
    xhr = 'false';
  } else {
    xhr = 'true';
  }
  $(".addPostForm, .addPostFormForum").prepend("<input class='xhr_type' type='hidden' name='xhr_type' value='"+xhr+"'>");
})

//FIND BROWSER HEIGHT

function positionLightBox(){
  var top = ($(window).height() - $("#lightbox").height()) / 2;
  var left = ($(window).width() - $("#lightbox").width()) / 2;
  $("#lightbox")
         .css({'top':top + $(document.body).scrollTop(0),'left':left})
         .fadeIn();
}

function removeLightbox(){
  $("#overlay, #lightbox")
    .fadeOut('slow', function(){
      $(this).remove();
      $("html,body").css("overflow-y", "auto");
      $("html,body").css("height", "auto");
    })
}

/*CHANGE PROFILE PIC*/
$(function(){
  ichangephoto = {
	 changePhotoForm: $("#changePhotoForm"),
	 userProfilePic: $("#userProfilePic"),
	 userPicImg: $("#userPicImg"),
	 userProfilePic: $("#userProfilePic"),
	 newPicIcon: $(".newPic"),
	 ajaxUploadImg: $("#ajaxUploadImg"),
	 changeFileButton: $("#changeFileButton"),
	 changePhotoForm: $("#changePhotoForm"),
  }
  var winHeight = window.innerHeight 
		          || document.documentElement.clientHeight 
				  || document.body.clientHeight;
  var winWidth = window.innerWidth 
                 || document.documentElement.clientWidth 
				 || document.body.clientWidth;
  ichangephoto
	  .changePhotoForm
      .prepend("<input type='hidden' name='winheight' value='"+winHeight+"'>")
  
  $("#userProfilePic, #changePhotoForm, #userPicImg").hover(function(){
    ichangephoto
	    .newPicIcon
		.css("display","block");
	//$(".newPic").css("display", "block")
  }, function(){
	ichangephoto
		.newPicIcon
		.css("display","none");
    //$(".newPic").css("display", "none")
  });

  $("#changePhotoForm").ajaxForm({
    beforeSend: function(){
      $(".uploadError, .error, .success").remove();
	  ichangephoto
	      .ajaxUploadImg
		  .css("display","block");
      //$("#ajaxUploadImg").css("display", "block");
    },
    success: function(result){
		console.log(result);
		if(result){
			$result = $.parseJSON(result) || null;
            //var parent = $("#userProfilePic"); ichangephoto.userProfilePic
	        var iStatus = $result.code;
	        switch(iStatus){
		       case 400:
		       case 501:
			     ichangephoto
			       .userProfilePic
				   .append("<p class='uploadError'>" + $result.status + "</p>");
		       break;
		       default:
			     zWidth = $result.width;
			     zHeight = $result.height;
			     zUserId = $result.user_id;
			     zPhoto = $result.photo;
			     var winHeight = window.innerHeight 
			                    || document.documentElement.clientHeight 
						        || document.body.clientHeight;
			     winHeight = winHeight - 250;
			     if(zHeight > winHeight){
				     zHeight = winHeight;
			     }
			     var winWidth = window.innerWidth 
			                   || document.documentElement.clientWidth 
						       || document.body.clientWidth;
			     document.scrollTop = 0;
			     $('html, body').css({'overflow':'hidden', 
			                     'height':'100%'});
			     $("<div id='overlay'></div>")
				    .css({
                        'top': 0,
                        'opacity': 0,
                        'z-index': 10
                       })
                    .animate({'opacity':'0.5'}, 
					          'slow')
                    .appendTo('body');
		         $("<div id='lightbox'></div>")
                    .append(
                            '<div id="newPicForm" style="width: '+zWidth+'px; height: '+zHeight+'px;">' +
                            '<form enctype="multipart/form-data" action="'+ __LOCATION__ +'/ajax/ajax_add_profile_pic.php" method="post" id="submitNewPic">' +
                            '<img style="width: '+zWidth+'px; height:'+zHeight+'px;" src="'+ __LOCATION__ + '/assets/user-images/'+zUserId+'/'+zPhoto+'" id="newPic" />' +
                            '<span id="picPreview">Pic Preview</span>'+
                            '<i>(click above to crop)</i>' +
                            '<input type="hidden" name="x" id="x" val=""/>' +
                            '<input type="hidden" name="y" id="y" val=""/>' +
                            '<input type="hidden" name="x2" id="x2" val=""/>' +
                            '<input type="hidden" name="y2" id="y2" val=""/>' +
                            '<input type="hidden" name="w" id="w" val=""/>' +
                            '<input type="hidden" name="h" id="h" val=""/>' +
                            '<div id="previewContain"><img src="'+ __LOCATION__ + '/assets/user-images/'+zUserId+'/'+zPhoto+'" id="preview" /></div>'+
                            '<input type="submit" id="savePhoto" name="submit" value="Save" />' +
                            '<input type="button" id="cancelPhoto" name="cancel" value="Cancel" />' +
                            '</form>' +
                            '</div>'
                           )
                    .css('z-index', '2000')
                    .appendTo('body');
			     positionLightBox();
		        break;
	      }
		} else {
			ichangephoto
			       .userProfilePic
				   .append("<p class='uploadError'>Internal error</p>");
		}
    },
    complete: function(){
	  ichangephoto
		 .changeFileButton
		 .val("");
      //$("#changeFileButton").val("");
	  ichangephoto
		 .ajaxUploadImg
		 .css("display","none");
      //$("#ajaxUploadImg").css("display", "none");
    }
  })
  
  /*FORCE SUBMIT ONCE PHOTO IS UPLOADED*/
  ichangephoto
	    .changeFileButton
		.change(function(){
			if(ichangephoto.changeFileButton.val() != ""){
				ichangephoto.changePhotoForm.submit();
			}
		})
  		
 // $("#changeFileButton").change(function(){
 //   if($("#changeFileButton") != ""){
 //     $("#changePhotoForm").submit();
 //   }
 // });

})

/*JCROP FOR NEW IMAGE*/
$(function(){
  $('body').on('click', 'img#newPic', function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $width = $("#preview").width();
    $height = $("#preview").height();
    $(this).Jcrop({
      onChange: showPreview,
      onSelect: showCoords,
      aspectRatio: 1,
      setSelect: [0,0,190,190]
    });
  });
  
  function showCoords(c){
    $('#x').val(c.x);
    $("#y").val(c.y);
    $("#x2").val(c.x2);
    $("#y2").val(c.y2);
    $("#w").val(c.w);
    $("#h").val(c.h);
  }
  
  function showPreview(coords){
    var rx = 100 / coords.w;
    var ry = 100 / coords.h;
    
    $("#preview").css({
      width: Math.round(rx * $width) + "px",
      height: Math.round(ry * $height) + "px",
      marginLeft: '-' + Math.round(rx * coords.x) + "px",
      marginTop: '-' + Math.round(ry * coords.y) + "px"
    })
  }
});

/*SUBMIT CROP*/
$(function(){
  isubmitcrop = {
	  userProfilePic: $("#userProfilePic"),
  }
  $('body').on('submit', '#submitNewPic', function(event){
  if(!event){
    event = window.event;
  }
  event.preventDefault();
  $form = $(this);
  $profilePic = $("#userPicImg");
  var url = $form.attr("action");
  var x = $("#x").val();
  var y = $("#y").val();
  var x2 = $("#x2").val();
  var y2 = $("#y2").val();
  var w = $("#w").val();
  var h = $("#h").val();
  var pic = $("#newPic").attr("src");
	$.ajax({
		type: 'POST',
		cache: false,
		url: url,
		data: {x: x, 
		       y: y, 
			   x2: x2, 
			   y2: y2, 
			   w: w, 
			   h: h, 
			   pic: pic},
		success: function(result){
			      //console.log(result);
		          removeLightbox();
				  if(result){
					  $result = $.parseJSON(result);
					  iStatus = $result.code;
					  switch(iStatus){
						  case 500:
						  case 401:
							   isubmitcrop
								  .userProfilePic
								  .append("<p class='uploadError'>"+ $result.status +"</p>");
						  break;
						  default:
							 location.reload();
						  break;
					  }
				  } else {
					  isubmitcrop
						 .userProfilePic
						 .append("<p class='uploadError'>Internal error</p>");
				  }
		          
		}
	})
  })
});

/*CANCEL CROP*/
$(function(){
  $('body').on('click', '#cancelPhoto', function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    removeLightbox();
  });
});


$(function(){
   iedituser = {
	   buttonWrap: $("div.editButtonWrap"),
	   curUsernameBtn: $("input#newNameButton"),
	   curUsernameBtnVal: null,
	   curEmailBtn: $("input#newEmailButton"),
	   curEmailBtnVal: null,
	   curPassBtn: $("input#changePass"),
	   curPassBtnVal: null,
	   newNameInputField: $("input#new_username"),
	   newNameInputFieldVal: null,
	   newEmailInputField: $("input#new_email"),
	   newEmailInputFieldVal: null,
	   oldPassInputField: $("input#old_pass"),
	   oldPassInputFieldVal: null,
	   newPassInputField: $("input#new_pass"),
	   newPassInputFieldVal: null,
	   confirmPassInputField: $("input#confirm_pass"),
	   confirmPassInputFieldVal: null,
   }
  /*CHANGE USERNAME SUBMIT*/
  $("body").on("submit", "#editUsernameForm", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $form = $(this);
    iedituser.curUsernameBtnVal = iedituser
	                                 .curUsernameBtn
									 .val();
	iedituser.newNameInputFieldVal = iedituser
	                                    .newNameInputField
										.val();
    var url = $form.attr("action");
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        iedituser
		    .curUsernameBtn
			.attr("disabled","disabled");
        iedituser
		    .curUsernameBtn
			.val("");
      },
	  type: 'POST',
	  data: {username: iedituser.newNameInputFieldVal},
      url: url,
      success: function(result){
		console.log(result);
		if(result){
			$result = $.parseJSON(result);
			iStatus = $result.code;
			switch(iStatus){
				case 410:
				case 500:
					$form
					  .before("<p class='error'>"+$result.status+"</p>");
				break;
				default:
					iedituser
					  .newNameInputField
					  .val($result.username);
					$form
					  .before("<p class='success'>Username changed successfully</p>");
				break;
			}
		} else {
			$form
			  .before("<p class='error'>Internal error</p>");
		}
      },
      complete: function(){
        iedituser
			.curButton
			.attr("disabled", false);
        iedituser
			.curButton
			.val(ichangusername.buttonVal);
      }
    });
  });
  
  /*CHANGE EMAIL SUBMIT*/
  $("body").on("submit", "#editEmailForm", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $form = $(this);
	iedituser.curEmailBtnVal = iedituser
	                              .curEmailBtn
								  .val();
	iedituser.newEmailInputFieldVal = iedituser
	                                     .newEmailInputField
										 .val();
    var url = $form.attr("action");
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        iedituser
		   .curEmailBtn
		   .attr("disabled","disabled");
		iedituser
		   .curEmailBtn
		   .val("");
      },
	  type: 'POST',
	  data: {email: iedituser.newEmailInputFieldVal},
      url: url,
      success: function(result){
		if(result){
			$result = $.parseJSON(result);
			iStatus = $result.code;
			switch(iStatus){
				case 401:
				case 500:
					$form.before("<p class='error'>"+ $result.status +"</p>");
				break;
				default: 
					iedituser
					   .newEmailInputField
					   .val($result.email);
					$form.before("<p class='success'>"+ $result.status +"</p>");
				break;
			}
		} else {
			$form.before("<p class='error'>Internal error</p>");
		}
      },
      complete: function(){
        iedituser
		   .curEmailBtn
		   .attr("disabled",false);
		iedituser
		   .curEmailBtn
		   .val(iedituser.curEmailBtnVal);
      }
    })
  });
  
  /*CHANGE PASSWORD SUBMIT*/
  $("body").on("submit", "#changePassForm", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $form = $(this);
    iedituser.curPassBtnVal            = iedituser
	                                       .curPassBtn
								           .val();
    iedituser.oldPassInputFieldVal     = iedituser
	                                       .oldPassInputField
										   .val();
    iedituser.newPassInputFieldVal     = iedituser
										   .newPassInputField
										   .val();
    iedituser.confirmPassInputFieldVal = iedituser
	                                       .confirmPassInputField
										   .val();
    var url = $form.attr("action");
    
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        iedituser
		   .curPassBtn
		   .attr("disabled","disabled")
        iedituser
		   .curPassBtn
		   .val("");
      },
      type: 'POST',
      data: {old_pass: iedituser.oldPassInputFieldVal, 
	         new_pass: iedituser.newPassInputFieldVal, 
			 confirm: iedituser.confirmPassInputFieldVal},
      url: url,
      success: function(result){
		if(result){
			$result = $.parseJSON(result);
			iStatus = $result.code;
			switch(iStatus){
				case 401:
				case 500:
					$form.before("<p class='error'>"+$result.status+"</p>");
				break;
				default: 
					iedituser
					   .oldPassInputField
					   .val("");
					iedituser
					   .newPassInputField
					   .val("");
					iedituser
					   .confirmPassInputField
					   .val("");
					$form.before("<p class='success'>"+$result.status+"</p>");
				break;
			}
		} else {
			$form.before("<p class='error'>Internal error</p>");
		}
      },
      complete: function(){
		iedituser
		   .curPassBtn
		   .attr("disabled",false);
		iedituser
		   .curPassBtn
		   .val(iedituser.curPassBtnVal);
      }
    })
  });
});

/*POST BOX CAMERA HOVER TOOLTIP*/
$(function(){
  $(".cameraPic, #photoFileButton").hover(function(event){
    $("#cameraHover").css("display", "block");
  }, function(){
    $("#cameraHover").css("display", "none");
  });
});

/*NEARBY DISPENSARYY MAP*/
$(function(){
  $("body").on("click", ".nearbyStoreWrap h3", function(event){
    if(!event){
      event = window.event;
    }
    event.stopPropagation();
    var pop_up = $(this).attr("id");
    google.maps.event.trigger(storedMarker[pop_up], "click");
  });
  
  /*NAVIGATOR GEOLOCATION
  if(geoPosition.init()){
      geoPosition.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
  }
  geoPositionSimulator.init();
  function error_callback(p)
  {
    //freezePane();
  }
  function success_callback(p)
  {
    $("#nearbyMap").css("display","block");
    $(".nearbyStoreWrap").remove();
    curLat = parseFloat( p.coords.latitude );
    curLng = parseFloat( p.coords.longitude );
    //weed-map.js
    nearbyMap();
    //freezePane();
  }
  
   //freezePane();
   */
});



/*NEW POSTS*/
$(function(){
    iaddphoto = {
	  curButton: null,
	  curForm: null,
	  curId: null,
	  curPane: null,
	  curTags: null,
	  showPanes: null,
	  photoBtn: $("input.photoFileButton"),
	  videoBtn: $("input.videoFileButton"),
	  linkBtn: $('.linkFileButton'),
	  userTagPane: $(".userTagPane"),
	  submittedTags: $(".submittedTags"),
	  buttonToggles: $(".buttonToggle"),
	  buttonToggleInputs: $(".buttonToggle").find("input"),
	  xhrType: $("input.xhr_type"),
	  postType: $("input.post_type"),
	  tagPane: $("div.tagPane"), //aka userTagPane
	  userPostBox: $("textarea#userFeedBox"),
	  dropPane: $("div.dropPane"),
	}
  /*POST BUTTON CLICK*/
  $("#addPostButton, #addProdButton").on("click", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $button = $(this);
    var buttonVal = $button.html();
    var buttonWrap = $button.parent("div");
    var iframePhoto = 'noframe';
    //CURRENT FEED WALL ID AND USERNAME
    var buttonClass = $button.attr("class");
    var dash = buttonClass.indexOf("-");
    var strEnd = buttonClass.length;
    var curWallId = buttonClass.slice(dash+1,strEnd);
    var curWallUser = buttonClass.slice(0,dash);
    //XHR TYPE
	var xhrType = iaddphoto
				      .xhrType
					  .val();
    //POST TYPE(PROD OR USER)
	var postType = iaddphoto
					  .postType
					  .val();
    //TAG PANE
    //USER PHOTO
    var userPhotoImg = iaddphoto
						 .tagPane
						 .find("img.postImgPreview");
    var userPhoto = userPhotoImg.attr("src");
    //USER VIDEO
    var userVideoWrap = iaddphoto.tagPane.find("video");
    var userVideo = userVideoWrap
						.find("source")
						.attr("src");
    //LINK INFO
    var userLinkWrap = iaddphoto
						.tagPane
						.find("div.userLink");
    var linkSource = userLinkWrap.find("img");
    var linkType = linkSource.attr("id");
    if(linkType == 'iframephoto'){
      iframePhoto = 'framephoto';
    } else if(linkType == 'iframenophoto'){
      iframePhoto = 'noframephoto';
    }
    var userLink = linkSource.attr("src");
    linkSource.remove();
    var linkHtml = userLinkWrap.html();
    //USER COMMENT
    var userPost = iaddphoto
					.userPostBox
					.val();
    //TAGS
    var tags = Array();
    var curTags = iaddphoto
					.submittedTags
					.find("span.newTag");
    curTags.each(function(){
      tags.push($(this).text());
    })
    //RATING
    var curRating = buttonWrap
						.siblings("div.stars")
						.find('input[name=rating]:checked')
						.val();
    var url = __LOCATION__ + '/ajax/ajax_add_post.php';
    if(!curRating){
      curRating = 'No Rating';
    }
    if(userPhoto){
      var media = 'photo';
      userVideo = 'NULL';
      userLink = 'NULL';
    } else if(userVideo){
      var media = 'video';
      userPhoto = 'NULL';
      userLink = 'NULL';
    } else if(userLink){
      var media = 'link';
      userPhoto = 'NULL';
      userVideo = 'NULL';
    } else {
      var media = 'none';
      userPhoto = 'NULL';
      userVideo = 'NULL';
      userLink = 'NULL';
    }
    
    $.ajax({
      beforeSend: function(){
        $(".error").remove();
        $button
			.attr("disabled", "disabled")
			.html("");
      },
      type: 'POST',
      data: {cur_wall_id: curWallId, 
			 cur_wall_user: curWallUser, 
			 text: userPost, 
			 photo: userPhoto, 
			 video: userVideo, 
			 tags: tags, 
			 rating: curRating, 
			 xhr_type: xhrType, 
			 post_type: postType, 
			 media: media, 
			 link: userLink, 
			 link_info: linkHtml, 
			 iframe: iframePhoto},
      url: url,
      success: function(result){
        console.log(result);
		result = $.parseJSON(result) || null;
		iStatus = result.code || null;
		if(result){
		  switch(iStatus){
			case 204:
			case 401:
			case 500:
				iaddphoto
					.submittedTags
					.after("<p class='error'>"+result.status+"</p>");
			break;
			default:
			  var comment = result.message;
			  iaddphoto.dropPane.prepend(comment);
              var vidId = iaddphoto
					       .dropPane
						   .first("div.commPostVideo")
						   .find("video")
						   .attr("id");
              if(vidId == 'newvideo'){
                videojs('newvideo',{},function(){});
              }
              $("img.postImgPreview").remove();
              $("div.userLink").remove();
              iaddphoto
				 .tagPane
				 .find("div.video-js, video")
				 .remove();
              iaddphoto
			     .userPostBox
				 .val("");
              userPhotoImg.remove();
              if(iaddphoto.curButton){
               iaddphoto.curButton.attr("disabled", false);
             }
             $(".bigx").css("display", "none").html("");
		     iaddphoto.userTagPane.css("display","none");
		     iaddphoto.submittedTags.css("display","none");
             $("div.stars").find("div").find("a").removeClass("rating");
             iaddphoto.curButton = null;
             iaddphoto.curForm = null;
             iaddphoto.curId = null;
             iaddphoto.curPane = null;
			break;
		  }
		} else {
			iaddphoto
				.submittedTags
				.after("<p class='error'>Internal error</p>");
		}
      },
      complete: function(){
        $button
			.attr("disabled",false)
			.html(buttonVal);
		iaddphoto
			.buttonToggleInputs
			.attr("disabled",false);
		iaddphoto
			.submittedTags
			.html("");
      }
    });
    
  });
	
  /*REPLY BUTTON CLICK*/
  $("body").on("click", "button.replyButton", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $button = $(this);
    var buttonText = $button.html();
    var buttonWrap = $button.parent("div.replyButtonWrap");
    /*CURRENT FORM*/
    var curForm = buttonWrap.parent("form").parent("div.replyForm");
    /*COMMENT ID*/
    var buttonId = $button.attr("id");
    strStart = buttonId.indexOf("-")+1;
    strEnd = buttonId.length;
    var commId = buttonId.slice(strStart,strEnd);
    /*POST TYPE*/
    var postType = buttonWrap.siblings("input.post_type").val();
    /*TAG PANE*/
    var userCurPane = buttonWrap.siblings(".replyPane");
    /*PHOTO*/
    var userPhotoImg = userCurPane.find("img.postImgPreview");
    var userPhoto = userPhotoImg.attr("src");
    /*USER COMMENT*/
    var userPostBox = buttonWrap.siblings("textarea.userReplyBox");
    var userPost = userPostBox.val();
    var url = __LOCATION__ + '.com/add-reply.php';
    
    $.ajax({
      beforeSend: function(){
        $("p.error").remove();
        $button.attr("disabled", "disabled");
        $button.html("");
      },
      type: 'POST',
      url: url,
      data: {text: userPost, photo: userPhoto, comm_id: commId, post_type: postType},
      success: function(result){
        if(result == 0){
          userCurPane.before("<p class='error'>Error adding comment</p>");
        } else if(result == 2){
          userCurPane.before("<p class='error'>Must insert comment or add photo</p>");
        } else {
          curForm.after(result);
          userCurPane.find("img").remove();
          userPostBox.val("");
          if(iaddphoto.curButton){
            iaddphoto.curButton.attr("disabled", false);
          }
          var countContain = curForm.siblings("div.repliesHead").find("div.replyCount").find("b")
          var countWrap = countContain.find("span.replyNum");
          var count = countWrap.html();
          count = Math.floor(count);
          count = count+1;
          countWrap.html(count);
          var countText = countContain.find("span.replyPluralize");
          if(count == 1){
            reply = 'Reply ';
          } else {
            reply = 'Replies ';
          }
          countText.html(reply);
        }
      },
      complete: function(){
        $button.attr("disabled", false);
        $button.html(buttonText);
        buttonWrap.find("img").remove();
      }
    })

  })
  
  /*ADD A LINK*/
  $("body").on("click", ".linkFileButton", function(event){ 
    iaddphoto.curButton = $(this);
    iaddphoto.curForm = iaddphoto
						   .curButton
						   .parent("div")
						   .parent("form");
    iaddphoto.curPane = iaddphoto
						   .curButton
						   .parent("div")
						   .siblings("div.tagPane");
    $("input#addlink").remove();
    iaddphoto
	   .curPane
	   .before('<input type="text" name="addlink" id="addlink" placeholder="Paste a link here" />');
  });

  $("body").on("paste", "input#addlink", function(event){
	inputCache = null;
    e = $(this);
    $(".error").remove();
	
    var timeoutId = setTimeout(function(){
      if(e.val() != "" && e.val() != inputCache){
		iaddphoto
			.buttonToggleInputs.attr("disabled","disabled");
        elink = inputCache = e.val();
        url = __LOCATION__+'/ajax/ajax_add_link.php';
        $.ajax({
          beforeSend: function(){
            iaddphoto
			    .curPane
				.append("<img class='ajaxImage' src='"+__LOCATION__ +"/assets/images/green-bars.gif'>");
          },
          type: 'POST',
          url: url,
          data: {add_link : elink},
          success: function(result){
			result = $.parseJSON(result);
			inputCache = null;
			var zStatus = result['code'];
			switch(zStatus){
				case 500:
					e.css("color","red");
				break;
				case 401:
					$(".chatBoxWrap").remove();
					$("body").append(doSignUpBox());
					$("input#addlink").html("").remove();
					iaddphoto
					    .buttonToggleInputs
						.attr("disabled",false);
				break;
				case 200:
					if(!result){
						$("img.ajaxImage").remove();
						e.css("color","red");
					}
					var html = ""
					if((!result.ogimage || result.ogimage == "") && 
					   (!result.ogtitle || result.ogtitle == "") && 
					   (!result.ogdescription || result.ogdescription == "") && 
					   (!result.ogurl || result.ogurl == "")
					&& (!result.site_title || result.site_title == "") && 
					   (!result.meta_description || result.meta_description == "") && 
					   (!result.iframe || result.iframe == "")){
					//NO RESULTS
						$("input#addlink").css("color","red");
					} else if((!result.ogimage) && 
					          (!result.ogtitle) && 
							  (!result.ogdescription) && 
							  (!result.ogurl) &&
						      (!result.site_title) &&  
						      (!result.meta_description) && 
							  (result.iframe)){
					//IFRAME ONLY
						html += "<div class='userLink'>";
						html += "<img class='iframephoto' id='iframenophoto' style='display:none;' src='"+__LOCATION__+"/assets/images/videoholder.png' />";
						html += result.iframe;
						html += "<div>";
					} else if(((result.ogimage) || 
							   (result.ogtitle) || 
							   (result.ogdescription) || 
							   (result.ogurl) ||
						       (result.site_title) || 
							   (result.meta_description)) 
							   && (result.iframe)){
					//IFRAME & OG INFORMATION
						html += "<div class='userLink'>";
						html += "<img class='iframephoto' id='iframephoto' style='display: none;' src='"+result.ogimage+"'>";
						html += result.iframe;
						html += "<div>";
					} else if(((!result.ogimage) && 
					           (!result.ogtitle) && 
							   (!result.ogdescription) && 
							   (!result.ogurl) && 
							   (!result.iframe)) 
						       && ((result.site_title) || 
							       (result.meta_description)) ){
					//ONLY META AND TITLE INFORMATION  
						html += "<div class='userLink'>";
						if(result.site_title && result.site_title != ""){
							html += "<h2 class='postHeadPreview'>"+result.site_title+"</h2>";
						}
						if(result.meta_description && 
						   result.meta_description != ""){
							html += "<span class='postDescPreview'>"+result.meta_description+"</span>";
						}
						html += '</div>';
					} else if(((result.ogimage) || 
					           (result.ogtitle) || 
							   (result.ogdescription) || 
							   (result.ogurl)) 
							   && (!result.iframe)){
					//OG ONLY NO IFRAME
						html += "<div class='userLink linkWrap'>";
						if(result.ogimage && 
						   result.ogimage != ""){
							html += "<img class='linkImgPreview' src='"+result.ogimage+"'>";
						}
						if(result.ogtitle && 
						   result.ogtitle != ""){
							html += "<h2>"+result.ogtitle+"</h2>";
						}
						if(result.ogdescription && 
						   result.ogdescription != ""){
							html += "<span class='postDescPreview'>"+result.ogdescription+"</span>";
						} else if(result.meta_description && 
						          result.meta_description != ""){
							html += "<span class='postDescPreview'>"+result.meta_description+"</span>";
						}
						if((result.sitename && 
						    result.sitename != "") 
							&& (result.ogurl && 
							    result.ogurl != "")){
							html += "<b><a class='postLinkPreview' rel='nofollow' href='"+result.ogurl+"' target='_blank'>"+result.sitename+"</a></b>";
						} 
						html += "</div>";
					} else {
					//DETECTION FAILED
						$("input#addlink").css("color","red");
					}
					if(html && html != ""){
						iaddphoto
						   .curPane
						   .append("<i class='bigx fa fa-times fa-3x' ></i>");
						$("input#addlink")
							    .val("")
							    .remove();
						iaddphoto
						    .curPane
							.append(html);
						iaddphoto
						    .userTagPane
							.css("display","block");
						iaddphoto
						    .submittedTags
							.css("display","block");
					} else {
						$("input#addlink").css("color", "red");
					}
					break;
				}
		  },
          complete: function(){
            $("img.ajaxImage").remove();
			iaddphoto
			   .buttonToggleInputs
			   .attr("disabled",false);
			$("body").focus();
          }
        })
        
      }
    },300)
  })
	
  /*ADD PHOTO/VIDEO TO COMMENT*/
  $("body").on("change", ".photoFileButton, .videoFileButton", function(){
    $("#addlink").remove(); //zAddPhoto.addLink = $("#addlink");
    iaddphoto.curButton = $(this);//zAddPhoto.curButton = $(this);
    
    //KEEP TAG PANE HIDDEN ON REPLY PHOTO
    var paneCheck = $(this)
					   .attr("class")
					   .split(" ");
    if(paneCheck[0] == 'userReplyFile'){
      iaddphoto.showPanes = false;
    } else {
      iaddphoto.showPanes = true;
    }
    
    iaddphoto.curForm = iaddphoto
						   .curButton
						   .parent("div")
						   .parent("form"); //zAddPhoto.curform
    if(iaddphoto.curPane){
      iaddphoto
	     .curPane
		 .find("img")
		 .remove();
    }
    iaddphoto.curPane = iaddphoto
					      .curForm
						  .children("div.tagPane"); //zAddPhoto.curPane
    if(iaddphoto.curButton.val() != ''){
      iaddphoto
	     .curForm
		 .submit();//form.addPostForm
    }
  });
  $("body").on("submit", ".addPostForm", function(event){
    $form = $(this);
    $("p.error").remove();
	$(".chatBoxWrap").remove();
    iaddphoto
	    .userTagPane
		.css("display","none");
	iaddphoto
	    .submittedTags
		.css("display", "none");
    /*DETERMINE IF BROWSER SUPPORTS new FormData()*/
    if(window.FormData === undefined){
      //IFRAME ROUTE
      iaddphoto
	      .curPane
		  .append("<img class='ajaxImage' src='"+__LOCATION__+"/assets/images/green-bars.gif'>");
      $form
	   .find("iframe")
	   .load(function(){ //zAddPhoto.curFrame
        $("p.error").remove();
        var isrc = iaddphoto
					.curForm
					.children("iframe")
					.contents()
					.find("body")
					.find("textarea")
					.html();
		isrc = $.parseJSON(isrc) || null;
        iaddphoto
		  .curPane
		  .find("img")
		  .remove();
		if(isrc){
			var iStatus = isrc.code;
			switch(iStatus){
				case 500:
				case 402:
					iaddphoto
						.submittedTags
						.after("<p class='error'>"+isrc.status+"</p>");
				break;
				case 401:
					$("body").append(doSignUpBox());
					iaddphoto
						.buttonToggleInputs
						.attr("disabled","disabled");
				break;
				default: 
				  if(isrc.media_type == "photo"){
				    iaddphoto
					   .curPane
					   .append("<img style='width:98%;' class='postImgPreview' src='"+isrc.file_source+"'>");
                    //DON'T DISPLAY TAG PANE ON REPLY PHOTO
                   if(iaddphoto.showPanes){
				     iaddphoto
					    .userTagPane
						.css("display","block");
				     iaddphoto
					    .userTagPane
						.css("display","block");
                   }
                   iaddphoto
				      .curButton
					  .attr("disabled", "disabled");
                   iaddphoto
				      .curPane
					  .append(bigX());
                 } else if(isrc.media_type == "video") {
			       var timeStamp = (new Date()).getTime();
			       var videoSource = isrc.file_source
                   iaddphoto
				      .curPane
					  .append(addVideo(videoSource,timeStamp));
                   videojs('video-preview'+timeStamp,{},function(){});
                   iaddphoto
				      .userTagPane
					  .css("display","block");
			       iaddphoto
				      .curPane
					  .append(bigX());
                   iaddphoto
				      .buttonToggleInputs
					  .attr("disabled","disabled");
			       iaddphoto
				      .userTagPane
					  .css("display","block");
			       iaddphoto
				      .submittedTags
					  .css("display","block");
		         } else {
			       iaddphoto
				      .submittedTags
					  .after("<p class='erro'>Internal error</p>");
		         }
				break;
			 }
		} else {
		   iaddphoto
		      .submittedTags
			  .after("<p class='erro'>Internal error</p>");
		}
		iaddphoto
		   .photoBtn
		   .val("");
	  })
    } else {
      //NEW FormData ROUTE
      if(!event){
        event = window.event
      }
      event.preventDefault();
      $.ajax({
        beforeSend: function(){
          $(".error").remove();
		  iaddphoto
		     .curPane
			 .find("img")
			 .remove();
		  iaddphoto
		     .userTagPane
			 .css("display","none");
		  iaddphoto
		     .submittedTags
			 .css("display", "none");
          iaddphoto
		     .curPane
			 .append("<img class='ajaxImage' src='"+__LOCATION__+"/assets/images/green-bars.gif'>");
        },
        type: 'POST',
        url: $form.attr("action"),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(result){
		  if(result){
			  isrc = $.parseJSON(result);
			  iStatus = isrc.code
			  switch(iStatus){
				case 500:
				case 402:
					iaddphoto
					   .submittedTags
					   .after("<p class='error'>"+isrc.status+"</p>")
				break;
				case 401:
					$("body").append(doSignUpBox());
					iaddphoto
					   .buttonToggleInputs
					   .attr("disabled","disabled");
				break;
				default: 
				  if(isrc.media_type == "photo"){
						iaddphoto
						   .curPane
						   .append("<img class='postImgPreview' src='"+isrc.file_source+"'>");
						iaddphoto
						   .userTagPane
						   .css("display","block"); 
						iaddphoto
						   .curPane
						   .append("");
						iaddphoto
						    .buttonToggleInputs
							.attr("disabled","disabled");
						iaddphoto
						    .curPane
							.append(bigX());
						//DON'T DISPLAY TAG PANE ON REPLY PHOTO
					    if(iaddphoto.showPanes){
						  iaddphoto.userTagPane.css("display","block");
						  iaddphoto.submittedTags.css("display","block");
					    }
				  } else if(isrc.media_type == "video"){
					  var timeStamp = (new Date()).getTime();
					  var videoSource = isrc.file_source;
                      iaddphoto
					      .curPane
						  .append(addVideo(videoSource,timeStamp));
                      videojs('video-preview'+timeStamp,{},function(){});
			          iaddphoto
					      .userTagPane
						  .css("display","block");
                      iaddphoto
					      .curPane
						  .append(bigX());
                      iaddphoto
					      .buttonToggleInputs
						  .attr("disabled","disabled");
			          iaddphoto
					      .userTagPane
						  .css("display","block");
			          iaddphoto
					      .submittedTags
						  .css("display","block");
				  } else {
					  iaddphoto
					      .submittedTags
						  .after("<p class='error'>Internal error</p>");
				  }
				break;
			  }
		  } else {
			  iaddphoto
			     .submittedTags
				 .after("<p class='error'>Internal error</p>");
		  }
		  iaddphoto
		     .photoBtn
			 .val("");
        },
        complete: function(){
          iaddphoto
		     .curPane
			 .find("img.ajaxImage")
			 .remove();
		  iaddphoto
		     .photoBtn
			 .val("");
		}
      })
    }
  });
  
  /*BIGX REMOVE PHOTO*/
  $("body").on("click", "i.bigx", function(){
    iaddphoto
	   .curPane
	   .find("img,h2,span,br,a, div.vidoe-js, video, div.userLink")
	   .remove();
    iaddphoto
	   .curPane
	   .html("");
	iaddphoto
	   .buttonToggleInputs
	   .attr("disabled",false);
    iaddphoto.curPane = null;
    iaddphoto
	   .curButton
	   .val("");
	iaddphoto
	   .photoBtn
	   .val("");
	iaddphoto
	   .videoBtn
	   .val("");
	iaddphoto
	   .linkBtn
	   .val("");
    if(iaddphoto.curTags == null){
	  iaddphoto
	   .userTagPane
	   .css("display","none");
	  iaddphoto
	   .submittedTags
	   .css("display","none");
    }
  });

  /*TAG PANE*/
  $("div.userTagPane").keydown(function(event){
    $pane = $(this);
    var $key = event.keyCode
    if(event.keyCode == 13){
      var curText = $pane.html();
      curText = $.trim(curText);
      if(curText == ""){
        return false;
      }
      
      curText = curText
	             .replace(/&/g, "&amp;")
				 .replace(/>/g, "&gt;")
				 .replace(/</g, "<")
				 .replace(/"/g, "")
				 .replace(/'/g, "")
				 .replace(/#/g, "");
      if(curText == ""){
        return false;
      }
      /*curText = "#"+curText;*/
      curText = curText.replace(/&amp;nbsp;/g, "");
      curText = curText.replace(/^\s+|\s+$/gm,'');
      curText = curText.replace(/<br>/g, "");
      curText = curText.replace(/<p>/g, "");
      curText = curText.replace(/<\/p>/g, "");
      curText = curText.replace(/<div>/g, "");
      curText = curText.replace(/<\/div>/g, "");
      //alert(curText);
      $pane.html("");
	  iaddphoto
		 .submittedTags
		 .find("br,p,div")
		 .remove();
	  iaddphoto
		 .submittedTags
		 .prepend("<span class='newTag' style='margin-left: 5px; padding: 5px 0px; display: inline-block;'>"+curText+"</span>");
	  return false;
    }
  })
  $("body").on("click", "span.newTag", function(){
    $span = $(this);
    $span.remove();
    $("div.userTagPane").focus();
  });
  /*FEED BOX*/
  $("#userFeedBox").keyup(function(event){
    $box = $(this);
    if(iaddphoto.curPane === null){
      if($box.val() != ""){
        $(".userTagPane, .submittedTags").css("display", "block");
        iaddphoto.curTags = true;
      } else {
        $(".userTagPane, .submittedTags").css("display", "none");
        iaddphoto.curTags = null;
      }
    }
  })
});

/*SHARE A POST*/
$(function(){
  $("body").on("click", "span.shareLink", function(){
    $button = $(this);
    var shareId = $button.attr("id");
    var strStart = shareId.indexOf("-")+1;
    var strEnd = shareId.length;
    var ishare = shareId.slice(strStart,strEnd);
    var postType = $button.parent("b").parent("div.shareCount").parent("div.repliesHead").siblings("div.replyForm").find("input.post_type").val();
    console.log(ishare)
 	console.log(postType)
    $.ajax({
      beforeSend: function(){
        $("div.shareMsg").remove();
      },
	  //*** NEEDS ATTN ***
      url: __LOCATION__ + '/share.php',
      type: 'POST',
      data: {share_id: ishare, post_type: postType},
      success: function(result){
        console.log(result)
        if(result == 0){
          //FALL THROUGH
        } else {
          //ADD NEW SHARE MESSAGE
          $button
            .parent("b")
            .parent("div.shareCount")
            .parent("div.repliesHead")
            .before('<div class="shareMsg">successfully added to feed</div>');
        }
      }
    })

  })
})

/*SMOKING NOW BUTTON*/
$(function(){
  $("body").on("click", "button.smokingButton", function(){
    $button = $(this);
    var parent = $button.parent("div.smokingButtonWrap");
    var smoking = $button.attr("id");
    var strStart = smoking.indexOf("-")+1;
    var strEnd = smoking.length;
    var sending = false;
    var smokeCheck = $("span.smokingnow")
    var smokingId = smoking.slice(strStart,strEnd);
    
    if(!sending && !smokeCheck.length){
      $.ajax({
        beforeSend: function(){
          sending = true;
        },
        url: __LOCATION__ + '/now-smoking.php?user='+smokingId,
        success: function(result){
          //console.log(result);
          if(result == 0){
            $(".chatBoxWrap").remove();
            $("body").append(doSignUpBox());
          } else {
            parent.append('<span class="smokingnow"><span class="nowtext">now smoking</span> '+result+'</span>');
          }
        },
        complete: function(){
          sending = false;
        }
      })
    }
    
  })
});

//DELETE A POST CLICK HANDLER & TOOLTIP
$(function(){
	//TOOLTIP
	var editClick = false;
	$("body").on("click", ".editLink", function(){
		var curLink = $(this);
		var curDelete = curLink.siblings(".delete");
		
		if(editClick){
			$(curDelete).css("display", "none");
		} else {
			$(curDelete).css("display", "block");
		}
		editClick = editClick ? false : true;
	});
	
	//DELETE BUTTON
	$("body").on("click", ".delete", function(){
		var curLink = $(this);
		var commWrap = curLink.parent("div.commHead").parent("div.commWrap");
		var curIdWrap = curLink.find("span").attr("id");
		var curInfo = curIdWrap.split("|");
		curId = curInfo[1];
		curPostType = curInfo[2];
		
		var url = __LOCATION__ + '/delete-post.php';
		var data = {post_id: curId, post_type: curPostType};
		
		if(editClick){
			$.ajax({
				beforeSend: function(){
					//CLOSE TOOPTIP & RESET CLICK STATE
					curLink.css("display", "none");
					editClick = false;
				},
				url: url,
				type: 'POST',
				data: data,
				success: function(json){
					var json = $.parseJSON(json);
					var status_id = json.status_code;
					var status_msg = json.status_message;
					if(status_id == 0){
						//ERROR
						alert(status_msg);
					} else if(status_id == 1){
						//SUCCESS
						commWrap.remove();
					}
				},
				complete: function(){
					//EMPTY
				}
			})
		}
		
	})
});


function adjust(){
  var height = document.documentElement.clientHeight;
  var listHeight = height - 168 + "px"
  var searchHeight = height - 101 + "px";
  var suggestHeight = height - 265 + "px";
  $("#listings").css({
    height: listHeight
  })
  $("#searchBox").css({
    height: searchHeight
  });
  /*$(".suggestions").css("height", suggestHeight);*/
};

window.onload = adjust;
window.onresize = adjust;