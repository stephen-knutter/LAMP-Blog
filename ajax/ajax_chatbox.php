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
	
	$sessionId = $_SESSION['logged_in_id'];
	$chatWithId = $_POST['user'];
	
	if($chatWithId){
		if($chatWithId == $sessionId){
			$error['status'] = 'Invalid user';
		    $error['code'] = 500;
		    echo json_encode($error);
		    exit();
		}
		$chatThread = $ChatsCtrl->checkChatThread($sessionId,$chatWithId);
		if($chatThread){
			$parent = $chatThread['id'];
			$getChat = true;
		} else {
			$parent = 0;
			$getChat = false;
		}
		$chatWithUser = $ChatsCtrl->getUserById($chatWithId);
		if($chatWithUser){
			$success = array();
			$chatWithUsername = $chatWithUser['username'];
		    $ChatsCtrl->markChatToRead($sessionId,$parent);
			$success['chat_with_id'] = $chatWithId;
			$success['chat_with_username'] = $chatWithUsername;
			$success['chat_parent'] = $parent;
			if($getChat){
				$chatThread = $ChatsCtrl->getChatThread($parent);
				if($chatThread){
					$success['messages'] = $Helper->formatMessages($chatThread,$sessionId);
					$success['code'] = 200;
				} else {
					$success['code'] = 500;
					$success['messages'] = 0;
				}
			}
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
	