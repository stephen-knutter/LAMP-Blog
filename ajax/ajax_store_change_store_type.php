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
	$type = trim($_POST['type']);
	
	if(!empty($type)){
		if($type == 'Recreational'){
			$type = 'rec';
			$pic = 'rec_icon_40.png';
		} else {
			$type = 'med';
			$pic = 'med_icon_40.png';
		}
		$updateType = $StoresCtrl->updateStoreType($storeId,$type,$pic);
		if($updateType){
			$done['code'] = 200;
			$done['status'] = 'Type successfully changed';
			echo json_encode($done);
			exit();
		} else {
			$error['code'] = 501;
			$error['status'] = 'Internal error';
			echo json_encode($error);
			exit();
		}
	} else {
		$error['code'] = 500;
		$error['status'] = 'Type is blank';
		echo json_encode($error);
		exit();
	}