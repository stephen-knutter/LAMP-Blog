<?php 
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/chats_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$ChatsCtrl = new ChatsCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$sessionId = $_SESSION['logged_in_id'];
	$chatWithId = (int)$_POST['user_id'];
	$parent = (int)$_POST['parent'];
	$msgPic = $_FILES['pic'];
	$msgType = 'mp';
	$message = 'NULL';
	
	if(empty($chatWithId) || empty($parent)){
		$error['status'] = 'No message';
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	$picType = @$_FILES['pic']['type'];
	$maxPhotoSize = __PHOTOBYTES__ * 10;
	$bits = openssl_random_pseudo_bytes(4,$cstrong);
	$hex = bin2hex($bits);
	
	if(!empty($picType)){
		$picSize = $_FILES['pic']['size'];
		if(($picType == 'image/png' ||
			$picType == 'image/PNG' ||
	        $picType == 'image/JPG' ||
	        $picType == 'image/jpg' ||
	        $picType == 'image/gif' ||
	        $picType == 'image/GIF' ||
	        $picType == 'image/JPEG' ||
	        $picType == 'image/jpeg') && 
	        $picSize < $maxPhotoSize){
				$picError = $_FILES['pic']['error'];
				if($picError){
				  $error['status'] = 'Internal error';
				  $error['code'] = 500;
				  echo json_encode($error);
				  exit();
			    }
				$userdir = '../assets/user-images/'.$sessionId.'/';
		        $linkdir = __LOCATION__ . '/assets/user-images/'.$sessionId.'/';
				$picName = $_FILES['pic']['name'];
				$picSource = $_FILES['pic']['tmp_name'];
				$extension = $Helper->getExtension($picName);
				$newPhotoName = 'message-'.$sessionId.'-'.$hex.$extension;
				$targetPath = $userdir.$newPhotoName;
				if(!file_exists($userdir)){
					mkdir($userdir,0755);
				}
				if(move_uploaded_file($picSource,$targetPath)){
					$msgPhoto = new \bv\resize($targetPath);
					$msgPhoto->resizeImage(150,150);
					$msgPhoto->saveImage($targetPath,80);
					if(file_exists($targetPath) && is_numeric($parent)){
						$newThreadId = $ChatsCtrl->addNewThreadMsg($parent,
												                   $sessionId,
												                   $chatWithId,
												                   $msgType,
												                   $newPhotoName,
												                   $message);
						if($newThreadId){
							$chatThread = $ChatsCtrl->getChatThread($newThreadId);
							if($chatThread){
								$success['messages'] = $Helper->formatMessages($chatThread,$sessionId);
							}
							$success['code'] = 200;
						} else {
							$success['code'] = 500;
							$success['messages'] = 0;
						}
			            echo json_encode($success);
			            exit();
					} else {
						if(file_exists($targetPath)){
							unlink($targetPath);
						}
					    $error['code'] = 500;
					    $error['status'] = 'Internal error';
					    echo json_encode($error);
					    exit();
					}
				} else {
					$error['code'] = 500;
					$error['status'] = 'Internal error';
					echo json_encode($error);
					exit();
				}
		}
	}
	
	   
	   
	   