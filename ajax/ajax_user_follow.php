<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/users_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$followId = $_POST['user_id'];
	$relationType = $_POST['relation_type'];
	$userId = $_SESSION['logged_in_id'];
	
	$isFollowing = $UsersCtrl->checkUserRelation($followId);
	
	if(!$isFollowing){
		$followUser = $UsersCtrl->addUserFollowing($followId,$userId);
	    if($followUser){
		  $success['code'] = 200;
		  $success['status'] = 'Now following';
		  echo json_encode($success);
		  exit();
	    }
	} 
	$done['code'] = 201;
    echo json_encode($done);
    exit();
	
	
	
	
	
	