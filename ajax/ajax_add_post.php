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
	
	$username = trim($_SESSION['logged_in_user']); //DELETE !!!!!!!!!
	$userId = $_SESSION['logged_in_id'];
	
	set_error_handler("warning_handler", E_WARNING);
	function warning_handler($errno, $errstr) { 
		$error['status'] = $errstr;
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	//POST ELEMENTS
	$curWallId = trim($_POST['cur_wall_id']);
	$curWallUser = trim($_POST['cur_wall_user']); //DELETE!!!!!!!!!!!
	$xhr = trim($_POST['xhr_type']);
	$postType = trim($_POST['post_type']);
	$userText = $Helper->createnofollow($Helper->sanitizeInput(trim($_POST['text'])));
	$media = trim($_POST['media']);
	$frame = trim($_POST['iframe']); // 0 or 1;
	$userdir = '../assets/user-images/'.$userId.'/';
	//USER PHOTO
	$userPhoto = trim($_POST['photo']);
	if($userPhoto == 'NULL' || !($userPhoto)){
		$userPhoto = false;
	}
	//USER VIDEO
	$userVideo = trim($_POST['video']);
	if($userVideo == 'NULL' || !($userVideo)){
		$userVideo = false;
	}
	//USER LINK
	$userLink = trim($_POST['link']);
	if($userLink == 'NULL' || !($userLink)){
		$userLink = false;
	}
	//LINK INFO
	$linkInfo = trim($_POST['link_info']);
	if($linkInfo == 'NULL' || !($linkInfo)){
		$linkInfo = false;
	}
	//USER TAGS
	@$tags = $_POST['tags'];
	if(!empty($tags)){
		$tagString = implode(",",$tags);
		$tagString = strip_tags($tagString);
	} else {
		$tagString = 'NULL';
	}
	//USER RATING
	$rating = (trim($_POST['rating']));
	if(is_numeric($rating) && $rating != 'NULL' && !empty($rating)){
		$rating = (float)($rating / 2);
		$storeId = $Controller->getStoreIdFromUserId($curWallId);
		if($storeId){
			$curRating = $Controller->getStoreRating($storeId);
			if($curRating){
				$oldValue = $curRating['value'];
				$oldVotes = $curRating['votes'];
				$addRating = $Controller->updateStoreRating($storeId,$rating,$oldValue,$oldVotes);
			} else {
				$addRating = $Controller->addStoreRating($storeId,$rating);
			}
		}
	} else {
		$rating = 0;
	}
	
	if(!empty($userText) || 
	   !empty($userPhoto) || 
	   !empty($userVideo) || 
	   !empty($userLink)){
		
		if((!empty($userText) && 
		    !empty($userPhoto)) 
			&& (empty($userVideo) && 
			    empty($userLink))){
			/*
			 * @PHOTO AND TEXT COMMENT (comm_type[s] = pf,rf,sf)
			**/
			
			$newCommentId = false;
			$tempPic = $Controller->getTempPic($userId);
			$newPhoto = $Helper->getFileFromFilePath($userPhoto);
			$extension = $Helper->getExtension($newPhoto);
			//SET COMM_TYPE
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rf';
				} else {
					$type = 'pf';
				}
			} else {
				$type = 'sf';
			}
			//REMOVE TEMP PHOTO
			$Controller->removeTempPhoto($userId);
			//REMOVE SMALL PHOTO
			$path = $userdir.'small-'.$newPhoto;
			if(file_exists($path)){
				unlink($path);
			}
			//RESIZE PHOTOS
			$newPath = $userdir.$newPhoto;
			if(file_exists($newPath)){
				$newPics = $Controller->cropPhotos($userdir,$newPhoto);
				//ADD COMMENT TO DB
				if($newPics){
					switch($postType){
						case 'product':
							$newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														                  $type,$userId,
														                  $userText,$newPhoto,
														                  'NULL',$tagString);
						break;
						default:
							$newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														                  $type,$userId,
														                  $userText,$newPhoto,
														                  'NULL',$tagString);
						break;
					}
					
					if($newCommentId){
						$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
						if($newComment){
							echo $Views->generateFeed($newComment,'front',true);
							if($tags){
								$addTags = $Controller->addTags($newCommentId,$tags);
							}
							exit();
						}
					}
				}
			}
		} else if((!empty($userText)) 
			       && (empty($userPhoto) && 
			           empty($userVideo) && 
					   empty($userLink))){
			/*
			 * @TEXT ONLY COMMENT (comm_type[s] = pt,rt,st)
			**/
			$newCommentId = false;
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rt';
				} else {
					$type = 'pt';
				}
			} else {
				$type = 'st';
			}
			switch($postType){
				case 'product':
					$newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														          $type,$userId,
														          $userText,'NULL',
														          'NULL',$tagString);
				break;
				default:
					$newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														          $type,$userId,
														          $userText,'NULL',
														          'NULL',$tagString);
				break;
			}
			if($newCommentId){
				$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
				if($newComment){
					echo $Views->generateFeed($newComment,'front',true);
					if($tags){
						$addTags = $Controller->addTags($newCommentId,$tags);
					}
					exit();
				}
			}
		} else if((!empty($userPhoto)) 
			       && (empty($userText) && 
			           empty($userVideo) && 
					   empty($userLink))){
			/*
			 * @PHOTO ONLY COMMENT (comm_type[s] = pp,rp,sp)
			**/
			$newCommentId = false;
			$tempPic = $Controller->getTempPic($userId);
			$newPhoto = $Helper->getFileFromFilePath($userPhoto);
			$extension = $Helper->getExtension($newPhoto);
			//SET COMM_TYPE
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rp';
				} else {
					$type = 'pp';
				}
			} else {
				$type = 'sp';
			}
			//REMOVE TEMP PHOTO
			$Controller->removeTempPhoto($userId);
			//REMOVE SMALL PHOTO
			$path = $userdir.'small-'.$newPhoto;
			if(file_exists($path)){
				unlink($path);
			}
			//RESIZE PHOTOS
			$newPath = $userdir.$newPhoto;
			if(file_exists($newPath)){
				$newPics = $Controller->cropPhotos($userdir,$newPhoto);
				//ADD COMMENT TO DB
				if($newPics){
					switch($postType){
						case 'product':
							$newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														                  $type,$userId,
														                  'NULL',$newPhoto,
														                  'NULL',$tagString);
						break;
						default:
							$newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														                  $type,$userId,
														                  'NULL',$newPhoto,
														                  'NULL',$tagString);
						break;
					}
					
					if($newCommentId){
						$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
						if($newComment){
							echo $Views->generateFeed($newComment,'front',true);
							if($tags){
								$addTags = $Controller->addTags($newCommentId,$tags);
							}
							exit();
						}
					}
				}
			}
		} else if((!empty($userVideo) && 
		           !empty($userText)) 
				   && (empty($userPhoto) && 
				       empty($userLink))){
			/*
			 * @VIDEO AND TEXT COMMENT (comm_type[s] = pvf,rvf,svf)
			**/
			$newCommentId = false;
			$tempVideo = $Controller->getTempVideo($userId);
			$newVideo = $Helper->getFileFromFilePath($userVideo);
			$extension = $Helper->getExtension($newVideo);
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rvf';
				} else {
					$type = 'pvf';
				}
			} else {
				$type = 'svf';
			}
			//REMOVE TEMP VIDEO
			$Controller->removeTempVideo($userId);
			//ADD VIDEO
			$feedVideo = 'feed-'.$newVideo;
			$feedVideoPath = $userdir.$feedVideo;
			$oldVideoPath = $userdir.$newVideo;
			if(rename($oldVideoPath,$feedVideoPath)){
				switch($postType){
					case 'product':
						$newCommentId = $Controller->addProdVideoOnly($curWallId,$rating,
														              $type,$userId,
														              $userText,'NULL',
														              $newVideo,$tagString);
					break;
					default:
						$newCommentId = $Controller->addUserVideoOnly($curWallId,$rating,
														              $type,$userId,
														              $userText,'NULL',
														              $newVideo,$tagString);
					break;
				}
				
			}
			if($newCommentId){
				$addVideoPic = $Controller->generateVideoPic($feedVideo,
												             $userId,
												             $newCommentId,
												             $postType);
				$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
				if($newComment){
					echo $Views->generateFeed($newComment,'front',true);
					if($tags){
						$addTags = $Controller->addTags($newCommentId,$tags);
					}
					exit();
				}
			} else {
				//ERROR
				$error['status']  ='Error adding video';
				$error['code'] = 500;
				echo json_encode($error);
				exit();
			}
		} else if((!empty($userVideo)) 
			       && (empty($userText) && 
			           empty($userPhoto) && 
					   empty($userLink))){
			/*
			 * @VIDEO ONLY COMMENT (comm_type[s] = pvv,rvv,svv)
			**/
			$newCommentId = false;
			$tempVideo = $Controller->getTempVideo($userId);
			$newVideo = $Helper->getFileFromFilePath($userVideo);
			$extension = $Helper->getExtension($newVideo);
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rvv';
				} else {
					$type = 'pvv';
				}
			} else {
				$type = 'svv';
			}
			//REMOVE TEMP VIDEO
			$Controller->removeTempVideo($userId);
			//ADD VIDEO
			$feedVideo = 'feed-'.$newVideo;
			$feedVideoPath = $userdir.$feedVideo;
			$oldVideoPath = $userdir.$newVideo;
			if(rename($oldVideoPath,$feedVideoPath)){
				switch($postType){
					case 'product':
						$newCommentId = $Controller->addProdVideoOnly($curWallId,$rating,
														              $type,$userId,
														              'NULL','NULL',
														              $newVideo,$tagString);
					break;
					default:
						$newCommentId = $Controller->addUserVideoOnly($curWallId,$rating,
														              $type,$userId,
														              'NULL','NULL',
														              $newVideo,$tagString);
					break;
				}
				if($newCommentId){
					$addVideoPic = $Controller->generateVideoPic($feedVideo,
												                 $userId,
												                 $newCommentId,
												                 $postType);
					$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
				    if($newComment){
						echo $Views->generateFeed($newComment,'front',true);
						if($tags){
							$addTags = $Controller->addTags($newCommentId,$tags);
						}
						exit();
					}
				} else {
					//ERROR
					$error['status']  ='Error adding video';
					$error['code'] = 500;
					echo json_encode($error);
					exit();
				}
			} else{
				//ERROR
				$error['status'] = 'Error adding video';
				$error['code'] = 500;
				echo json_encode($error);
				exit();
			}
		} else if((!empty($userLink)) 
			       && (empty($userVideo) && 
			           empty($userText) && 
					   empty($userPhoto))){
			/*
			 * @LINK MEDIA ONLY (comm_type[s] = pll,rll,sll)
			**/
			$newCommentId = false;
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rll';
				} else {
					$type = 'pll';
				}
			} else {
				$type = 'sll';
			}
			if($frame == 'noframe' || $frame == 'framephoto'){
				//GET EXTENSION
			    $tempPic = $Controller->getTempPic($userId);
			    $linkPhoto = $Helper->getFileFromFilePath($userLink);
			    $extension = $Helper->getExtension($linkPhoto);
				$bytes = openssl_random_pseudo_bytes(4,$cstrong);
				$hex = bin2hex($bytes);
				$oldUrl = $userdir.$linkPhoto;
				$newPhoto = 'budvibes-'.$userId.'-'.$hex.$extension;
				$newPath = $userdir.$newPhoto;
				if(@rename($oldUrl, $newPath)){
					$newPics = $Controller->cropPhotos($userdir,$newPhoto);
					if($newPics){
						if($frame == 'framephoto' || $frame == 'noframephoto'){
							$newVideo = $linkInfo;
							$type = $type . 'v';
						} else {
							$newVideo = 'NULL';
							$userText = $linkInfo;
						}
						switch($postType){
							case 'product':
							  $newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														                    $type,$userId,
														                    $userText,$newPhoto,
														                    $newVideo,$tagString);
						    break;
						    default:
							  $newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														                    $type,$userId,
														                    $userText,$newPhoto,
														                    $newVideo,$tagString);
						    break;
						}
						
					}
					if($newCommentId){
						$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
						if($newComment){
							echo $Views->generateFeed($newComment,'front',true);
							if($tags){
								$addTags = $Controller->addTags($newCommentId,$tags);
							}
							exit();
						}
					}
				}
			}
		} else if((!empty($userLink) && 
		           !empty($userText)) 
				   && (empty($userVideo) && 
				       empty($userPhoto))){
			/*
			 * @LINK AND TEXT COMMENT (comm_type[s] = plf,rlf,slf)
			**/
			$newCommentId = false;
			if($postType != 'product'){
				if($rating > 0){
					$type = 'rll';
				} else {
					$type = 'pll';
				}
			} else {
				$type = 'sll';
			}
			//GET EXTENSION
			$tempPic = $Controller->getTempPic($userId);
			$linkPhoto = $Helper->getFileFromFilePath($userLink);
			$extension = $Helper->getExtension($linkPhoto);
			$bytes = openssl_random_pseudo_bytes(4,$cstrong);
			$hex = bin2hex($bytes);
			$oldUrl = $userdir.$linkPhoto;
			$newPhoto = 'budvibes-'.$userId.'-'.$hex.$extension;
			$newPath = $userdir.$newPhoto;
			if(@rename($oldUrl, $newPath)){
				$newPics = $Controller->cropPhotos($userdir,$newPhoto);
				if($newPics){
					if($frame == 'framephoto' || $frame == 'noframephoto'){
						$newVideo = $linkInfo;
						$type = $type . 'v';
					} else {
						$newVideo = 'NULL';
						$userText = $linkInfo . "<div class='commPostText'><p>".$userText."</p></div>";
					}
					switch($postType){
						case 'product':
							$newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														                    $type,$userId,
														                    $userText,$newPhoto,
														                    $newVideo,$tagString);
						break;
						default:
							$newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														                    $type,$userId,
														                    $userText,$newPhoto,
														                    $newVideo,$tagString);
						break;
						}
						
					}
				if($newCommentId){
					$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
					if($newComment){
						echo $Views->generateFeed($newComment,'front',true);
						if($tags){
							$addTags = $Controller->addTags($newCommentId,$tags);
						}
						exit();
					}
				}
			}
		}
	} else {
		$error['status'] = 'No items to post';
		$error['code'] = 204;
		echo json_encode($error);
		exit();
	}