/*FOLLOW / UNFOLLOW*/
$(function(){
  irelations = {
	  curButton: null,
	  curButtonText: null,
	  curButtonClass: null,
	  curButtonAction: null,
	  relationTypeWrap: $(".follow-message"),
	  relationType: null,
	  relationLink: $("span#relationLink"),
	  relationType: null,
	  relationUserId: null,
  }
  $("body").on("click", "span#relationLink, span.relationButton", function(){
    irelations.curButton = $(this);
	//$button = $(this);
	irelations.relationType = irelations
	                            .relationTypeWrap
								.attr("id");
    //var relationType = $button.parent("div").attr("id");
    if(irelations.relationType.indexOf('-') > 0){
      var dshStart = irelations
	                   .relationType
					   .indexOf("-");
      irelations.relationUserId = irelations
	                              .relationType
								  .slice(0,dshStart);
      var small = true;
    } else {
	  /*BIG BUTTON TYPE*/
	  irelations.relationType = irelations
	                              .relationLink
								  .attr("class")
								  .split(' ')[0];
      //var curPage = $("span#relationLink").attr("class").split(' ')[0]; //PAGE TYPE
	  var dshStart = irelations
						.relationType
						.indexOf("-")+1;
	  var dshEnd = irelations
					 .relationType
					 .length;
      //var pageStart = curPage.indexOf("-")+1;
      //var pageEnd = curPage.length;
	  irelations.relationUserId = irelations
								  .relationType
								  .slice(dshStart,dshEnd);
      //var curPageId = curPage.slice(pageStart,pageEnd); //PAGE ID
      //var pageRelation = $("span#relationLink").parent("div").attr("id"); irelations.relationType
      var small = false;
    }
    irelations.curButtonText = irelations
	                            .curButton
								.text();
	//var buttonHtml = $button.text();
    irelations.curButtonClass = irelations
							     .curButton
								 .attr("class")
								 .split(' ')[0];
	var actionStart = irelations
	                    .curButtonClass
						.indexOf("-");
	
	irelations.curButtonAction = irelations
	                              .curButtonClass
								  .slice(0,actionStart);
	//var curClass = $button.attr("class");
    //curClass = curClass.split(' ')[0];
    //var strSplit = curClass.indexOf("-");
    //var strEnd = curClass.length;
    //var curAction = curClass.slice(0,strSplit);//ACTION(FOLLOW/UNFOLLOW)
    //var curUser = curClass.slice(strSplit+1,strEnd);//USER ID
    if(irelations.curButtonAction == 'follow'){
      //url = __LOCATION__ + '/ajax_follow_user.php?user='+curUser+'&type='+relationType;
	  url = __LOCATION__ + '/ajax/ajax_user_follow.php';
      if(!small){
        var newHtml = "Unfollow</b>"
        var newClass = "unfollow-"
		               +irelations.relationUserId
		               +" unfollowText";
      } else {
        var updateHtml = "Unfollow <b>&minus;</b>"
        var updateClass = "unfollow-"
		                  +irelations.relationUserId
						  +" unfollowText";
        var newHtml = "&minus; Unfollow";
        var newClass = "unfollow-"
		               +irelations.relationUserId
					   +" relationButton";
      }
    } else if(irelations.curButtonAction == 'unfollow'){
      //url = __LOCATION__ + '/ajax_unfollow_user.php?user='+curUser+'&type='+relationType;
	  url = __LOCATION__ + '/ajax/ajax_user_unfollow.php';
      if(!small){
        var newHtml = "Follow";
        var newClass = "follow-"
		               +irelations.relationUserId
					   +" followText";
      } else {
        var updateHtml = "Follow <b>&#43;</b>";
        var updateClass = "follow-"
		                  +irelations.relationUserId
						  +" followText";
        var newHtml = "&#43; Follow";
        var newClass = "follow-"
		               +irelations.relationUserId
					   +" relationButton";
      }
    } else {
		return false;
	}
    
    $.ajax({
      beforeSend: function(){
        if(!small){
		  irelations
		    .curButton
			.html("");
          //$button.parent("div").prepend("<img class='relationGear' src='https://www.budvibes.com/images/geargray.gif' />");
          //$button.html("");
        } else {
		  irelations
		    .curButton
			.css("color","#ddd");
          //$button.parent("div").prepend("<img class='relationGearSmall' src='https://www.budvibes.com/images/small-gear.gif' />");
          //$button.css("color", "#dddddd");
        }
      },
      type: 'POST',
      url: url,
	  data: {user_id: irelations.relationUserId,
	        relation_type: irelations.relationType},
      success: function(result){
		if(result){
			$result = $.parseJSON(result);
			iStatus = $result.code;
			irelations
			  .curButton
			  .html(newHtml)
			  .attr("class", newClass)
			  .css("color", "#000");
			switch(iStatus){
				case 401:
					$(".chatBoxWrap").remove();
					$("body").append(doSignUpBox());
				break;
				case 500:
				case 201:
					//DO NOTHING
				break;
				default:
					irelations
					  .curButton
					  .html(newHtml)
					  .attr("class",newClass);
				break;
			}
		}
		/*
        if(result == 1){
          $button.html(newhtml).attr("class", newClass);
          $button.css("color", "#000000");
          $("img.relationGear, img.relationGearSmall").remove();
          if((pageRelation == relationType) && (curUser == curPageId)){
            $("span#relationLink").html(updatehtml).attr("class", updateClass);
          }
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
          $("img.relationGear, img.relationGearSmall").remove();
		  $button.html(buttonHtml).css("color","#000000");
		  $(".chatClose").click(function(){
				$(this).parent("div").parent("div").remove();
			})
        } else {
          $("img.relationGear, img.relationGearSmall").remove();
        }
		*/
      }
    })
    
  })
});