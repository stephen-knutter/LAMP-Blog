<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	$bytes = 1048576;
	$maxSize = $bytes * 10;
	$fileType = $_FILES['photo']['type'];
	$fileSize = $_FILES['photo']['size'];
	$winHeight = $_POST['winheight'];
	$userId = $_SESSION['logged_in_id'];
	$userdir = '../assets/user-images/'.$userId.'/';
	set_error_handler("warning_hanlder", E_WARNING);
	function warning_hanlder($errno,$errstr){
		$error['status'] = $errstr;
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	if(!empty($fileType) && !empty($fileSize)){
		if(($fileType == 'image/PNG' ||
		   $fileType == 'image/png' ||
		   $fileType == 'image/JPG' ||
		   $fileType == 'image/jpg' ||
		   $fileType == 'image/GIF' ||
		   $fileType == 'image/gif' ||
		   $fileType == 'image/JPEG' ||
		   $fileType == 'image/jpeg') 
		   && $fileSize < $maxSize){
			   $error = $_FILES['photo']['error'];
			   if($error > 0){
				   $error['status'] = "Photo upload error";
				   $error['code'] = 500;
				   echo json_encode($error);
				   exit();
			   }
			   $photoName = $_FILES['photo']['name'];
			   $photoSource = $_FILES['photo']['tmp_name'];
			   $byts = openssl_random_pseudo_bytes(4, $cstrong);
			   $hex = bin2hex($byts);
			   $extension = $Helper->getExtension($photoName);
			   $newPhoto = 'user-'.$userId.'-'.$hex.$extension;
			   $newPath = $userdir.$newPhoto;
			   if(!file_exists($userdir)){
				   mkdir($userdir,0755);
			   }
			   
			   $tmpPhoto = $Controller->getTempProfilePic($userId);
			   if($tmpPhoto){
				   $removeTempPhoto = $Controller->removeTempProfilePic($userId,$tmpPhoto,$userdir);
			   }
			   
			   if(move_uploaded_file($photoSource,$newPath)){
				   $cropProfilePic = $Helper->cropProfilePhoto($newPath);
				   if($cropProfilePic){
					   list($width,$height) = getimagesize($newPath);
					   $response['width'] = $width;
					   $response['height'] = $height;
					   $response['photo'] = $newPhoto;
					   $response['user_id'] = $userId;
					   echo json_encode($response);
					   exit();
				   }
			   } else{
				   $error['status'] = 'Internal photo upload error';
				   $error['code'] = 500;
				   echo json_encode($error);
				   exit();
			   }
		   } else {
			   $error['status'] = 'Photo must gif,png,or jpg';
			   $error['code'] = 401;
			   echo json_encode($error);
			   exit();
		   }
	} else {
		$error['status'] = 'Empty file uploaded';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}