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
	$oldPass = trim(@$_POST['old_pass']); //strip_tags ??
	$newPass = trim(@$_POST['new_pass']); //strip_tags ??
	$confirmPass = trim(@$_POST['confirm']); //strip_tags ??
	
	$passCheck = $UsersCtrl->validatePassword($newPass,$confirmPass);
	$validPassword = $passCheck['success'];
	
	if($validPassword){
		$updatePassword = $UsersCtrl->updatePassword($userId,
		                                             $newPass,
													 $oldPass);
		if($updatePassword){
			$success['code'] = 200;
			$success['status'] = "Password successfully changed";
			echo json_encode($success);
			exit();
		} else {
			//ERROR
			$error['code'] = 500;
			$error['status'] = 'Invalid password supplied';
			echo json_encode($error);
			exit();
		}
	} else {
		$error['code'] = 401;
		$error['status'] = "Passwords do not match";
		echo json_encode($error);
		exit();
	}