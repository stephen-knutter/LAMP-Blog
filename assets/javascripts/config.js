var __LOCATION__ = 'http://localhost/bv_mvc/LAMP-Blog';

function doSignUpBox(){
	return '<div class="chatBoxWrap" id="thread-no">'+
                '<div class="chatBoxHead clearfix"><span class="chatName" id="chat-none">Sign Up</span><span class="chatClose">X</span></div>'+
                '<div class="chatBoxBody">'+
                  '<div id="signInMenu" class="signUpMenu">'+
                    '<h3><img src="'+__LOCATION__+'/assets/images/sign-up-head.png" alt="User Sign Up"></h3>'+
                    '<form action="'+__LOCATION__+'/login" method="post" id="signUpForm">'+
                      '<input type="text" class="signInput" name="username" placeholder="Username">'+
                      '<input type="text" class="signInput" name="email" placeholder="Email">'+
                      '<input type="password" class="signInput" name="pass" placeholder="Password">'+
                      '<input type="password" class="signInput" name="confirmpass" placeholder="Confirm Password">'+
                      '<input type="submit" class="signSubmit" name="sign-in" value="Sign Up">'+
                    '</form>'+
                    '<div id="signUp">'+
                      '<a href="'+__LOCATION__+'/login" id="signInLink">&#8592; Sign In</a>'+
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
                      '<img class="chatCamImg" src="'+__LOCATION__+'/assets/images/chat-cam.png" />'+
                    '</form>'+
                  '</div>'+
                  '<div class="chatEmoWrap">'+
                    '<img class="chatEmoImg" src="'+__LOCATION__+'/assets/images/chat-smile.png" />'+
                  '</div>'+
                '</div>'+
              '</div>'		
}

function doChatBox(parent,chatWithId,chatWithUsername,chatMessages){
	if(chatMessages){
		messages = doChatMsg(chatMessages);
	} else {
		messages = '';
	}
	return '<div class="chatBoxWrap" id="thread-'+parent+'">'+
				'<div class="chatBoxHead clearfix">'+
					'<span class="chatName" id="chat-'+chatWithId+'">'+chatWithUsername+'</span>'+
					'<span class="chatClose">X</span>'+
				'</div>'+
				'<div class="chatBody">'+messages+'</div>'+
				'<div class="chatBoxPostWrap">'+
					'<div class="chatReplyBoxWrap clearfix">'+
						'<div class="chatBox" contenteditable="true" placeholder="message"></div>'+
					'</div>'+
				'</div>'+
				'<div class="chatButtons">'+
					'<div class="chatCamWrap">'+
						'<form id="chatPic" action="'+__LOCATION__+'/ajax/ajax_add_message.php" type="post">'+
							'<input type="file" name="pic" class="chatCamFile" />'+
							'<input type="hidden" name="message" value="NULL" />'+
							'<input type="hidden" name="emoji" value="NULL" />'+
							'<input type="hidden" name="thread" value="'+parent+'"/>'+
							'<input type="hidden" name="user" value="'+chatWithId+'"/>'+
							'<img class="chatCamImg" src="'+ __LOCATION__+'/assets/images/chat-cam.png">'+
						'</form>'+
					'</div>'+
					'<div class="chatEmoWrap">'+
						'<img class="chatCamImg" src="'+__LOCATION__ +'/assets/images/chat-smile.png" />'+
					'</div>'+
				'</div>'+
		   '</div>';
}

function doChatMsg(chatMessages){
		oMessage = null;
		iMessages = chatMessages['messages'].length;
		for($i=0;$i < iMessages;$i++){
			curMessage = chatMessages['messages'][$i];
			zProfilePic = curMessage['profile_pic'];
			zUseId = curMessage['user_id'];
			zDate = curMessage['date'];
			zMsgType = curMessage['msg_type'];
			zMsgText = curMessage['msg_text'];
			zChatPic = curMessage['pic'];
			zThumb = curMessage['thumb'];
			zThumbClass = curMessage['thumb_class'];
			zBodyClass = curMessage['body_class'];
			zImageClass = curMessage['image_class'];
			oMessage += '<div class="chatMsgWrap clearfix">'+
				         '<div class="'+zBodyClass+'">';
					      if(zMsgType == 'mt'){
						    oMessage += '<span class="chatMsg">'+zMsgText+'</span>';
					      } else {
						    oMessage += '<img class="'+zImageClass+'" src="'+zChatPic+'" />';
					      }
			oMessage +=	 '</div>'+
				        '<p class="chatDate">'+zDate+'</p>'+
			           '</div>';
		}
		return oMessage;
}

function bigX(){
	return "<i class='bigx fa fa-times fa-3x' ></i>";
}

function addVideo(source,timeStamp){
	return '<video id="video-preview'+timeStamp+'" style="margin: 0 auto; position: relative; display: block;" class="video-preview'+timeStamp+'" video-js vjs-default-skin" controls preload="auto" width="516" height="516">'+
                 '<source src="'+source+'">'+
                 '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
            '</video>';
}

function doVideo(video,photo,timeStamp){
	return "<div class='userPicWrap'>"+
	           "<video style='margin: 0 auto; position: relative; display: block;' id='video-preview"+timeStamp+"' class='video-js vjs-default-skin' controls preload='auto' width='281' height='281' poster='"+photo+"'>"+  
				 "<source src='"+ video+"'>"+
				 "<p class='vjs-no-js'>To view this video please enable JavaScript, and consider upgrading to a web browser that <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a></p>"+
               "</video>"+
               "<div class='photoInfoPane'>"+
                   "<span class='photoReplyCount'>0 Replies</span>"+
               "</div>"+
           "</div>";
}


 
 
                                                 

function doPhoto(source){
	return "<div class='userPicWrap'>"+
                "<img src='"+source+"'>"+
                "<div class='photoInfoPane'>"+
                    "<span class='photoReplyCount'>0 Replies</span>"+
                "</div>"+
           "</div>";
}