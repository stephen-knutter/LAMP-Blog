<?php 
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/chats_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$ChatsCtrl = new ChatsCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$emojiList = $ChatsCtrl->getEmojiList();
	
	if($emojiList){
		$success = array();
		$i=0;
		foreach($emojiList as $emoji){
			$id = $emoji['id'];
			$pic = $emoji['pic'];
			$picLink = __LOCATION__ . '/assets/images/emoji/'.$pic;
			$name = $emoji['name'];
			$success['emojis'][$i]['id'] = $id;
			$success['emojis'][$i]['pic_link'] = $picLink;
			$success['emojis'][$i]['name'] = $name;
			$i++;
		}
		$success['code'] = 200;
		echo json_encode($success);
		exit();
	} else {
		$error['status'] = 'Emoji list not found';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}