<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/users_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';

	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;

	$userId = (int)$_POST['user'];
	$offset = (int)$_POST['start'];

	$photos = $UsersCtrl->generateUserPhotos($userId,$offset);
	if($photos){
	    echo $photos;
			exit();
	} else {
	    $done['code'] = 201;
			echo json_encode($done);
			exit();
	}
