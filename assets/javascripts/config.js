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
				'<div class="chatBoxBody">'+messages+'</div>'+
				'<div class="chatBoxPostWrap">'+
					'<div class="chatReplyBoxWrap clearfix">'+
						'<div class="chatBox" contenteditable="true" placeholder="message"></div>'+
					'</div>'+
				'</div>'+
				'<div class="chatButtons">'+
					'<div class="chatCamWrap">'+
						'<form id="chatPic" action="'+__LOCATION__+'/ajax/ajax_chat_new_pic_msg.php" type="post" enctype="multipart/form-data">'+
							'<input type="file" name="pic" class="chatCamFile" />'+
							'<input type="hidden" name="parent" value="'+parent+'"/>'+
							'<input type="hidden" name="user_id" value="'+chatWithId+'"/>'+
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
		oMessage = '';
		var count = chatMessages.length;
		if(!count){
			count = 1;
		}
		for($i=0;$i < count;$i++){
			curMessage = chatMessages[$i];
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

function doEmojiList(emojis){
	oEmojis = '';
	var count = emojis.length;
	oEmojis += '<div class="emojis">'+
					'<div class="emojiClose clearfix">'+
						'<img src="'+__LOCATION__+'/assets/images/add-emoji.png" alt="Add Emoticon">'+
						'<span class="closex">X</span>'+
					'</div>'+
					'<div class="emojiListWrap">';
					for($i=0;$i < count;$i++){
						curEmoji = emojis[$i];
						oEmojis += '<div class="emojiIconWrap clearfix" id="emoji-'+curEmoji['id']+'">'+
										'<div class="emojiIconPic">'+
											'<img src="'+curEmoji['pic_link']+'" alt="'+curEmoji['name']+'">'+
										'</div>'+
										'<div class="emojiText">'+curEmoji['name']+'</div>'+
								   '</div>';
					}
	oEmojis +=     '</div>'+
				'</div>';
	return oEmojis;
}

function bigX(){
	return "<i class='bigx fa fa-times fa-3x' ></i>";
}

function addVideo(source,timeStamp){
	return '<video id="video-preview'+timeStamp+'" style="margin: 0 auto; position: relative; display: block;" class="video-preview'+timeStamp+' video-js vjs-default-skin" controls preload="auto" width="516" height="516">'+
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

function doFlwrMenu(editType){
	return '<input type="hidden" name="prod_type" id="prod_type" value="'+editType+'" />'+
	'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
	'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_gram">G $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox price_eigth">1/8 $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox price_fourth">1/4 $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox price_half">1/2 $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox price_ounce">Oz $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox menuType">med</span><br/>'+
	'<input type="submit" id="addMenuItem" value="Add New" />'
}

function doNewFlwrItem(itemId,storeId,prodId,prodLabel,itemName, usedFor,
					   g,e,f,h,o){
	return '<div class="editItemWrap clearfix">'+
	'<input type="hidden" name="menu_id" class="menu_id" value="'+itemId+'">'+
	'<input type="hidden" name="store_id" class="store_id" value="'+storeId+'" />'+
	'<input type="hidden" name="prod_id" class="prod_id" value="'+prodId+'">'+
	'<input type="hidden" name="prod_label" class="prod_label" value="'+prodLabel+'">'+
	'<input type="text" name="item_name" class="item_name" value="'+itemName+'" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_gram">G $<span class="selectDollar">'+g+'</span></span>'+
	'<span class="priceBox price_eigth">1/8 $<span class="selectDollar">'+e+'</span></span>'+
	'<span class="priceBox price_fourth">1/4 $<span class="selectDollar">'+f+'</span></span>'+
	'<span class="priceBox price_half">1/2 $<span class="selectDollar">'+h+'</span></span>'+
	'<span class="priceBox price_ounce">Oz $<span class="selectDollar">'+o+'</span></span>'+
	'<span class="priceBox menuType">'+usedFor+'</span><br/>'+
	'<input type="submit" name="update" class="updateMenuItem" value="Update" />'+
	'<input type="submit" name="delete" class="deleteMenuItem" value="Delete">'+
	'</div>'
}

function doWaxMenu(editType){
	return '<input type="hidden" name="prod_type" id="prod_type" value="'+editType+'" />'+
	'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
	'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_half">.5g $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox price_gram">G $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox menuType">med</span><br/>'+
	'<input type="submit" id="addMenuItem" value="Add New" />'
}

function doNewWaxItem(itemId,storeId,prodId,prodLabel,itemName, usedFor,
					  g,h){
	return '<div class="editItemWrap clearfix">'+
	'<input type="hidden" name="menu_id" class="menu_id" value="'+itemId+'">'+
	'<input type="hidden" name="store_id" class="store_id" value="'+storeId+'" />'+
	'<input type="hidden" name="prod_id" class="prod_id" value="'+prodId+'">'+
	'<input type="hidden" name="prod_label" class="prod_label" value="'+prodLabel+'">'+
	'<input type="text" name="item_name" class="item_name" value="'+itemName+'" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_half">.5g $<span class="selectDollar">'+h+'</span></span>'+
	'<span class="priceBox price_gram">G $<span class="selectDollar">'+g+'</span></span>'+
	'<span class="priceBox menuType">'+usedFor+'</span><br/>'+
	'<input type="submit" name="update" class="updateMenuItem" value="Update" />'+
	'<input type="submit" name="delete" class="deleteMenuItem" value="Delete">'+
	'</div>'
}

function doSingleMenu(editType){
	return '<input type="hidden" name="prod_type" id="prod_type" value="'+editType+'" />'+
	'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
	'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_each">Each $<span class="selectDollar">00</span></span>'+
	'<span class="priceBox menuType">med</span><br/>'+
	'<input type="submit" id="addMenuItem" value="Add New" />'
}

function doNewSingleItem(itemId,storeId,prodId,prodLabel,itemName, usedFor,
						 e){
	return '<div class="editItemWrap clearfix">'+
	'<input type="hidden" name="menu_id" class="menu_id" value="'+itemId+'">'+
	'<input type="hidden" name="store_id" class="store_id" value="'+storeId+'" />'+
	'<input type="hidden" name="prod_id" class="prod_id" value="'+prodId+'">'+
	'<input type="hidden" name="prod_label" class="prod_label" value="'+prodLabel+'">'+
	'<input type="text" name="item_name" class="item_name" value="'+itemName+'" placeholder="Item Name" autocomplete="off"/><br/>'+
	'<span class="priceBox price_each">Each $<span class="selectDollar">'+e+'</span></span>'+
	'<span class="priceBox menuType">'+usedFor+'</span><br/>'+
	'<input type="submit" name="update" class="updateMenuItem" value="Update" />'+
	'<input type="submit" name="delete" class="deleteMenuItem" value="Delete">'+
	'</div>'
}

function doSpecial(name,desc,photo,date){
	return '<div class="curSpecial">'+
		'<div class="curSpecialHead">'+
			'<span class="specialStoreName">'+name+'</span><span class="specialDescrip">'+desc+'</span>'+
		'</div>'+
		'<div class="curSpecialPic">'+
			'<img class="specialPic" src="'+photo+'">'+
		'</div>'+
		'<div class="specialExp">'+
			'<span class="curexpDate">EXPIRES: '+date+'</span>'+
		'</div>'+
	'</div>'
}