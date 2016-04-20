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

function bigX(){
	return "<i class='bigx fa fa-times fa-3x' ></i>";
}

function addVideo(source,timeStamp){
	return '<video id="video-preview'+timeStamp+'" style="margin: 0 auto; position: relative; display: block;" class="video-preview'+timeStamp+' video-js vjs-default-skin" controls preload="auto" width="516" height="516">'+
                 '<source src='+source+'>'+
                 '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'+
            '</video>';
}