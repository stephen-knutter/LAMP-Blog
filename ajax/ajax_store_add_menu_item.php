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
	$prodType = trim(strtolower($_POST['prod_type']));
	$itemName = trim(strip_tags($_POST['item_name']));
	$itemName = $Helper->sanitizeInput($itemName);
	$itemName = preg_replace('/[^a-zA-Z0-9]/', ' ', $itemName);
	$menuType = trim(strtolower($_POST['menu_type']));
	
	if(!empty($itemName) && !empty($menuType) && !empty($prodType)){
		switch($prodType){
			case 'indica':
			case 'sativa':
			case 'hybrid':
				$g = $_POST['gram_doll'];
				$e = $_POST['eigth_doll'];
				$f = $_POST['fourth_doll'];
				$h = $_POST['half_doll'];
				$o = $_POST['ounce_doll'];
			
				if(!empty($g) &&
				   !empty($e) &&
				   !empty($f) &&
			       !empty($h) &&
			       !empty($o)
			      ){
					$newItemId = $StoresCtrl->addFlwrItems($g,$e,$f,$h,$o,
														   $itemName,
														   $menuType,
														   $prodId,
														   $prodType,
														   $storeId);
						if($newItemId){
							$relationCheck = $StoresCtrl->checkStoreProdRelation($storeId,$prodId);
							if(!$relationCheck && $prodId){
								$StoresCtrl->addStoreProdRelation($storeId,$prodId);
							}
							$newItems = array();
							$newItems['id'] = $newItemId;
							$newItems['name'] = $itemName;
							$newItems['store_id'] = $storeId;
							$newItems['prod_id'] = $prodId;
							$newItems['g'] = $g;
							$newItems['e'] = $e;
							$newItems['f'] = $f;
							$newItems['h'] = $h;
							$newItems['o'] = $o;
							$newItems['prod_label'] = $prodType;
							$newItems['used_for'] = $menuType;
							$newItems['code'] = 200;
							echo json_encode($newItems);
							exit();
						} else {
							$error['status'] = 'Internal error';
							$error['code'] = 501;
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
					$newItemId = $StoresCtrl->addWaxItems($h,$g,
														  $itemName,
														  $menuType,
														  $prodId,
														  $prodType,
														  $storeId);
					if($newItemId){
						$newItems = array();
						$newItems['id'] = $newItemId;
						$newItems['name'] = $itemName;
						$newItems['store_id'] = $storeId;
						$newItems['prod_id'] = $prodId;
						$newItems['g'] = $g;
						$newItems['h'] = $h;
						$newItems['prod_label'] = $prodType;
						$newItems['used_for'] = $menuType;
						$newItems['code'] = 200;
						echo json_encode($newItems);
						exit();
					} else {
						$error['status'] = 'Internal error';
						$error['code'] = 501;
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
					$newItemId = $StoresCtrl->addSingleItems($e,
														     $itemName,
														     $menuType,
														     $prodId,
														     $prodType,
														     $storeId);
					if($newItemId){
						$newItems = array();
						$newItems['id'] = $newItemId;
						$newItems['name'] = $itemName;
						$newItems['store_id'] = $storeId;
						$newItems['prod_id'] = $prodId;
						$newItems['e'] = $e;
						$newItems['prod_label'] = $prodType;
						$newItems['used_for'] = $menuType;
						$newItems['code'] = 200;
						echo json_encode($newItems);
						exit();
					} else {
						$error['status'] = 'Internal error';
						$error['code'] = 501;
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
	} else {
		$error['status'] = 'One or more items blank';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}