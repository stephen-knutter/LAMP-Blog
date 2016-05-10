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
	
	$userId = (int)$_POST['user'];
	$offset = (int)$_POST['start'];
	$type = $_POST['type'];
	$word = $_POST['word'];
	
	switch($type){
		case 'feed':
			$feed = $UsersCtrl->generateFeed('ajax-feed',$userId,$offset);
			if($feed){
				echo $feed;
				exit();
			} else {
				$done['code'] = 201;
				echo json_encode($done);
				exit();
			}
		break;
		case 'posts':
			$posts = $UsersCtrl->generateFeed('ajax-posts',$userId,$offset);		
			if($posts){
				echo $posts;
				exit();
			} else {
				$done['code'] = 201;
				echo json_encode($done);
				exit();
			}
		break;
		case 'strains':
		break;
		case 'search':
		break;
		case 'forums':
		break;
		case 'front':
		break;
	}
	
	
	
	
	
	
	
	
	
	