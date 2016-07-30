<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/users_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';

	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;

	$userId = (int)$_POST['user'];
	$offset = (int)$_POST['start'];

	$videos = $UsersCtrl->generateUserVideos($userId,$offset);
	if($videos){
		echo $videos;
		exit();
	} else {
	  $done['code'] = 201;
		echo json_encode($done);
		exit();
	}
