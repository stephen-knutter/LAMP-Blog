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
	$maxPhotoSize = __PHOTOBYTES__ * 10;
	$maxVideoSize = __PHOTOBYTES__ * 20;
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
			   //CHECK FOR FILE UPLOAD ERROR
			   if($fileError){
				  $error['status'] = 'Internal error';
				  $error['code'] = 500;
				  $response =  json_encode($error);
				  echo $xhr ? $response : '<textarea>'.$response.'</textarea>';
				  exit();
			   }
			   $fileName = $_FILES['post_photo']['name'];
			   $fileSource = $_FILES['post_photo']['tmp_name'];
			   $extension = $Helper->getExtension($fileName);
			   $newPhotoName = 'budvibes-'.$userId.'-'.$hex.$extension;
			   $targetPath = $userdir.$newPhotoName;
			   
			   if(!$forumPost){
				   $tempPic = $Controller->getTempPic($userId);
				   if($tempPic){
					   $tempPath = $userdir.$tempPic;
					   if(file_exists($tempPath)){
						   unlink($tempPath);
					   }
				   }
				   $Controller->removeTempPhoto($userId);
			   }
			   
			   if(!file_exists($userdir)){
				   mkdir($userdir,0755);
			   }
			   
			   //PROCESS NEW PHOTO
			   if(move_uploaded_file($fileSource,$targetPath)){
				   //RESIZE PHOTO/REMOVE GIF FRAMES
				   $previewPic = $Helper->cropPreview($targetPath);
				   //ADD TO TEMP DIRECTORY
				   if(file_exists($targetPath)){
						  $addPhoto = $Controller->addTempPic($userId,
						                                      $newPhotoName,
															  $fileTypePhoto);
						  if($addPhoto){
							  $success = array();
							  $success['media_type'] = 'photo';
							  $success['file_source'] = $linkdir.$newPhotoName;
							  $success['username'] = $userName;
							  $photoJson = json_encode($success);
							  echo $xhr ? $photoJson : '<textarea>'.$photoJson.'</textarea>';
							  exit();
						  } else {
							if(file_exists($targetPath)){
								unlink($targetPath);
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