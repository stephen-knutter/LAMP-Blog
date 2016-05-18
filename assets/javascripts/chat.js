/*AJAX CHAT*/
$(function(){
	var sending = false;
	var timeoutId = null;
	
	/*TEXT MSG CHAT*/
	$("body").on("keydown", "div.chatBox", function(event){
		var prependPane = $("div.chatBoxBody");
		var iKey = event.keyCode;
		var url = __LOCATION__ . '/ajax/ajax_chat_new_txt_msg.php';
		/*CHAT THREAD*/
		var chatWrapper = $("div.chatBoxWrap");
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
		/*CHAT TEXT*/
		var chatBox = prependPane.siblings("div.chatBoxPostWrap").find("div.chatReplyBoxWrap").find("div.chatBox");
		var chatVal = chatBox.html();

		if(iKey == 13){
			event.preventDefault();
			if(chatVal != ''){
				chatBox.html("");
				$.ajax({
					type: 'POST',
					data: {message: chatVal, 
						   emoji: 'NULL', 
						   pic: 'NULL', 
						   thread: curThread, 
						   user_id: chatUser},
					url: url,
					success: function(result){
						if(result){
							$result = $.parseJSON(result);
							iStatus = $result.code;
							switch(iStatus){
								case 500:
									//DO NOTHING
								break;
								case: 401:
									$("body").append(doSignUpBox());
								break;
								default: 
									prependPane.prepend(doChatMsg($result.messages));
								break;
							}
						}
						if(result == 0){
							//FALL THROUGH
						} else {
							prependPane.prepend(result);
						}
					}
				})
			}
		}
		
	});

	/*CHAT MSG EMOJI*/
	$("body").on("click", "div.chatEmoWrap", function(){
		$button = $(this);
		var appendBody = $button.parent("div").siblings("div.chatBoxBody");
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
				},
				url: 'https://www.budvibes.com/getemoji.php',
				success: function(result){
					$("body").append(result);
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
		var prependPane = chatWrapper.find("div.chatBoxBody");
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
				data: {message: 'NULL', emoji: emojiImg, pic: 'NULL', thread: curThread, user: chatUser},
				url: 'https://www.budvibes.com/add-message.php',
				success: function(result){
					if(result == 0){
						//alert(result)
					} else {
						prependPane.prepend(result);
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
		event.preventDefault();
		$form = $(this);
		var prependPane = $form.parent("div").parent("div").siblings("div.chatBoxBody");
		if(!sending){
			$.ajax({
				beforeSend: function(){
					sending = true;
				},
				type: 'POST',
				url: 'https://www.budvibes.com/add-message.php',
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function(result){
					//alert(result)
					if(result == 0){
						//alert(result)
					} else {
						prependPane.prepend(result);
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
								/*
								if(timeoutId){
									clearInterval(timeoutId);
								}
								timeoutId = setInterval(function(){
									//UPDATE CHAT
								},5000);
								*/
							break;
						}
					}
					/*
					if(result == 0){
						//FALL THROUGH
					} else {
						$("div.chatBoxWrap").remove();
						$("body").append(result);
						if(timeoutId){
							clearInterval(timeoutId);
						}
							timeoutId = setInterval(function(){
								var appendBox = $("div.chatBoxBody");
								var chatUserWrap = $("span.chatName");
								var chatUserId = chatUserWrap.attr("id");
								var strStart = chatUserId.indexOf("-")+1;
								var strEnd = chatUserId.length;
								var chatUser = chatUserId.slice(strStart,strEnd);
								$.ajax({
									url: 'https://www.budvibes.com/chat-update.php',
									type: 'POST',
									data: {user: chatUser},
									success: function(result){
										if(result == 0){
											//FALL THROUGH
										} else {
											appendBox.prepend(result);
										}
									}
								})
							},5000)
					}
					*/
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

