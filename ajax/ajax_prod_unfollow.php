<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/products_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';

  $ProdCtrl = new ProductsCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;

  if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}

  $unfollowId = $_POST['user_id'];
  $relationType = $_POST['relation_type'];
  $userId = $_SESSION['logged_in_id'];

  $isFollowing = $ProdCtrl->checkUserRelation($unfollowId);

  if($isFollowing){
    $unfollowProduct = $ProdCtrl->removeProductFollowing($unfollowId,$userId);
    if($unfollowProduct){
      $success['code'] = 200;
      $success['status'] = 'Now following';
      echo json_encode($success);
      exit();
    }
  }
  $done['code'] = 201;
  echo json_encode($done);
  exit();
