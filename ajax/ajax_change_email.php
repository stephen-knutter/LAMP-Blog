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
	
	$userId = $_SESSION['logged_in_id'];
	$email = trim(@$_POST['email']);
	$email = strip_tags($email);
	$email = strtolower($email);
	
	$emailCheck = $UsersCtrl->validateEmail($email);
	$validEmail = $emailCheck['success'];
	
	if($validEmail){
		$updateEmail = $UsersCtrl->updateEmail($userId,$email);
		if($updateEmail){
			$success['code'] = 200;
			$success['status'] = "Email successfully changed";
			$success['email'] = $email;
			echo json_encode($success);
			exit();
		}
	} else {
		$error['code'] = 401;
		$error['status'] = 'Invalid email';
		echo json_encode($error);
		exit();
	}