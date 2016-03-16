/*FOLLOW / UNFOLLOW*/
$(function(){
  $("body").on("click", "span#relationLink, span.relationButton", function(){
    $button = $(this);
    var relationType = $button.parent("div").attr("id");
    if(relationType.indexOf('-') > 0){
      var dshStart = relationType.indexOf("-");
      relationType = relationType.slice(0,dshStart);
      var small = true;
    } else {
	  /*BIG BUTTON TYPE*/
      var curPage = $("span#relationLink").attr("class").split(' ')[0]; //PAGE TYPE
      var pageStart = curPage.indexOf("-")+1;
      var pageEnd = curPage.length;
      var curPageId = curPage.slice(pageStart,pageEnd); //PAGE ID
      var pageRelation = $("span#relationLink").parent("div").attr("id");
      var small = false;
    }
    var buttonHtml = $button.text();
    var curClass = $button.attr("class");
    curClass = curClass.split(' ')[0];
    var strSplit = curClass.indexOf("-");
    var strEnd = curClass.length;
    var curAction = curClass.slice(0,strSplit);//PAGE TYPE
    var curUser = curClass.slice(strSplit+1,strEnd);//USER ID
    if(curAction == 'follow'){
      url = 'https://www.budvibes.com/follow.php?user='+curUser+'&type='+relationType;
      if(!small){
        var newhtml = "Unfollow</b>"
        var newClass = "unfollow-"+curUser+" unfollowText";
      } else {
        var updatehtml = "Unfollow <b>&minus;</b>"
        var updateClass = "unfollow-"+curUser+" unfollowText";
        var newhtml = "&minus; Unfollow";
        var newClass = "unfollow-"+curUser+" relationButton";
      }
    } else {
      url = 'https://www.budvibes.com/unfollow.php?user='+curUser+'&type='+relationType;
      if(!small){
        var newhtml = "Follow</b>";
        var newClass = "follow-"+curUser+" followText";
      } else {
        var updatehtml = "Follow";
        var updateClass = "follow-"+curUser+" followText";
        var newhtml = "&#43; Follow";
        var newClass = "follow-"+curUser+" relationButton";
      }
    }
    
    $.ajax({
      beforeSend: function(){
        if(!small){
          $button.parent("div").prepend("<img class='relationGear' src='https://www.budvibes.com/images/geargray.gif' />");
          $button.html("");
        } else {
          $button.parent("div").prepend("<img class='relationGearSmall' src='https://www.budvibes.com/images/small-gear.gif' />");
          $button.css("color", "#dddddd");
        }
      },
      type: 'GET',
      url: url,
      success: function(result){
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
      }
    })
    
  })
});