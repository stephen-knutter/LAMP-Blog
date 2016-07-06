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
	$email = trim(strtolower(strip_tags($_POST['email'])));
	
	if(!empty($email)){
		$emailCheck = $StoresCtrl->validateEmail($email);
		$success = $emailCheck['success'];
		if(!$success){
			$error['code'] = 500;
			$error['status'] = $emailCheck['email'];
			echo json_encode($error);
			exit();
		} else {
			$updateEmail = $StoresCtrl->updateEmail($userId,$storeId,$email);
			if($updateEmail){
				$done['code'] = 200;
				$done['status'] = 'Email successfully updated';
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
		$error['status'] = 'Email is blank';
		echo json_encode($error);
		exit();
	}
	
	