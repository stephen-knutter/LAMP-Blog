<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	
	$UsersCtrl = new UsersCtrl;
	$Helper = new ApplicationHelper;
	
	$regToken = $_GET['link'];
	$user = $_GET['user'];
	
	if(!empty($regToken) && !empty($user)){
		$tokenArray = $Helper->unobfuscateLink($regToken);
		$regToken = $tokenArray[1];
		$UsersCtrl->verifyRegToken($user,$regToken);
	} else {
		header('Location: ' . __LOCATION__);
		exit();
	}