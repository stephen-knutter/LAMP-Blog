<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/stores_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$StoresCtrl = new StoresCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$userId = (int)$_SESSION['logged_in_id'];
	$storeId = (int)$_SESSION['store_id'];
	$username = trim($_POST['username']);
	
	if(!empty($username)){
		$usernameCheck = $StoresCtrl->validateUsername($username);
		$success = $usernameCheck['success'];
		if(!$success){
			$error['code'] = 500;
			$error['status'] = $usernameCheck['username'];
			echo json_encode($error);
			exit();
		} else {
			$slug = $Helper->createUrl($username);
			$updateUsername = $StoresCtrl->updateUsername($username,$slug,$userId,$storeId);
			if($updateUsername){
				$done['code'] = 200;
				$done['status'] = 'Username successfully updated';
				echo json_encode($done);
				exit();
			} else {
				$error['code'] = 501;
				$error['status'] = 'Internal error';
				echo json_encode($error);
				exit();
			}
		}
	} else {
		$error['code'] = 500;
		$error['status'] = 'One or more items blank';
		echo json_encode($error);
		exit();
	}