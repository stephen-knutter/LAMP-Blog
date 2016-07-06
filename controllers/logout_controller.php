<?php
	require dirname(__DIR__) . '/bv_inc.php';
	session_start();
	$_SESSION = array();
	session_destroy();
	
	setcookie('logged_in_id', '', time() - 3600);
	setcookie('logged_in_user', '', time() - 3600);
	setcookie('user_verified', '', time()  - 3600);
	setcookie('logged_in_photo', '', time() - 3600);
	setcookie('store', '', time() - 3600);
	setcookie('store_id', '', time() - 3600);
	setcookie('store_reg', '', time() - 3600);
	setcookie('store_state', '', time() - 3600);
	
	$url = __LOCATION__;
	header('Location: ' . $url);
	exit();
