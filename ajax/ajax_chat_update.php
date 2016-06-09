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
	$chatWithId = (int)$_POST['user_id'];
	$parent = (int)$_POST['parent'];
	
	if((!empty($chatWithId) && is_numeric($chatWithId)) && 
	   (!empty($parent) && is_numeric($parent)) ){
		$chatThread = $ChatsCtrl->checkForNewMsg($chatWithId);
		if($chatThread){
			$ChatsCtrl->markChatToRead($sessionId,$parent);
			$success['messages'] = $Helper->formatMessages($chatThread,$sessionId);
			$success['code'] = 200;
		} else {
			$success['code'] = 500;
			$success['messages'] = 0;
		}
		echo json_encode($success);
		exit();
	} else {
		$error['status'] = 'No user chat';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	
	