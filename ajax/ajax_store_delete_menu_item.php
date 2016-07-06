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
	
	$sessionId = (int)$_SESSION['logged_in_id'];
	$storeId = (int)$_SESSION['store_id'];
	$menuId = (int)$_POST['menu_id'];
	
	
	if(!empty($menuId)){
		$menuCheck = $StoresCtrl->checkCorrectMenuItem($menuId,$storeId);
		if($menuCheck){
			$deleteItem = $StoresCtrl->deleteMenuItem($menuId);
			if($deleteItem){
				$success['status'] = 'Item successfully deleted';
				$success['code'] = 200;
				echo json_encode($success);
				exit();
			} else {
				$error['status'] = 'Item could not be deleted';
				$error['code'] = 500;
				echo json_encode($error);
				exit();
			}
		} else {
			$error['status'] = 'Item could not be deleted';
			$error['code'] = 500;
			echo json_encode($error);
			exit();
		}
	} else {
		$error['status'] = 'One or more items blank';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}