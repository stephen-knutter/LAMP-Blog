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
	$prodId = (int)$_POST['prod_id'];
	$menuId = (int)$_POST['menu_id'];
	$prodType = trim(strtolower($_POST['prod_type']));
	$itemName = trim(strip_tags($_POST['item_name']));
	$itemName = $Helper->sanitizeInput($itemName);
	$itemName = preg_replace('/[^a-zA-Z0-9]/', ' ', $itemName);
	$menuType = trim(strtolower($_POST['menu_type']));

	if(!empty($itemName) && 
	   !empty($menuType) && 
	   !empty($prodType) && 
	   !empty($menuId)){
		switch($prodType){
			case 'indica':
			case 'sativa':
			case 'hybrid':
				$g = $_POST['gram_doll'];
				$e = $_POST['eigth_doll'];
				$f = $_POST['fourth_doll'];
				$h = $_POST['half_doll'];
				$o = $_POST['ounce_doll'];
				if(!empty($g)&&
				   !empty($e)&&
				   !empty($f)&&
				   !empty($h)&&
				   !empty($o)
				  ){
					  $updateItem = $StoresCtrl->updateFlwrItem($menuId,
																$itemName,
																$menuType,
																$g,$e,$f,$h,$o);
					  if($updateItem){
						  $success['status'] = 'Item successfully updated';
						  $success['code'] = 200;
						  echo json_encode($success);
						  exit();
					  } else {
						  $error['status'] = 'Item could not be updated';
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
			break;
			case 'wax':
				$h = $_POST['half_doll'];
				$g = $_POST['gram_doll'];
				if(!empty($h) && !empty($g)){
					$updateItem = $StoresCtrl->updateWaxItem($menuId,
															 $itemName,
															 $menuType,
															 $g,$h);
					if($updateItem){
						$success['status'] = 'Item successfully updated';
						$success['code'] = 200;
						echo json_encode($success);
						exit();
					} else {
						$error['status'] = 'Item could not be updated';
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
			break;
			case 'edible':
			case 'drink':
			case 'tincture':
			case 'ointment':
			case 'other':
				$e = $_POST['each_doll'];
				if(!empty($e)){
					$updateItem = $StoresCtrl->updateSingleItem($menuId,
															    $itemName,
															    $menuType,
															    $e);
					if($updateItem){
						$success['status'] = 'Item successfully updated';
						$success['code'] = 200;
						echo json_encode($success);
						exit();
					} else {
						$error['status'] = 'One or more items blank';
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
			break;
		}
	}
	