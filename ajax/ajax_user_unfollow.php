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
	
	$unfollowId = $_POST['user_id'];
	$relationType = $_POST['relation_type'];
	$userId = $_SESSION['logged_in_id'];
	
	$isFollowing = $UsersCtrl->checkUserRelation($unfollowId);
	
	if($isFollowing){
		$unfollowUser = $UsersCtrl->removeUserFollowing($unfollowId,$userId);
	    if($unfollowUser){
		  $success['code'] = 200;
		  $success['status'] = 'Successfully unfollowed';
		  echo json_encode($success);
		  exit();
	    }
	} 
	$done['code'] = 201;
    echo json_encode($done);
    exit();
	
	
	