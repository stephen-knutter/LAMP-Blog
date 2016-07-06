<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	$userId = $_SESSION['logged_in_id'];
	$userdir = '../assets/user-images/'.$userId.'/';
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$x = $_POST['x'] ? $_POST['x'] : 0;
	$y = $_POST['y'] ? $_POST['y'] : 0;
	$w = $_POST['w'] ? $_POST['w'] : 190;
	$h = $_POST['h'] ? $_POST['h'] : 190;
	$pic = $_POST['pic'];
	
	if(!empty($pic)){
		$tempPic = $Controller->getTempProfilePic($userId);
		if($tempPic){
			$Controller->removeTempProfilePic($userId,$tempPic,$userdir);
		}
		$tempPhoto = $Helper->getFileFromFilePath($pic);
		$newPhoto = 'budvibes-'.$tempPhoto;
		$newPath = $userdir.$newPhoto;
		$newCrop = $Helper->cropProfilePhotoExact($userdir,$tempPhoto,$x,$y,$w,$h);
		if($newCrop){
			$newThumbs = $Helper->cropProfilePicThumbs($userdir,$newPhoto);
			if($newThumbs){
				//MUST GRAB OLD PROFILE PIC HERE BEFORE INSERTION OF NEW PIC
				$curProfilePic = $Controller->getUserProfilePic($userId);
				$newProfilePic = $Controller->addNewProfilePic($userId,$newPhoto);
				if($newProfilePic){
				  //REMOVE OLD PIC & THUMBS
				  if($curProfilePic){
					$curProfilePicPath = $userdir.$curProfilePic;
					if(file_exists($curProfilePicPath)){
						$curRelationPic = $userdir.'relation-'.$curProfilePic;
						$curTopPic = $userdir.'top-'.$curProfilePic;
						$curThumbPic = $userdir.'thumb-'.$curProfilePic;
						$curSmallThumbPic = $userdir.'thumbsmall-'.$curProfilePic;
						if(file_exists($curRelationPic)){
							unlink($curRelationPic);
						}
						if(file_exists($curTopPic)){
							unlink($curTopPic);
						}
						if(file_exists($curThumbPic)){
							unlink($curThumbPic);
						}
						if(file_exists($curSmallThumbPic)){
							unlink($curSmallThumbPic);
						}
						unlink($curProfilePicPath);
					}
				  }
				}
				
				if(file_exists($newPath)){
					$_SESSION['logged_in_photo'] = $newPhoto;
					//setcookie('logged_in_photo', $newPhoto, time() + (60*60*24*30));
					$success['status'] = 'Photo uploaded successfully';
					$success['code'] = 200;
					echo json_encode($success);
					exit();
				} else {
					$error['status'] = 'Internal upload error';
					$error['code'] = 500;
					echo json_encode($error);
					exit();
				}
				
			} else {
			    //ERROR
				$error['status'] = 'Internal upload error';
		        $error['code'] = 500;
		        echo json_encode($error);
		        exit();
			}
		} else {
			//ERROR
			$error['status'] = 'Internal upload error';
		    $error['code'] = 500;
		    echo json_encode($error);
		    exit();
		}
	} else {
		$error['status'] = 'No picture uploaded';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	
	
	
	
	