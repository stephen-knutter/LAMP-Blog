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
	$username = trim(@$_POST['username']);
	$slug = $Helper->createUrl($username);
	
	$nameCheck = $UsersCtrl->validateUsername($username);
	$validUsername = $nameCheck['success'];
	if($validUsername){
		$username = htmlentities($username);
		$updateUsername = $UsersCtrl->updateUsername($userId,$username,$slug);
		if($updateUsername){
			$_SESSION['logged_in_user'] = $username;
			$success['username'] = $username;
			$success['slug'] = $slug;
			$success['code'] = 200;
			$success['status'] = 'Username successfully updated';
			echo json_encode($success);
			exit();
		} else {
			$error['status'] = 'Internal error';
			$error['code'] = 500;
			echo json_encode($error);
			exit();
		}
	} else {
		$error['status'] = 'Invalid username';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}