/*AJAX CHAT*/
$(function(){
	var sending = false;
	var timeoutId = null;
	
	/*TEXT MSG CHAT*/
	$("body").on("keydown", "div.chatBox", function(event){
		var prependPane = $("div.chatBoxBody");
		var iKey = event.keyCode;
		var url = __LOCATION__ + '/ajax/ajax_chat_new_txt_msg.php';
		/*CHAT THREAD*/
		var chatWrapper = $("div.chatBoxWrap");
		var threadWrap = chatWrapper.attr("id");
		var wrapStart = threadWrap.indexOf("-")+1;
		var wrapEnd = threadWrap.length;
		var curThread = threadWrap.slice(wrapStart,wrapEnd);
		/*CHAT USER*/
		var chatUserWrap = $("div.chatBoxHead").find("span.chatName");
		var chatUser = chatUserWrap.attr("id");
		var strStart = chatUser.indexOf("-")+1;
		var strEnd = chatUser.length;
		chatUser = chatUser.slice(strStart,strEnd);
		/*CHAT TEXT*/
		var chatBox = $("div.chatBox");
		var chatVal = chatBox.html();

		if(iKey == 13){
			event.preventDefault();
			if(chatVal != ''){
				chatBox.html("");
				$.ajax({
					type: 'POST',
					data: {message: chatVal, 
						   parent: curThread, 
						   user_id: chatUser,
						   type: 'mt'},
					url: url,
					success: function(result){
						if(result){
							$result = $.parseJSON(result);
							iStatus = $result.code;
							switch(iStatus){
								case 500:
									//DO NOTHING
								break;
								case 401:
									$("body").append(doSignUpBox());
								break;
								default: 
									prependPane.prepend(doChatMsg($result.messages));
								break;
							}
						}
					}
				})
			}
		}
	});

	/*CHAT MSG EMOJI*/
	$("body").on("click", "div.chatEmoWrap", function(){
		$button = $(this);
		var appendBody = $("div.chatBoxBody");
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
				},
				url: __LOCATION__ + '/ajax/ajax_chat_emoji_list.php',
				success: function(result){
					if(result){
						$result = $.parseJSON(result);
						iStatus = $result.code;
						switch(iStatus){
							case 401:
							case 500:
								//DO NOTHING
							break;
							default:
								$("body").append(doEmojiList($result.emojis));
							break;
						}
					}
				},
				complete: function(){
					sending = false;
				}
			})
		}
	});
	
	$("body").on("click", "div.emojiIconWrap", function(){
		$wrap = $(this);
		var chatWrapper = $("div.chatBoxWrap");
		
		/*CHAT THREAD*/
		var prependPane = $("div.chatBoxBody");
		var threadWrap = chatWrapper.attr("id");
		var wrapStart = threadWrap.indexOf("-")+1;
		var wrapEnd = threadWrap.length;
		var curThread = threadWrap.slice(wrapStart,wrapEnd);
		/*CHAT USER*/
		var chatUserWrap = prependPane.siblings("div.chatBoxHead").find("span.chatName");
		var chatUser = chatUserWrap.attr("id");
		var strStart = chatUser.indexOf("-")+1;
		var strEnd = chatUser.length;
		chatUser = chatUser.slice(strStart,strEnd);
		
		var imgWrap = $wrap.find("div.emojiIconPic").find("img");
		var emojiImg = imgWrap.attr("src");
		$wrap.parent("div").parent("div.emojis").remove();
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
				},
				type: 'POST',
				data: {pic: emojiImg, 
					   parent: curThread, 
					   user_id: chatUser,
					   type: 'me'},
				url: __LOCATION__ + '/ajax/ajax_chat_new_emoji_msg.php',
				success: function(result){
					console.log(result);
					if(result){
						$result = $.parseJSON(result);
						iStatus = $result.code;
						switch(iStatus){
							case 401:
							case 500:
								//DO NOTHING
							break;
							default:
								prependPane.prepend(doChatMsg($result.messages));
							break;
						}
					}
				},
				complete: function(){
					sending = false;
				}
			})
		}
	})
	
	/*CHAT MSG PIC*/
	$("body").on("change", "input.chatCamFile", function(){
		$file = $(this);
		var iform = $file.parent("form");
		var fileVal = $file.val();
		if(fileVal != ''){
			iform.submit();
		}
	});
	$("body").on("submit", "form#chatPic", function(event){
		if(!event){
			event = window.event
		}
		event.preventDefault();
		$form = $(this);
		var prependPane = $("div.chatBoxBody");
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
				},
				type: 'POST',
				url: $form.attr("action"), 
				data: new FormData(this), 
				contentType: false,
				cache: false,
				processData: false,
				success: function(result){
					console.log(result);
					if(result){
						$result = $.parseJSON(result);
						iStatus = $result.code;
						switch(iStatus){
							case 401:
							case 500:
								//DO NOTHING
							break;
							default:
								prependPane.prepend(doChatMsg($result.messages));
							break;
						}
					}
				},
				complete: function(){
					sending = false;
				}
			})
		}
	});
	
	/*HEADER LIST AND MSG BUTTON CLICK HANDLERS*/
	$("body").on("click", "div.headMsgWrap, div.message-user", function(){
		$button = $(this);
		var chatWithId = $button.data("chat");
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
					$("div.inboxCount").remove();
				},
				type: 'POST',
				url: __LOCATION__+'/ajax/ajax_chatbox.php',
				data: {user: chatWithId},
				success: function(result){
					console.log(result);
					if(result){
						$result = $.parseJSON(result);
						iStatus = $result.code;
						switch(iStatus){
							case 500:
								//DO NOTHING
							break;
							case 401:
								$("body").append(doSignUpBox());
							break;
							default:
								$("div.chatBoxWrap").remove();
								$parent = $result.chat_parent;
								$chatWithId = $result.chat_with_id;
								$chatWithUsername = $result.chat_with_username;
								$chatMessages = $result.messages;
								$("body").append(doChatBox($parent,$chatWithId,$chatWithUsername,$chatMessages));
								if(timeoutId){
									clearInterval(timeoutId);
								}
								timeoutId = setInterval(function(){
									//UPDATE CHAT
									var appendBox = $("div.chatBoxBody");
									//USER INFO
								    var chatUserWrap = $("span.chatName");
								    var chatUser = chatUserWrap.attr("id");
									var chatUserId = chatUser.split("-")[1];
									//CHAT THREAD INFO
									var chatParentWrap = $("div.chatBoxWrap");
									var chatParent = chatParentWrap.attr("id");
									var chatParentId = chatParent.split("-")[1];
								    $.ajax({
									   url: __LOCATION__ + '/ajax/ajax_chat_update.php',
									   type: 'POST',
									   data: {user_id: chatUserId, parent: chatParentId},
									   success: function(result){
										 console.log(result);
										 if(result){
											 $result = $.parseJSON(result);
											 iStatus = $result.code;
											 switch(iStatus){
												 case 401:
												 case 500:
													//DO NOTHING
												 break;
												 default:
													appendBox.prepend(doChatMsg($result.messages));
												 break;
											 }
										 }
									   }
								    });
								},5000);
							break;
						}
					}
				},
				complete: function(){
					sending = false;
				}
			})
		}
	})
	
	/*CLOSE BUTTONS*/
	$("body").on("click", "span.closex", function(){
		$button = $(this);
		$button.parent("div").parent("div").remove();
	})
	$("body").on("click", "span.chatClose", function(){
		$button = $(this);
		$button.parent("div").parent("div").remove();
		$("div.emojis").remove();
		if(timeoutId){
			clearInterval(timeoutId);
		}
	})
});

