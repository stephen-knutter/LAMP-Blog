<?php 
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	@$userText = $Helper->createnofollow($Helper->sanitizeInput(trim($_POST['text'])));
	@$userPhoto = $_POST['photo'];
	$postType = $_POST['post_type'];
	$commId = $_POST['comm_id'];
	$sessionId = (int)$_SESSION['logged_in_id'];
	$userdir = '../assets/user-images/'.$sessionId.'/';
	$xhr = $_POST['xhr_type'];
	
	if(!empty($commId)){
		if(!empty($userText) || !empty($userPhoto)){
			if((!empty($userText) && !empty($userPhoto)) || 
			   (empty($userText) && !empty($userPhoto))){
					$tempPic = $Controller->getTempPic($sessionId);
					$newPhoto = $Helper->getFileFromFilePath($userPhoto);
					$extension = $Helper->getExtension($newPhoto);
					//REMOVE TEMP PHOTO
					$Controller->removeTempPhoto($sessionId);
					//REMOVE SMALL PHOTO
					$path = $userdir.'small-'.$newPhoto;
					if(file_exists($path)){
						unlink($path);
					}
					//RESIZE PHOTOS
					$newPath = $userdir.$newPhoto;
					if(file_exists($newPath)){
						$newPic = $Controller->cropReplyPhoto($userdir,$newPhoto);
						if($newPic){
							if(file_exists($userdir.$newPhoto)){
								unlink($userdir.$newPhoto);
							}
							if(!empty($userText) && !empty($userPhoto)){
							   //PHOTO & TEXT REPLY
								switch($postType){
									case 'product':
										$newReplyId = $Controller->addProdReplyFull($commId,$sessionId,
																					$userText,$newPhoto);
									break;
									default: 
										$newReplyId = $Controller->addUserReplyFull($commId,$sessionId,
																					$userText,$newPhoto);
									break;
								}
							} else if(empty($userText) && !empty($userPhoto)){
							  //PHOTO ONLY REPLY
								switch($postType){
									case 'product':
										$newReplyId = $Controller->addProdReplyFull($commId,$sessionId,
																				     'NULL',$newPhoto);
									break;
									default: 
										$newReplyId = $Controller->addUserReplyFull($commId,$sessionId,
																				     'NULL',$newPhoto);
									break;
								}
							}
							if($newReplyId){
								$newReply = $Controller->generateNewReply($newReplyId,$xhr,$postType);
								if($newReply){
									echo $Views->generateReplies($newReply,true);
									exit();
								}
							} else {
								$error['code'] = 501;
								$error['status'] = 'Internal error';
								echo json_encode($error);
								exit();
							}
						} else {
							$error['code'] = 501;
							$error['status'] = 'Internal error';
							echo json_encode($error);
							exit();
						}
					} else {
						$error['code'] = 501;
						$error['status'] = 'Internal error';
						echo json_encode($error);
						exit();
					}
			} else if(!empty($userText) && empty($userPhoto)){
				//TEXT ONLY COMMENT
				switch($postType){
					case 'product':
						$newReplyId = $Controller->addProdReplyFull($commId,$sessionId,
																	$userText,'NULL');
					break;
					default: 
						$newReplyId = $Controller->addUserReplyFull($commId,$sessionId,
																	$userText,'NULL');
					break;
				}
				if($newReplyId){
					$newReply = $Controller->generateNewReply($newReplyId,$xhr,$postType);
					if($newReply){
						echo $Views->generateReplies($newReply,true);
						exit();
					}
				} else {
					$error['code'] = 501;
					$error['status'] = 'Internal error';
					echo json_encode($error);
					exit();
				}
			} else {
				$error['code'] = 500;
				$error['status'] = 'Nothing to reply';
				echo json_encode($error);
				exit();
			}
		} else {
			$error['code'] = 500;
			$error['status'] = 'Nothing to reply';
			echo json_encode($error);
			exit();
		}
	} else {
		$error['code'] = 500;
		$error['status'] = 'Invalid reply';
		echo json_encode($error);
		exit();
	}
	