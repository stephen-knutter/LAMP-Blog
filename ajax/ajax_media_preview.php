<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	$xhr = @$_POST['xhr_type'] == 'true' ? true : false;
	$forumPost = isset($_POST['post_forum']) ? true : false;
	
	set_error_handler("warning_handler", E_WARNING);
	function warning_handler($errno, $errstr) { 
		$error['status'] = 'Internal error';
		$error['code'] = 500;
		$response = json_encode($error);
		echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
		exit();
	}
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must log in to add post';
		$error['code'] = 401;
		$response = json_encode($error);
		echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
		exit();
	}
	
	$userId = $_SESSION['logged_in_id'];
	$userName = $_SESSION['logged_in_user'];
	$userdir = '../assets/user-images/'.$userId.'/';
	$linkdir = __LOCATION__ . '/assets/user-images/'.$userId.'/';
	$fileTypePhoto = @$_FILES['post_photo']['type'];
	$fileTypeVideo = @$_FILES['post_video']['type'];
	$bytes = 1048576; //bytes per meg
	$maxPhotoSize = $bytes * 10;
	$maxVideoSize = $bytes * 20;
	$bits = openssl_random_pseudo_bytes(4,$cstrong);
	$hex = bin2hex($bits);
	
	if(!empty($fileTypePhoto) || !empty($fileTypeVideo)){
		$fileSizePhoto = @$_FILES['post_photo']['size'];
		$fileSizeVideo = @$_FILES['post_video']['size'];
		if(($fileTypePhoto == 'image/PNG' ||
		   $fileTypePhoto == 'image/png' ||
		   $fileTypePhoto == 'image/JPG' ||
		   $fileTypePhoto == 'image/jpg' || 
		   $fileTypePhoto == 'image/GIF' ||
		   $fileTypePhoto == 'image/gif' ||
		   $fileTypePhoto == 'image/JPEG' ||
		   $fileTypePhoto == 'image/jpeg') && 
		   $fileSizePhoto < $maxPhotoSize){
			   
			   $fileError = $_FILES['post_photo']['error'];
			   $fileName = $_FILES['post_photo']['name'];
			   $fileSource = $_FILES['post_photo']['tmp_name'];
			   $extension = $Helper->getExtension($fileName);
			   $newPhotoName = 'budvibes-'.$userId.'-'.$hex.$extension;
			   $largePhotoName = 'large-'.$newPhotoName;
			   $smallPhotoName = 'small-'.$newPhotoName;
			   $targetPath = $userdir.$newPhotoName;
			   //CHECK FOR FILE UPLOAD ERROR
			   if($fileError){
				  $error['status'] = 'Internal error';
				  $error['code'] = 500;
				  $response =  json_encode($error);
				  echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
				  exit();
			   }
			   
			   
			   if(!$forumPost){
				   $tempPic = $Controller->getTempPic($userId);
				   if($tempPic){
					   $tempPath = $userdir.$tempPic;
					   $tempPathSmall = $userdir.'small-'.$tempPic;
					   $tempPathLarge = $userdir.'large-'.$tempPic;
					   if(file_exists($tempPath)){
						   unlink($tempPath);
					   }
					   if(file_exists($tempPathLarge)){
						   unlink($tempPathLarge);
					   }
					   if(file_exists($tempPathSmall)){
						   unlink($tempPathSmall);
					   }
				   }
				   $Controller->removeTempPhoto($userId);
			   }
			   
			   if(!file_exists($userdir)){
				   mkdir($userdir,755);
			   }
			   
			   //PROCESS NEW PHOTO
			   if(move_uploaded_file($fileSource,$targetPath)){
				   //RESIZE PHOTO
				   $largePhotoPath = $userdir.$largePhotoName;
				   $smallPhotoPath = $userdir.$smallPhotoName;
				   $largePhoto = new \bv\resize($targetPath);
				   $largePhoto->resizeImage(516,516);
				   $largePhoto->saveImage($largePhotoPath,80);
				   $smallPhoto = new \bv\resize($targetPath);
				   $smallPhoto->resizeImage(100,100);
				   $smallPhoto->saveImage($smallPhotoPath,80);
				   //ADD TO TEMP DIRECTORY
				   if(file_exists($largePhotoPath) && 
					  file_exists($smallPhotoPath) &&
					  file_exists($targetPath)){
						  $addPhoto = $Controller->addTempPic($userId,
						                                      $newPhotoName,
															  $fileTypePhoto);
						  if($addPhoto){
							  $success = array();
							  $success['media_type'] = 'photo';
							  $success['file_source'] = $linkdir.$largePhotoName;
							  $success['username'] = $userName;
							  $photoJson = json_encode($success);
							  echo $xhr ? $photoJson : '<textarea>'.$photoJson.'</textarea>';
							  exit();
						  } else {
							if(file_exists($targetPath)){
								unlink($targetPath);
							}
							if(file_exists($largePhotoPath)){
								unlink($largePhotoPath);
							}
							if(file_exists($smallPhotoPath)){
								unlink($smallPhotoPath);
							}
							$error['status'] = 'Internal error';
							$error['code'] = 500;
							$response = json_encode($error);
							echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
						  }
					  } else {
						  $error['status'] = 'Internal error';
						  $error['code'] = 500;
						  $response = json_encode($error);
						  echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
						  exit();
					  }
			   } else {
				 $error['status'] = 'Internal error';
				 $error['code'] = 500;
				 $response = json_encode($error);
				 echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
				 exit();
			   }
		   } else if(($fileTypeVideo == 'video/mp4' ||
					  $fileTypeVideo == 'video/mp4v-es' ||
					  $fileTypeVideo == 'video/ogg' ||
					  $fileTypeVideo == 'video/webm' ||
					  $fileTypeVideo == 'video/avi' ||
					  $fileTypeVideo == 'video/vivo' ||
					  $fileTypeVideo == 'video/vdo' ||
					  $fileTypeVideo == 'video/ty' ||
					  $fileTypeVideo == 'video/quicktime' ||
					  $fileTypeVideo == 'video/ogm' ||
					  $fileTypeVideo == 'video/mpeg4' ||
					  $fileTypeVideo == 'video/mpeg-2' ||
					  $fileTypeVideo == 'video/mpeg' ||
					  $fileTypeVideo == 'video/h264' ||
					  $fileTypeVideo == 'video/flv' ||
					  $fileTypeVideo == 'video/divx' ||
					  $fileTypeVideo == 'video/annodex' ||
					  $fileTypeVideo == 'video/3gpp2' ||
					  $fileTypeVideo == 'video/3gpp') && 
					  $fileSizeVideo < $maxVideoSize){
						$fileError = $_FILES['post_video']['error'];
						$fileName = $_FILES['post_video']['name'];
						$fileSource = $_FILES['post_video']['tmp_name'];
						$extension = $Helper->getExtension($fileName);
						$newVideoName = 'video-budvibes-'.$userId.'-'.$hex.$extension;
						$targetPath = $userdir.$newVideoName;
						$tempVideo = $Controller->getTempVideo($userId);
						if($tempVideo){
							$tempVideoPath = $userdir.$tempVideo;
							if(file_exists($tempVideoPath)){
								unlink($tempVideoPath);
							}
							
							$Controller->removeTempVideo($userId);
						}
						
						if(!file_exists($userdir)){
							mkdir($userdir);
						}
						
						if(move_uploaded_file($fileSource,$targetPath)){
							$addVideo = $Controller->addTempVideo($userId,
							                                      $newVideoName,
																  $fileTypeVideo);
							if($addVideo){
								$success = array();
								$success['media_type'] = 'video';
								$success['file_source'] = $linkdir.$newVideoName;
								$success['username'] = $userName;
								$videoJson = json_encode($success);
								echo $xhr ? $videoJson : '<textarea>'.$videoJson.'</textarea>';
								exit();
							} else {
								//ERROR
								if(file_exists($targetPath)){
									unlink($targetPath);
								}
								$error['status'] = 'Error adding video';
								$error['code'] = 500;
								$response = json_encode($error);
								echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
							}
						} else {
							//ERROR
							$error['status'] = 'Internal error';
							$error['file_source'] = $fileSource;
							$error['file_path'] = $newVideoName;
							$error['code'] = 500;
							$response = json_encode($error);
							echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
							exit();
						}
					  } else {
						  $error['status'] = 'Empty file';
						  $error['code'] = 402;
						  $response = json_encode($error);
						  echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
						  exit();
					  } //END FILES CHECK FOR PHOTO
	}