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
	
	$user = $_POST['user'];
	$userSlug = $Helper->createUrl($user);
	
	$curUser = $UsersCtrl->findUserBySlug($userSlug);
	if($curUser){
		$curUserId = $curUser['id'];
		$relationStatus = $UsersCtrl->checkUserRelation($curUserId);
		$curPics = $UsersCtrl->getRecentUserPics($curUserId,4);
		echo $Views->generateToolTip($curUser,$curPics,$relationStatus);
		exit();
	}
	