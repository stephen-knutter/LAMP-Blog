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
$(function(){
  var winHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
  var winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  $("#changePhotoForm").prepend("<input type='hidden' name='winheight' value='"+winHeight+"'>")
})

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
  $("#userProfilePic, #changePhotoForm, #userPicImg").hover(function(){
    $(".newPic").css("display", "block")
  }, function(){
    $(".newPic").css("display", "none")
  });

  $("#changePhotoForm").ajaxForm({
    beforeSend: function(){
      $(".uploadError, .error, .success").remove();
      $("#ajaxUploadImg").css("display", "block");
    },
    success: function(result){
      var parent = $("#userProfilePic");
      if(result == 2 || result == 0){
        $(".userProfilePic").append("<p class='uploadError'>Upload Error</p>");
      } else if(result == 3){
        $(".userProfilePic").append("<p class='uploadError'>Internal Error</p>");
      } else if(result == 4){
        $(".userProfilePic").append("<p class='uploadError'>File must be gif, jpg, or png smaller than 10MB</p>");
      } else if(result == 10){
        $(".userProfilePic").append("<p class='uploadError'>Browser window too small</p>");
      } else {
        var ilength = result.length;
        var dash = result.indexOf("-");
        var amper = result.indexOf("&");
        var width = result.slice(0,dash);
        var height = result.slice(dash+1, amper);
        var photo = result.slice(amper+1, ilength);
        var winHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        winHeight = winHeight - 250;
        if(height > winHeight){
          height = winHeight;
        }
        var winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        document.scrollTop = 0;
        $('html, body').css({'overflow':'hidden', 'height':'100%'});
        $("<div id='overlay'></div>")
          .css({
            'top': 0,
            'opacity': 0,
            'z-index': 10
          })
          .animate({'opacity':'0.5'}, 'slow')
          .appendTo('body')
          
        $("<div id='lightbox'></div>")
          .append(
            '<div id="newPicForm" style="width: '+width+'px; height: '+height+'px;">' +
            '<form enctype="multipart/form-data" action="../../../add-photo.php" method="post" id="submitNewPic">' +
            '<img style="width: '+width+'px; height:'+height+'px;" src="'+'https://www.budvibes.com/user-images/'+photo+'" id="newPic" />' +
            '<span id="picPreview">Pic Preview</span>'+
            '<i>(click above to crop)</i>' +
            /*'<input type="file" name="newphoto" id="newPhotoBack"/>' +*/
            '<input type="hidden" name="x" id="x" val=""/>' +
            '<input type="hidden" name="y" id="y" val=""/>' +
            '<input type="hidden" name="x2" id="x2" val=""/>' +
            '<input type="hidden" name="y2" id="y2" val=""/>' +
            '<input type="hidden" name="w" id="w" val=""/>' +
            '<input type="hidden" name="h" id="h" val=""/>' +
            '<div id="previewContain"><img src="'+'https://www.budvibes.com/user-images/'+photo+'" id="preview" /></div>'+
            '<input type="submit" id="savePhoto" name="submit" value="Save" />' +
            '<input type="button" id="cancelPhoto" name="cancel" value="Cancel" />' +
            '</form>' +
            '</div>'
          )
          .css('z-index', '2000')
          .appendTo('body')
          
          positionLightBox();
      }
    },
    complete: function(){
      $("#changeFileButton").val("");
      $("#ajaxUploadImg").css("display", "none");
    }
  })
  
  /*FORCE SUBMIT ONCE PHOTO IS UPLOADED*/
  $("#changeFileButton").change(function(){
    if($("#changeFileButton") != ""){
      $("#changePhotoForm").submit();
    }
  });
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
		data: {x: x, y: y, x2: x2, y2: y2, w: w, h: h, pic: pic},
		success: function(result){
		removeLightbox();
		if(result == 0){
			$("#userProfilePic").append("<p class='uploadError'>Error uploading file</p>");
		} else {
			/*window.location = 'http://www.budmapz.com/'+result.trim();*/
			location.reload();
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
  /*CHANGE USERNAME SUBMIT*/
  $("body").on("submit", "#editUsernameForm", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    $form = $(this);
    var buttonWrap = $form.find("div.editButtonWrap");
    var button = buttonWrap.find("input#newNameButton");
    var buttonVal = button.val();
    var newNameContain = $form.find("div.editTextWrap").find("input#new_username");
    var newName = newNameContain.val();
    var url = $form.attr("action");
    //var url = url+"?username="+newName;
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
        button.attr("disabled","disabled");
        button.val("");
      },
	  type: 'POST',
	  data: {username: newName},
      url: url,
      success: function(result){
        result = result.trim();
        if(result == 0){
          $form.before("<p class='error'>log in to edit</p>");
        } else if(result == 2){
          $form.before("<p class='error'>username already taken</p>");
        } else if(result == 3){
          $form.before("<p class='error'>internal Error</p>");
        } else if(result == 4){
          $form.before("<p class='error'>invalid username</p>");
        } else if(result == 5){
          $form.before("<p class='error'>username cannot be blank</p>");
        } else {
          /*window.location = 'http://www.budmapz.com/edit.php?user='+result;*/
          //newNameContain.val(result);
		  window.location = 'https://www.budvibes.com/'+result+'/edit';
          $form.before("<p class='success'>username changed successfully</p>");
        }
        
      },
      complete: function(){
        buttonWrap.find("img.hourglass").remove();
        button.attr("disabled", false);
        button.val(buttonVal);
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
    var buttonWrap = $form.find("div.editButtonWrap");
    var button = buttonWrap.find("input#newEmailButton");
    var buttonVal = button.val();
    var newEmailContain = $form.find("div.editTextWrap").find("input#new_email");
    var newEmail = newEmailContain.val();
    var url = $form.attr("action");
    //url = url+"?email="+newEmail;
    
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
        button.attr("disabled","disabled");
        button.val("");
      },
	  type: 'POST',
	  data: {email: newEmail},
      url: url,
      success: function(result){
        if(result == 0){
          $form.before("<p class='error'>log in to edit</p>");
        } else if(result == 2){
          $form.before("<p class='error'>email currently registered</p>");
        } else if(result == 3){
          $form.before("<p class='error'>internal Error</p>");
        } else if(result == 4){
          $form.before("<p class='error'>invalid email</p>");
        } else if(result == 5){
          $form.before("<p class='error'>email cannot be blank</p>");
        } else {
          newEmailContain.val(result);
          $form.before("<p class='success'>email changed successfully</p>");
        }
      },
      complete: function(){
        buttonWrap.find("img.hourglass").remove();
        button.attr("disabled", false);
        button.val(buttonVal);
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
    var buttonWrap = $form.find("div.editButtonWrap");
    var button = buttonWrap.find("input#changePass");
    var buttonVal = button.val();
    var oldPassBox = $form.find("div.oldPassWrap").find("input#old_pass");
    var oldPass = $form.find("div.oldPassWrap").find("input#old_pass").val();
    var newPassBox = $form.find("div.newPassWrap").find("input#new_pass")
    var newPass = $form.find("div.newPassWrap").find("input#new_pass").val();
    var confirmPassBox = $form.find("div.confirmWrap").find("input#confirm_pass")
    var confirmPass = $form.find("div.confirmWrap").find("input#confirm_pass").val();
    var url = $form.attr("action");
    
    $.ajax({
      beforeSend: function(){
        $(".error, .uploadError, .success").remove();
        buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
        button.attr("disabled","disabled");
        button.val("");
      },
      type: 'POST',
      data: {old_pass: oldPass, new_pass: newPass, confirm: confirmPass},
      url: url,
      success: function(result){
        if(result == 0){
          $form.before("<p class='error'>Log in to edit</p>");
        } else if(result == 1){
          $form.before("<p class='success'>Password successfully changed</p>");
          oldPassBox.val("");
          newPassBox.val("");
          confirmPassBox.val("");
        } else if(result == 2){
          $form.before("<p class='error'>internal Error</p>");
        } else if(result == 3){
          $form.before("<p class='error'>invalid password</p>");
        } else if(result == 4){
          $form.before("<p class='error'>passwords do not match</p>");
        } else if(result == 5){
          $form.before("<p class='error'>invalid password</p>");
        } else if(result == 6){
          $form.before("<p class='error'>one or more fields blank</p>");
        }
      },
      complete: function(){
        buttonWrap.find("img.hourglass").remove();
        button.attr("disabled", false);
        button.val(buttonVal);
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
  
  /*NAVIGATOR GEOLOCATION*/
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
});

/*USER FEEDS (POST AND REPY)*/
iaddphoto = {
  curButton: null,
  curForm: null,
  curId: null,
  curPane: null,
  curTags: null,
  showPanes: null
}

/*NEW POST*/
$(function(){
  curTagPane = $(".userTagPane");
  curSubmittedTags = $(".submittedTags");
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
    /*CURRENT FEED WALL ID AND USERNAME*/
    var buttonClass = $button.attr("class");
    var dash = buttonClass.indexOf("-");
    var strEnd = buttonClass.length;
    var curWallId = buttonClass.slice(dash+1,strEnd);
    var curWallUser = buttonClass.slice(0,dash);
    /*XHR TYPE*/
    var xhrType = buttonWrap.siblings("input.xhr_type").val();
    /*POST TYPE(PROD OR USER)*/
    var postType = buttonWrap.siblings("input.post_type").val();
    /*TAG PANE*/
    userCurPane = buttonWrap.siblings("div.tagPane");
    /*USER PHOTO*/
    var userPhotoImg = userCurPane.find("img.postImgPreview");
    var userPhoto = userPhotoImg.attr("src");
    /*USER VIDEO*/
    var userVideoWrap = userCurPane.find("video");
    var userVideo = userVideoWrap.find("source").attr("src");
    /*LINK INFO*/
    var userLinkWrap = userCurPane.find("div.userLink");
    //var linkHtml = userLinkWrap.html();
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
    /*USER COMMENT*/
    var userPostBox = buttonWrap.siblings("textarea#userFeedBox");
    var userPost = userPostBox.val();
    /*TAGS*/
    var tags = Array();
    var tagPane = buttonWrap.siblings("div.submittedTags");
    var curTags = tagPane.find("span.newTag");
    curTags.each(function(){
      tags.push($(this).text());
    })
    /*var tagsJSON = JSON.stringify(tags);*/
    /*RATING*/
    var curRating = buttonWrap.siblings("div.stars").find('input[name=rating]:checked').val();
    var url = 'https://www.budvibes.com/add-post.php';
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
        buttonWrap.append("<img class='postgear' src='https://www.budvibes.com/images/postgear.gif'>");
        $(".error").remove();
        $button.attr("disabled", "disabled").html("");
      },
      type: 'POST',
      data: {cur_wall_id: curWallId, cur_wall_user: curWallUser, text: userPost, 
        photo: userPhoto, video: userVideo, tags: tags, rating: curRating, xhr_type: xhrType, post_type: postType, media: media, 
        link: userLink, link_info: linkHtml, iframe: iframePhoto},
      url: url,
      success: function(result){
        //console.log(result);
        if(result == 0){
          $(".submittedTags").after("<p class='error'>Error adding comment</p>");
        }else if(result == 2){
          $(".submittedTags").after("<p class='error'>Must insert comment or add photo or video</p>");
        } else {
          $(".dropPane").prepend(result);
          var vidId = $(".dropPane").first("div.commPostVideo").find("video").attr("id");
          if(vidId == 'newvideo'){
            videojs('newvideo',{},function(){});
          }
          $("img.postImgPreview").remove();
          $("div.userLink").remove();
          $("div.tagPane").find("div.video-js, video").remove();
          userPostBox.val("");
          userPhotoImg.remove();
          if(iaddphoto.curButton){
            iaddphoto.curButton.attr("disabled", false);
          }
          $(".userTagPane, .submittedTags, .bigx").css("display", "none").html("");
          $("div.stars").find("div").find("a").removeClass("rating");
          iaddphoto.curButton = null;
          iaddphoto.curForm = null;
          iaddphoto.curId = null;
          iaddphoto.curPane = null;
          
        }
        
      },
      complete: function(){
        buttonWrap.find("img.postgear").remove();
        $button.attr("disabled",false).html(buttonVal);
        $("div.buttonToggle").find("input").attr("disabled", false);
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
    var url = 'https://www.budvibes.com/add-reply.php';
    
    $.ajax({
      beforeSend: function(){
        $("p.error").remove();
        //buttonWrap.append("<img class='replygear' src='https://www.budvibes.com/images/postgear.gif'>");
        //buttonWrap.append("<img class='replygear' src='http://www.budmapz.com/images/geargray.gif'>");
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
    iaddphoto.curForm = iaddphoto.curButton.parent("div").parent("form");
    iaddphoto.curPane = iaddphoto.curButton.parent("div").siblings("div.tagPane");
    $("input#addlink").remove();
    iaddphoto.curPane.before('<input type="text" name="addlink" id="addlink" placeholder="Paste a link here" />');
  });

  $("body").on("paste", "input#addlink", function(event){
	inputCache = null;
    e = $(this);
    $(".error").remove();
	
    var timeoutId = setTimeout(function(){
      if(e.val() != "" && e.val() != inputCache){
        $("div.buttonToggle").find("input").attr("disabled", "disabled");
        //iaddphoto.curPane = e.siblings("div.tagPane");
        elink = inputCache = e.val();
		//alert(elink + ' - ' + inputCache);
        //elink = encodeURIComponent(elink);
        //clearInterval(timeoutId);
        url = 'https://www.budvibes.com/add-link.php';
        $.ajax({
          beforeSend: function(){
            iaddphoto.curPane.append("<img class='ajaxImage' src='https://www.budvibes.com/images/green-bars.gif'>");
          },
          type: 'POST',
          url: url,
          data: {add_link : elink},
          success: function(result){
			inputCache = null;
            //console.log(result)
            if(result == 0){
              //alert('internal error');
              e.css("color","red");
            } else if(result == 6){
              $(".chatBoxWrap").remove();
              $("body").append(
                '<div class="chatBoxWrap" id="thread-no">'+
                '<div class="chatBoxHead clearfix"><span class="chatName" id="chat-none">Sign Up</span><span class="chatClose">X</span></div>'+
                '<div class="chatBoxBody">'+
                  '<div id="signInMenu" class="signUpMenu">'+
                    '<h3><img src="https://www.budvibes.com/images/sign-up-head.png" alt="User Sign Up"></h3>'+
                    '<form action="../../../sign-up.php" method="post" id="signUpForm">'+
                      '<input type="text" class="signInput" name="username" placeholder="Username">'+
                      '<input type="text" class="signInput" name="email" placeholder="Email">'+
                      '<input type="password" class="signInput" name="pass" placeholder="Password">'+
                      '<input type="password" class="signInput" name="confirmpass" placeholder="Confirm Password">'+
                      '<input type="submit" class="signSubmit" name="signup" value="Sign Up">'+
                    '</form>'+
                    '<div id="signUp">'+
                      '<a href="https://www.budvibes.com/sign-in.php" id="signInLink">&#8592; Sign In</a>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<div class="chatBoxPostWrap">'+
                  '<div class="chatReplyBoxWrap clearfix">'+
                    '<div class="chatBox" contenteditable="true"></div>'+
                  '</div>'+
                '</div>'+
                '<div class="chatButtons clearfix">'+
                  '<div class="chatCamWrap">'+
                    '<form id="chatPic" action="./add-message.php" type="post">'+
                      '<input type="file" name="pic" class="chatCamFile" />'+
                      '<input type="hidden" name="message" value="NULL" />'+
                      '<input type="hidden" name="emoji" value="NULL" />'+
                      '<input type="hidden" name="thread" value="none" />'+
                      '<input type="hidden" name="user" value="none" />'+
                      '<img class="chatCamImg" src="https://www.budvibes.com/images/chat-cam.png" />'+
                    '</form>'+
                  '</div>'+
                  '<div class="chatEmoWrap">'+
                    '<img class="chatEmoImg" src="https://www.budvibes.com/images/chat-smile.png" />'+
                  '</div>'+
                '</div>'+
              '</div>'
              );
              $("input#addlink").html("").remove();
              $(".buttonToggle").find("input").attr("disabled",false);
            } else {
              result = $.parseJSON(result);
              if(!result){
                $("img.ajaxImage").remove();
                e.css("color","red");
              }
              //tagPane = e.parent("div#linkFileButtonWrap").siblings("div.tagPane");
              var html = ""
              if((!result.ogimage || result.ogimage == "") && (!result.ogtitle || result.ogtitle == "") && (!result.ogdescription || result.ogdescription == "") && (!result.ogurl || result.ogurl == "")
              && (!result.site_title || result.site_title == "") && (!result.meta_description || result.meta_description == "") && (!result.iframe || result.iframe == "")){
                //NO RESULTS
                $("input#addlink").css("color","red");
              } else if((!result.ogimage) && (!result.ogtitle) && (!result.ogdescription) && (!result.ogurl)
                  && (!result.site_title) &&  (!result.meta_description) && (result.iframe)){
                //IFRAME ONLY
                html += "<div class='userLink'>";
                html += "<img class='iframephoto' id='iframenophoto' style='display:none;' src='https://www.budvibes.com/images/videoholder.png' />";
                html += result.iframe;
                html += "<div>";
              } else if( ((result.ogimage) || (result.ogtitle) || (result.ogdescription) || (result.ogurl)
                    || (result.site_title) || (result.meta_description)) && (result.iframe)){
                //IFRAME & OG INFORMATION
                html += "<div class='userLink'>";
                html += "<img class='iframephoto' id='iframephoto' style='display: none;' src='"+result.ogimage+"'>";
                html += result.iframe;
                html += "<div>";
              } else if( ((!result.ogimage) && (!result.ogtitle) && (!result.ogdescription) && (!result.ogurl) && (!result.iframe)) 
                && ((result.site_title) || (result.meta_description)) ){
                //ONLY META AND TITLE INFORMATION  
                  html += "<div class='userLink'>";
                  if(result.site_title && result.site_title != ""){
                    html += "<h2 class='postHeadPreview'>"+result.site_title+"</h2>";
                  }
                  if(result.meta_description && result.meta_description != ""){
                    html += "<span class='postDescPreview'>"+result.meta_description+"</span>";
                  }
                  html += '</div>';
              } else if( ((result.ogimage) || (result.ogtitle) || (result.ogdescription) || (result.ogurl)) && (!result.iframe)){
                //OG ONLY NO IFRAME
                  html += "<div class='userLink linkWrap'>";
                  if(result.ogimage && result.ogimage != ""){
                    html += "<img class='linkImgPreview' src='"+result.ogimage+"'>";
                  }
                  if(result.ogtitle && result.ogtitle != ""){
                    html += "<h2>"+result.ogtitle+"</h2>";
                  }
                  if(result.ogdescription && result.ogdescription != ""){
                    html += "<span class='postDescPreview'>"+result.ogdescription+"</span>";
                  } else if(result.meta_description && result.meta_description != ""){
                    html += "<span class='postDescPreview'>"+result.meta_description+"</span>";
                  }
                  if((result.sitename && result.sitename != "") && (result.ogurl && result.ogurl != "")){
                    html += "<b><a class='postLinkPreview' rel='nofollow' href='"+result.ogurl+"' target='_blank'>"+result.sitename+"</a></b>";
                  } 
                  html += "</div>";
              } else {
                //DETECTION FAILED
                $("input#addlink").css("color","red");
              }
              if(html && html != ""){
                iaddphoto.curPane.append("<img class='bigx' src='https://www.budvibes.com/images/bigx.png' title='Remove'/>");
                $("input#addlink").val("").remove();
                iaddphoto.curPane.append(html);
                $(".userTagPane, .submittedTags").css("display", "block");
              } else {
                $("input#addlink").css("color", "red");
              }
              
            }
            //
          },
          complete: function(){
            $("img.ajaxImage").remove();
            $(".buttonToggle").find("input").attr("disabled",false);
			//clearInterval(timeoutId);
			$("body").focus();
          }
        })
        
      }
    },300)
  })

  /*ADD PHOTO TO COMMENT*/
  $("body").on("change", ".photoFileButton, .videoFileButton", function(){
    $("#addlink").remove();
    iaddphoto.curButton = $(this);
    
    //KEEP TAG PANE HIDDEN ON REPLY PHOTO
    var paneCheck = $(this).attr("class").split(" ");
    if(paneCheck[0] == 'userReplyFile'){
      iaddphoto.showPanes = false;
    } else {
      iaddphoto.showPanes = true;
    }
    
    iaddphoto.curForm = iaddphoto.curButton.parent("div").parent("form");
    if(iaddphoto.curPane){
      iaddphoto.curPane.find("img").remove();
    }
    iaddphoto.curPane = iaddphoto.curForm.children("div.tagPane");
    if(iaddphoto.curButton.val() != ''){
      iaddphoto.curForm.submit();//form.addPostForm
    }
  });
  $("body").on("submit", ".addPostForm", function(event){
    $form = $(this);
    $("p.error").remove();
    $(".userTagPane, .submittedTags").css("display", "none");
    
    /*DETERMINE IF BROWSER SUPPORTS new FormData()*/
    if(window.FormData === undefined){
      //IFRAME ROUTE
      iaddphoto.curPane.append("<img class='ajaxImage' src='https://www.budvibes.com/images/green-bars.gif'>");
      $form.find("iframe").load(function(){
        $("p.error").remove();
        var isrc = iaddphoto.curForm.children("iframe").contents().find("body").find("textarea").html();
        iaddphoto.curPane.find("img").remove();
        if(isrc == 0){
          $(".submittedTags").after("<p class='error'>Error uploading file</p>");
        } else if(isrc == 2) {
          $(".submittedTags").after("<p class='error'>File must be gif, jpg, or png smaller than 10MB</p>");
        } else if(isrc == 3){
          $(".submittedTags").after("<p class='error'>Video added successfully</p>");
        } else if(isrc == 4){
          $(".submittedTags").after("<p class='error'>Error moving video</p>");
        } else if(isrc == 5){
          $(".submittedTags").after("<p class='error'>Invalid video format</p>");
        } else if(result == 6){
          $(".chatBoxWrap").remove();
          $("body").append(
            '<div class="chatBoxWrap" id="thread-no">'+
            '<div class="chatBoxHead clearfix"><span class="chatName" id="chat-none">Sign Up</span><span class="chatClose">X</span></div>'+
            '<div class="chatBoxBody">'+
              '<div id="signInMenu" class="signUpMenu">'+
                '<h3><img src="https://www.budvibes.com/images/sign-up-head.png" alt="User Sign Up"></h3>'+
                '<form action="../../../sign-up.php" method="post" id="signUpForm">'+
                  '<input type="text" class="signInput" name="username" placeholder="Username">'+
                  '<input type="text" class="signInput" name="email" placeholder="Email">'+
                  '<input type="password" class="signInput" name="pass" placeholder="Password">'+
                  '<input type="password" class="signInput" name="confirmpass" placeholder="Confirm Password">'+
                  '<input type="submit" class="signSubmit" name="signup" value="Sign Up">'+
                '</form>'+
                '<div id="signUp">'+
                  '<a href="https://www.budvibes.com/sign-in.php" id="signInLink">&#8592; Sign In</a>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="chatBoxPostWrap">'+
              '<div class="chatReplyBoxWrap clearfix">'+
                '<div class="chatBox" contenteditable="true"></div>'+
              '</div>'+
            '</div>'+
            '<div class="chatButtons clearfix">'+
              '<div class="chatCamWrap">'+
                '<form id="chatPic" action="./add-message.php" type="post">'+
                  '<input type="file" name="pic" class="chatCamFile" />'+
                  '<input type="hidden" name="message" value="NULL" />'+
                  '<input type="hidden" name="emoji" value="NULL" />'+
                  '<input type="hidden" name="thread" value="none" />'+
                  '<input type="hidden" name="user" value="none" />'+
                  '<img class="chatCamImg" src="https://www.budvibes.com/images/chat-cam.png" />'+
                '</form>'+
              '</div>'+
              '<div class="chatEmoWrap">'+
                '<img class="chatEmoImg" src="https://www.budvibes.com/images/chat-smile.png" />'+
              '</div>'+
            '</div>'+
          '</div>'
          );
          $(".buttonToggle").find("input").attr("disabled",false);
        } else {
          var isrc = $.parseJSON(isrc);
          if(isrc.media_type == "photo"){
            iaddphoto.curPane.append("<img style='width:98%;' class='postImgPreview' src='"+isrc.file_source+"'>");
            //DON'T DISPLAY TAG PANE ON REPLY PHOTO
            if(iaddphoto.showPanes){
              $(".userTagPane, .submittedTags").css("display", "block");
            }
            iaddphoto.curButton.attr("disabled", "disabled");
            iaddphoto.curPane.append("<img class='bigx' src='https://www.budvibes.com/images/bigx.png' title='remove'/>");
          } else {
            var timeStamp = (new Date()).getTime();
            iaddphoto.curPane.append('<video loop style="margin: 0 auto; position: relative; display: block;" class="video-preview'+timeStamp+'" class="video-js vjs-default-skin" controls preload="auto" width="516" height="516">'+
                         '<source src='+isrc.file_source+'>'+
                         '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
                        '</video>'
                      );
            videojs('video-preview'+timeStamp,{},function(){});
            iaddphoto.curPane.next("div.userTagPane").css("display", "block");
            iaddphoto.curPane.append("<img class='bigx' src='https://www.budvibes.com/images/bigx.png' title='Remove'/>");
            iaddphoto.curForm.find("div.buttonToggle").find("input").attr("disabled", "disabled");
            $(".userTagPane, .submittedTags").css("display", "block");
          }
        }
        $form.find("div.replyPhotoButtonWrap").find("input.photoFileButton").val("");
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
          $("userTagPane, .submittedTags").css("display", "none");
          iaddphoto.curPane.find("img").remove();
          iaddphoto.curPane.find("div.userTagPane").remove();
          iaddphoto.curPane.append("<img class='ajaxImage' src='https://www.budvibes.com/images/green-bars.gif'>");
        },
        type: 'POST',
        url: $form.attr("action"),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(result){
          //alert(result);
          if(result == 0){
            $(".submittedTags").after("<p class='error'>Error uploading file</p>");
          } else if(result == 2){
            $(".submittedTags").after("<p class='error'>File must be gif, jpg, or png smaller than 10MB</p>");
          } else if(result == 3){
            $(".submittedTags").after("<p class='error'>Video added successfully</p>");
          } else if(result == 4){
            $(".submittedTags").after("<p class='error'>Error moving video</p>");
          } else if(result == 5){
            $(".submittedTags").after("<p class='error'>Invalid video format</p>");
          } else if(result == 6){
            $(".chatBoxWrap").remove();
            $("body").append(
              '<div class="chatBoxWrap" id="thread-no">'+
              '<div class="chatBoxHead clearfix"><span class="chatName" id="chat-none">Sign Up</span><span class="chatClose">X</span></div>'+
              '<div class="chatBoxBody">'+
                '<div id="signInMenu" class="signUpMenu">'+
                  '<h3><img src="https://www.budvibes.com/images/sign-up-head.png" alt="User Sign Up"></h3>'+
                  '<form action="../../../sign-up.php" method="post" id="signUpForm">'+
                    '<input type="text" class="signInput" name="username" placeholder="Username">'+
                    '<input type="text" class="signInput" name="email" placeholder="Email">'+
                    '<input type="password" class="signInput" name="pass" placeholder="Password">'+
                    '<input type="password" class="signInput" name="confirmpass" placeholder="Confirm Password">'+
                    '<input type="submit" class="signSubmit" name="signup" value="Sign Up">'+
                  '</form>'+
                  '<div id="signUp">'+
                    '<a href="https://www.budvibes.com/sign-in.php" id="signInLink">&#8592; Sign In</a>'+
                  '</div>'+
                '</div>'+
              '</div>'+
              '<div class="chatBoxPostWrap">'+
                '<div class="chatReplyBoxWrap clearfix">'+
                  '<div class="chatBox" contenteditable="true"></div>'+
                '</div>'+
              '</div>'+
              '<div class="chatButtons clearfix">'+
                '<div class="chatCamWrap">'+
                  '<form id="chatPic" action="./add-message.php" type="post">'+
                    '<input type="file" name="pic" class="chatCamFile" />'+
                    '<input type="hidden" name="message" value="NULL" />'+
                    '<input type="hidden" name="emoji" value="NULL" />'+
                    '<input type="hidden" name="thread" value="none" />'+
                    '<input type="hidden" name="user" value="none" />'+
                    '<img class="chatCamImg" src="https://www.budvibes.com/images/chat-cam.png" />'+
                  '</form>'+
                '</div>'+
                '<div class="chatEmoWrap">'+
                  '<img class="chatEmoImg" src="https://www.budvibes.com/images/chat-smile.png" />'+
                '</div>'+
              '</div>'+
            '</div>'
            );
            $(".buttonToggle").find("input").attr("disabled",false);
          } else {
            var result = $.parseJSON(result);//result.media_type(video or photo) !! result.file_source
            if(result.media_type == "photo"){
              iaddphoto.curPane.append("<img class='postImgPreview' src='"+result.file_source+"'>");
              iaddphoto.curPane.next("div.userTagPane").css("display", "block");
              iaddphoto.curPane.append("<img class='bigx' src='https://www.budvibes.com/images/bigx.png' title='Remove'/>");
              //iaddphoto.curButton.attr("disabled", "disabled");
              iaddphoto.curForm.find("div.buttonToggle").find("input").attr("disabled", "disabled");
              //DON'T DISPLAY TAG PANE ON REPLY PHOTO
              if(iaddphoto.showPanes){
                $(".userTagPane, .submittedTags").css("display", "block");
              }
            } else if(result.media_type == "video") {
              var timeStamp = (new Date()).getTime();
              iaddphoto.curPane.append('<video loop style="margin: 0 auto; position: relative; display: block;" id="video-preview'+timeStamp+'" class="video-js vjs-default-skin" controls preload="auto" width="516" height="516">'+
                           '<source src='+result.file_source+'>'+
                           '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
                          '</video>'
                        );
              videojs('video-preview'+timeStamp,{},function(){});
              iaddphoto.curPane.next("div.userTagPane").css("display", "block");
              iaddphoto.curPane.append("<img class='bigx' src='https://www.budvibes.com/images/bigx.png' title='Remove'/>");
              //iaddphoto.curButton.attr("disabled", "disabled");
              iaddphoto.curForm.find("div.buttonToggle").find("input").attr("disabled", "disabled");
              $(".userTagPane, .submittedTags").css("display", "block");
            }
          }
        },
        complete: function(){
          iaddphoto.curPane.find("img.ajaxImage").remove();
          $form.find("div.replyPhotoButtonWrap").find("input.photoFileButton").val("");
        }
      })
    }
  });
  
  /*BIGX REMOVE PHOTO*/
  $("body").on("click", "img.bigx", function(){
    /*iaddphoto.curPane.find("img").fadeOut();*/
    iaddphoto.curPane.find("img,h2,span,br,a, div.vidoe-js, video, div.userLink").remove();
    /*iaddphoto.curPane.find("div.video-js, video").remove();*/
    iaddphoto.curPane.html("");
    /*iaddphoto.curButton.attr("disabled",false);*/
    iaddphoto.curForm.find("div.buttonToggle").find("input").attr("disabled", false);
    iaddphoto.curPane = null;
    iaddphoto.curButton.val("");
    $(".photoFileButton, .videoFileButton, .linkFileButton").val("");
    if(iaddphoto.curTags == null){
      $(".userTagPane, .submittedTags").css("display", "none");
    }
  })

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
      
      curText = curText.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "<").replace(/"/g, "").replace(/'/g, "").replace(/#/g, "")
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
      $(".submittedTags").find("br,p,div").remove();
      $(".submittedTags").prepend("<span class='newTag' style='margin-left: 5px; padding: 5px 0px; display: inline-block;'>"+curText+"</span>");
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

/*TOGGLE REPLY FORM*/
$(function(){
  $("body").on("click", "span.addLink", function(event){
    if(!event){
      event = window.event;
    }
    event.preventDefault();
    event.stopPropagation();
    $link = $(this);
    var parent = $link.closest("div.repliesHead");
    var form = parent.siblings("div.replyForm");
    form.slideToggle("fast");
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
      url: 'https://www.budvibes.com/share.php',
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
          //alert('Sending...');
        },
        url: 'https://www.budvibes.com/now-smoking.php?user='+smokingId,
        success: function(result){
          //console.log(result);
          if(result == 0){
            $(".chatBoxWrap").remove();
            $("body").append(
              '<div class="chatBoxWrap" id="thread-no">'+
              '<div class="chatBoxHead clearfix"><span class="chatName" id="chat-none">Sign Up</span><span class="chatClose">X</span></div>'+
              '<div class="chatBoxBody">'+
                '<div id="signInMenu" class="signUpMenu">'+
                  '<h3><img src="https://www.budvibes.com/images/sign-up-head.png" alt="User Sign Up"></h3>'+
                  '<form action="../../../sign-up.php" method="post" id="signUpForm">'+
                    '<input type="text" class="signInput" name="username" placeholder="Username">'+
                    '<input type="text" class="signInput" name="email" placeholder="Email">'+
                    '<input type="password" class="signInput" name="pass" placeholder="Password">'+
                    '<input type="password" class="signInput" name="confirmpass" placeholder="Confirm Password">'+
                    '<input type="submit" class="signSubmit" name="signup" value="Sign Up">'+
                  '</form>'+
                  '<div id="signUp">'+
                    '<a href="https://www.budvibes.com/sign-in.php" id="signInLink">&#8592; Sign In</a>'+
                  '</div>'+
                '</div>'+
              '</div>'+
              '<div class="chatBoxPostWrap">'+
                '<div class="chatReplyBoxWrap clearfix">'+
                  '<div class="chatBox" contenteditable="true"></div>'+
                '</div>'+
              '</div>'+
              '<div class="chatButtons clearfix">'+
                '<div class="chatCamWrap">'+
                  '<form id="chatPic" action="./add-message.php" type="post">'+
                    '<input type="file" name="pic" class="chatCamFile" />'+
                    '<input type="hidden" name="message" value="NULL" />'+
                    '<input type="hidden" name="emoji" value="NULL" />'+
                    '<input type="hidden" name="thread" value="none" />'+
                    '<input type="hidden" name="user" value="none" />'+
                    '<img class="chatCamImg" src="https://www.budvibes.com/images/chat-cam.png" />'+
                  '</form>'+
                '</div>'+
                '<div class="chatEmoWrap">'+
                  '<img class="chatEmoImg" src="https://www.budvibes.com/images/chat-smile.png" />'+
                '</div>'+
              '</div>'+
            '</div>'
            );
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
		
		var url = 'https://www.budvibes.com/delete-post.php';
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