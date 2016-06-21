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
	$website = trim(strip_tags(strtolower($_POST['website'])));
	$website = 'http://'.$website;
	
	if(!empty($website)){
		$websiteCheck = $Helper->checkWebsite($website);
		if(!$websiteCheck){
			$error['code'] = 500;
			$error['status'] = 'Not a valid website';
			echo json_encode($error);
			exit();
		} else {
			$updateWebsite = $StoresCtrl->updateWebsite($storeId,$website);
			if($updateWebsite){
				$done['code'] = 200;
				$done['status'] = 'Website successfully changed';
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
		$error['status'] ='Website is blank';
		echo json_encode($error);
		exit();
	}