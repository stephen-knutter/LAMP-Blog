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
	
	$userId = $_POST['user_id'];
	$thread = $_POST['thread'];
	$message = $_POST['message'];
	
	if(empty($message)){
		$error['status'] = 'No message';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	$message = $Helper->sanitizeInput($message);
	
	