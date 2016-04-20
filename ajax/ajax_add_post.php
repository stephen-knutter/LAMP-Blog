<?php 
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$Controller = new ApplicationCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	$username = trim($_SESSION['logged_in_user']); //DELETE !!!!!!!!!
	$userId = $_SESSION['logged_in_id'];
	
	set_error_handler("warning_handler", E_WARNING);
	function warning_handler($errno, $errstr) { 
		$error['status'] = $errstr;
		$error['code'] = 500;
		echo json_encode($error);
		exit();
	}
	
	if(!$userId){
		$error['status'] = 'Must log in to add post';
		$error['code'] = 401;
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
	$rating = trim($_POST['rating']);
	if(is_numeric($rating) && $rating != 'NULL' && !empty($rating)){
		$rating = $rating / 2;
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
			$newPhoto = $Helper->getPhoto($userPhoto);
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
						case 'products':
							$newCommentId = $Controller->addProdPhotoFull($curWallId,$rating,
														  $type,$userId,
														  $userText,$newPhoto,
														  $tagString);
						break;
						default:
							$newCommentId = $Controller->addUserPhotoFull($curWallId,$rating,
														  $type,$userId,
														  $userText,$newPhoto,
														  $tagString);
						break;
					}
					
					if($newCommentId){
						$newComment = $Controller->generateNewComment($newCommentId,$xhr,$postType);
						if($newComment){
							$Views->generateFeed($newComment,'front');
						}
					}
				}
			}
		} else if((!empty($userText)) 
			       && (empty($userPhoto) && 
			           empty($userVideo) && 
					   empty($userLink))){
			//TEXT ONLY COMMENT (comm_type[s] = pt, rt, st)
			
		} else if((!empty($userPhoto)) 
			       && (empty($userText) && 
			           empty($userVideo) && 
					   empty($userLink))){
			//PHOTO ONLY COMMENT (comm_type[s] = pp, rp, sp)
			
		} else if((!empty($userVideo) && 
		           !empty($userText)) 
				   && (empty($userPhoto) && 
				       empty($userLink))){
			//VIDEO AND TEXT COMMENT (comm_type[s] = pvf, rvf, svf)
			
		} else if((!empty($userVideo)) 
			       && (empty($userText) && 
			           empty($userPhoto) && 
					   empty($userLink))){
			//VIDOE ONLY COMMENT (comm_type[s] = pvv, rvv, svv)
			
		} else if((!empty($userLink)) 
			       && (empty($userVideo) && 
			           empty($userText) && 
					   empty($userPhoto))){
			//LINK ONLY COMMENT (comm_type[s] = pll, rll, sll)
			
		} else if((!empty($userLink) && 
		           !empty($userText)) 
				   && (empty($userVideo) && 
				       empty($userPhoto))){
			//LINK AND TEXT COMMENT (comm_type[s] = plf, rlf, slf)
			
		}
		
	} else {
		$error['status'] = 'No items to post';
		$error['code'] = 204;
		echo json_encode($error);
		exit();
	}
	
	
	
	
	
	
	
	
	