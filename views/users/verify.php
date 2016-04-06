<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	
	$UsersCtrl = new UsersCtrl;
	$Helper = new ApplicationHelper;
	
	$regToken = $_GET['link'];
	$user = $_GET['user'];
	
	if(!empty($regToken) && !empty($user)){
		$tokenArray = $this->Helper->unobfuscateLink($regToken);
		$regToken = $tokenArray[1];
		$this->UsersCtrl->verifyRegToken($user,$regToken);
	} else {
		header('Location: ' . __LOCATION__);
	}