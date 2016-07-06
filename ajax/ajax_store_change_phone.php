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
	
	$storeId = (int)$_SESSION['store_id'];
	$phone = trim($_POST['phone']);
	
	if(!empty($phone)){
		$checkPhone = $Helper->checkPhoneNumber($phone);
		if(!$checkPhone){
			$error['code'] = 500;
			$error['status'] = 'Invalid phone number';
			echo json_encode($error);
			exit();
		} else {
			$updatePhone = $StoresCtrl->updatePhone($storeId,$phone);
			if($updatePhone){
				$done['code'] = 200;
				$done['status'] = 'Phone number successfully updated';
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
		$error['status'] = 'Phone number is blank';
		echo json_encode($error);
		exit();
	}