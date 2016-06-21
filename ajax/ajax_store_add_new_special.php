<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/stores_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$StoresCtrl = new StoresCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$sessionId = (int)$_SESSION['logged_in_id'];
	$storeId = (int)$_SESSION['store_id'];
	$storeName = $_SESSION['logged_in_user'];
	$specialOffer = trim($_POST['special_offer']);
	$specialOffer = $Helper->sanitizeInput($specialOffer);
	$specialOffer = $Helper->createNoFollow($specialOffer);
	
	if(empty($specialOffer) || strlen($specialOffer) > 140){
		$error['status'] = 'Special must be 140 characters or less';
		$error['code'] = 501;
		echo json_encode($error);
		exit();
	} else {
		$xhrType = $_POST['xhr_type'];
		$postType = $_POST['post_type'];
		$expMonth = $_POST['exp_month'];
		$expDay = $_POST['exp_day'];
		$expYear = $_POST['exp_year'];
		if($expMonth != 'MM' && $expDay != 'DD' && $expYear != 'YYYY'){
			$dateString = $expYear .'-'.$expMonth.'-'.$expDay;
		} else {
			$curTime = strtotime("now");
			$dateString = date('Y-m-d',$curTime);
		}
		$specialImg = $_POST['special_img'];
		if(!empty($xhrType) && 
		   !empty($postType) && 
		   !empty($specialOffer) &&
		   !empty($specialImg) &&
		   !empty($dateString)){
			 if($specialImg == 'NULL'){
				$specialImg = 'budvibes-special.png';
			} else {
				$userdir = '../assets/user-images/'.$sessionId.'/';
				$newImg = $Helper->getFileFromFilePath($specialImg);
				$extension = $Helper->getExtension($newImg);
				$newImgPath = $userdir.$newImg;
				$newImgPathSmall = $userdir.'small-'.$newImg;
				$newImgPathOrig = $userdir.'original-special-'.$newImg;
				if(file_exists($newImgPath)){
					$newSpecialPhotos = $Helper->cropStoreSpecialPhoto($userdir,$newImg);
					if($newSpecialPhotos){
						$specialImg = 'special-'.$newImg;
					} else {
						$specialImg = 'budvibes-special.png';
					}
				} else {
					$specailImg = 'budvibes-special.png';
				}
				$StoresCtrl->deleteTempSpecialPhoto($sessionId);
				if(file_exists($newImgPath)){
					rename($newImgPath,$newImgPathOrig);
				}
				if(file_exists($newImgPathSmall)){
					unlink($newImgPathSmall);
				}
			}
			
			$curSpecial = $StoresCtrl->checkCurStoreSpecial($storeId);
			if($curSpecial){
				$curSpecialPhoto = $curSpecial['photo'];
				$updateSpecial = $StoresCtrl->updateSpecial($storeId,
															$specialOffer,
															$specialImg,
															$dateString);
				if($updateSpecial){
					if($curSpecialPhoto != 'budvibes-special.png'){
						$oldSpecialPic = $userdir.$curSpecialPhoto;
						$oldSpecialPicSmall = $userdir.'small-'.$curSpecialPhoto;
						$oldSpecialPicOriginal = $userdir.'original-'.$curSpecialPhoto;
						if(file_exists($oldSpecialPic)){
							unlink($oldSpecialPic);
						}
						if(file_exists($oldSpecialPicSmall)){
							unlink($oldSpecialPicSmall);
						}
						if(file_exists($oldSpecialPicOriginal)){
							unlink($oldSpecialPicOriginal);
						}
					}
				} else {
					$error['status'] = 'Error updating special';
					$error['code'] = 500;
					echo json_encode($error);
					exit();
				}
			} else {
				$newSpecial = $StoresCtrl->addSpecial($storeId,
													  $specialOffer,
													  $specialImg,
													  $dateString);
				if(!$newSpecial){
					$error['status'] = 'Error adding special';
					$error['code'] = 500;
					echo json_encode($error);
					exit();
				}
			}
			
			if($specialImg == 'budvibes-special.png'){
				$specialPhotoPath = __LOCATION__ . '/assets/images/budvibes-special.png';
			} else {
				$specialPhotoPath = __LOCATION__ . '/assets/user-images/'.$sessionId.'/'.$specialImg;
			}
			$specialExp = strtotime($dateString);
			$specialExp = date("F j", $specialExp);
			$newSpecial = array();
			$newSpecial['store_name'] = $storeName;
			$newSpecial['user_id'] = $sessionId;
			$newSpecial['desc'] = $specialOffer;
			$newSpecial['photo'] = $specialPhotoPath;
			$newSpecial['exp'] = $specialExp;
			$newSpecial['code'] = 200;
			echo json_encode($newSpecial);
			exit();
		} else {
			$error['status'] = 'One or more items blank';
			$error['code'] = 500;
			echo json_encode($error);
			exit();
		}
	}
	
	