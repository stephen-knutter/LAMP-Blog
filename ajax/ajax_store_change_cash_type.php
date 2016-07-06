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
	$cash = trim($_POST['cash']);
	
	if(!empty($cash)){
		if($cash == 'Debit'){
			$cash = 'd';
		} else {
			$cash = 'a';
		}
		$updateCash = $StoresCtrl->updateCashType($storeId,$cash);
		if($updateCash){
			$done['code'] = 200;
			$done['status'] = 'Cash type successfully updated';
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
		$error['status'] = 'Cash type is blank';
		echo json_encode($error);
		exit();
	}