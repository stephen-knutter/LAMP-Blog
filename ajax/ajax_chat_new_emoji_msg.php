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
	$msgPic = $_POST['pic'];
	$msgPic = $Helper->getFileFromFilePath($msgPic);
	$msgType = 'me';
	$message = 'NULL';
	
	if(empty($msgPic) || empty($chatWithId) || empty($parent)){
		$error['status'] = 'No message';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	if(is_numeric($parent)){
		$newThreadId = $ChatsCtrl->addNewThreadMsg($parent,
												   $sessionId,
												   $chatWithId,
												   $msgType,
												   $msgPic,
												   $message);
		if($newThreadId){
			$chatThread = $ChatsCtrl->getChatThread($newThreadId);
			if($chatThread){
				$success['messages'] = $Helper->formatMessages($chatThread,$sessionId);
				$success['code'] = 200;
			} else {
				$success['code'] = 500;
				$success['messages'] = 0;
			}
			echo json_encode($success);
			exit();
		}
	}