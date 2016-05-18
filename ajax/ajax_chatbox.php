<?php 
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/users_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$sessionId = $_SESSION['logged_in_id'];
	$chatWithId = $_POST['user'];
	
	if($chatWithId){
		if($chatWithId == $sessionId){
			$error['status'] = 'Invalid user';
		    $error['code'] = 500;
		    echo json_encode($error);
		    exit();
		}
		$chatThread = $UsersCtrl->checkChatThread($sessionId,$chatWithId);
		if($chatThread){
			$parent = $chatThread['id'];
			$getChat = true;
		} else {
			$parent = 0;
			$getChat = false;
		}
		$chatWithUser = $UsersCtrl->getUserById($chatWithId);
		if($chatWithUser){
			$success = array();
			$chatWithUsername = $chatWithUser['username'];
		    $UsersCtrl->markChatToRead($sessionId,$parent);
			$success['chat_with_id'] = $chatWithId;
			$success['chat_with_username'] = $chatWithUsername;
			$success['chat_parent'] = $parent;
			if($getChat){
				$chatThread = $UsersCtrl->getChatThread($parent);
				if($chatThread){
					$i=0;
					foreach($chatThread as $chat){
						$threadProfilePic = $chat['profile_pic'];
						$threadChatId = $chat['user_id'];
						$threadDate = $chat['created_at'];
						$threadMsgType = $chat['message_type'];
						$threadMsg = $chat['comm_text'];
						$threadMsgPic = $chat['pic'];
						$chatDate = strtotime($threadDate);
						$chatDate = date("M d, Y",$chatDate);
						if($threadProfilePic == 'no-profile.png'){
							$thumbLink = __LOCATION__ . '/assets/images/thumb-no-profile.png';
						} else {
							$thumbLink = __LOCATION__ . '/assets/user-images/'.$threadChatId.'/thumbsmall-'.$threadProfilePic;
						}
						if($sessionId == $threadChatId){
							$thumbClass = 'chatMsgThumbRight';
							$bodyClass = 'chatMsgBodyRight';
						} else {
							$thumbClass = 'chatMsgThumbLeft';
							$bodyClass = 'chatMsgBodyLeft';
						}
						if($threadMsgType == 'me' ){
							$imgClass = 'emojipost';
						} elseif($threadMsgType == 'mp'){
							$imgClass = 'picpost';
						} else {
							$imgClass = '';
						}
						$success['messages'][$i]['profile_pic'] = $threadProfilePic;
						$success['messages'][$i]['user_id'] = $threadChatId;
						$success['messages'][$i]['date'] = $chatDate;
						$success['messages'][$i]['msg_type'] = $threadMsgType;
						$success['messages'][$i]['msg_text'] = $threadMsg;
						$success['messages'][$i]['pic'] = $threadMsgPic;
						$success['messages'][$i]['thumb'] = $thumbLink;
						$success['messages'][$i]['thumb_class'] = $thumbClass;
						$success['messages'][$i]['body_class'] = $bodyClass;
						$success['messages'][$i]['image_class'] = $imgClass;
						$i++;
					}
				}
			}
			$success['code'] = 200;
			echo json_encode($success);
			exit();
		} else {
			$error['status'] = 'Invalid user';
		    $error['code'] = 500;
		    echo json_encode($error);
		    exit();
		}
	} else {
		$error['status'] = 'Invalid user';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	