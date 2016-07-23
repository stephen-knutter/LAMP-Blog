<?php

	class ApplicationCtrl{
		private $Helper;
		private $Model;

		public function __construct(){
			$this->Helper = new ApplicationHelper;
			$this->Model  = new ApplicationModels;
		}

		public function remove_whitespace($link){
			$link = strtolower($link);
			$link = preg_replace('/\s+/', '-', $link);
			$link = preg_replace('/\&amp\;/', 'and', $link);
			$link = preg_replace('/\&/', 'and', $link);
			$link = preg_replace('/\?/', '', $link);
			$link = preg_replace('/\'/', '', $link);
			$link = preg_replace('/\&39\;/', '', $link);
			return $link;
		}

		public function is_logged_in(){
			if(isset($_SESSION['logged_in_user'])){
				header('Location: '.$_SERVER['LOCATION'].'/'.$_SESSION['logged_in_user']);
				exit();
			}
		}

		/* !!! FOR PRODUCTION ONLY !!! */
		public function checkUrl(){
			if(__MODE__ == 'development'){
				$redirect_url = false;
				$uri = $_SERVER['REQUEST_URI'];
				$host = $_SERVER['HTTP_HOST'];
				if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != off) || $_SERVER['SERVER_PORT'] == 443){
					//CHECK FOR WWW
					if(!preg_match('/www\./', $host)){
						$redirect_url = 'https://www.'.$host;
						if(!empty($uri)){
							$redirect_url .= $uri;
						}
					}
				} else {
					//REDIRECT TO HTTPS://WWW
					if(preg_match('/www\./', $host)){
						$redirect_url = 'https://'.$host;
					} else {
						$redirect_url = 'https://www.'.$host;
					}
					if(!empty($uri)){
						$redirect_url .= $uri;
					}
				}

				if($redirect_url){
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: '.$redirect_url);
					exit();
				}
			}
		}

		public function getSharedUsername($id){
			$username = $this->Model->findSharedUsername($id);
			return $username;
		}

		public function getStrainNameHead($id){
			$strain = $this->Model->findStrainName($id);
			return $strain;
		}

		public function getSecondUsernameHead($id){
			$user = $this->Model->findSecondUsername($id);
			return $user;
		}

		public function getForumThread($id){
			$thread = $this->Model->findForumThread($id);
			return $thread;
		}

		public function getForumBlock($id){
			$title = $this->Model->findBlockTitle($id);
			return $title;
		}

		public function getForumReply($id){
			$forumReply = $this->Model->findForumReply($id);
			return $forumReply;
		}

		public function getForumContent($id){
			$message = $this->Model->findForumContent($id);
			return $message;
		}

		public function getProdReplies($id){
			$replies = $this->Model->findProductReplies($id);
			return $replies;
		}

		public function getUserReplies($id){
			$replies = $this->Model->findUserReplies($id);
			return $replies;
		}

		public function generateProductShareCount($id){
			$shareCount = $this->Model->findProductShares($id);
			return $shareCount;
		}

		public function generateUserShareCount($id){
			$shareCount = $this->Model->findUserShares($id);
			return $shareCount;
		}

		public function getProductReplyCount($id){
			$replyCount = $this->Model->findProductReplyCount($id);
			return $replyCount;
		}

		public function getUserReplyCount($id){
			$replyCount = $this->Model->findUserReplyCount($id);
			return $replyCount;
		}

		public function getMessageCount($id){
			$msgTotal = $this->Model->findUserMessageCount($id);
			return $msgTotal;
		}

		public function getUserMessages($id){
			$userMessages = $this->Model->findUserMessages($id);
			return $userMessages;
		}

		public function getFirstUserMessage($id){
			$userMsg = $this->Model->findUserMessageOne($id);
			return $userMsg;
		}

		public function getSecondUserMessage($id){
			$userMsg = $this->Model->findUserMessageTwo($id);
			return $userMsg;
		}

		public function removeTempPhoto($id){
			$deletePhoto = $this->Model->deleteTempPhoto($id);
			return $deletePhoto;
		}

		public function getTempPic($id){
			$tempPic = $this->Model->findTempPic($id);
			return $tempPic;
		}

		public function addTempPic($id,$photo,$type){
			$tempPic = $this->Model->insertTempPic($id,$photo,$type);
			return $tempPic;
		}

		public function getTempVideo($id){
			$tempVideo = $this->Model->findTempVideo($id);
			return $tempVideo;
		}

		public function removeTempVideo($id){
			$deleteVideo = $this->Model->deleteTempVideo($id);
			return $deleteVideo;
		}

		/*
		 * @PHOTO CROPPING FUNCTIONS
		 **/

		public function cropPhotos($path,$photo){
			$pics = $this->Helper->cropPhoto($path,$photo);
			return $pics;
		}

		public function cropReplyPhoto($path,$photo){
			$pic = $this->Helper->cropReplyPhoto($path,$photo);
			return $pic;
		}

		public function addProdPhotoFull($curWallId,$rating,
										 $type,$userId,
										 $userText,$newPhoto,
										 $newVideo,$tagString){
			$newCommentId = $this->Model->insertProdPhotoFull($curWallId,$rating,
															  												$type,$userId,
															  												$userText,$newPhoto,
															  												$newVideo,$tagString);
			return $newCommentId;
		}

		public function addProdReplyFull($commId,$sessionId,
										 $userText,$newPhoto){
			$newReplyId = $this->Model->insertProdReplyFull($commId,$sessionId,
																											$userText,$newPhoto);
			return $newReplyId;
		}

		public function addUserPhotoFull($curWallId,$rating,
										 $type,$userId,
										 $userText,$newPhoto,
										 $newVideo,$tagString){
			$newCommentId = $this->Model->insertUserPhotoFull($curWallId,$rating,
															  												$type,$userId,
															  												$userText,$newPhoto,
															  												$newVideo,$tagString);
			return $newCommentId;
		}

		public function addUserReplyFull($commId,$sessionId,
										 $userText,$newPhoto){
			$newReplyId = $this->Model->insertUserReplyFull($commId,$sessionId,
										                    							$userText,$newPhoto);
			return $newReplyId;
		}

		public function addUserVideoOnly($curWallId,$rating,
										 								 $type,$userId,
										 							 	 $userText,$newPhoto,
										 							   $newVideo,$tagString){
			$newCommentId = $this->Model->insertUserVideoOnly($curWallId,$rating,
															  												$type,$userId,
										                      							$userText,$newPhoto,
						                                      			$newVideo,$tagString);
			return $newCommentId;
		}

		public function addProdVideoOnly($curWallId,$rating,
								         						 $type,$userId,
										 							   $userText,$newPhoto,
																		 $newVideo,$tagString){
			$newCommentId = $this->Model->insertProdVideoOnly($curWallId,$rating,
															  												$type,$userId,
										                      							$userText,$newPhoto,
																												$newVideo,$tagString);
			return $newCommentId;
		}

		public function generateVideoPic($newVideo,$userId,$newCommentId,$postType){
			$newVideoPic = $this->Helper->addVideoPic($newVideo,$userId);
			if($newVideoPic){
				switch($postType){
					case 'products':
					   $addPic = $this->Model->insertProdVideoPic($newVideoPic,$newCommentId);
					break;
					default:
					   $addPic = $this->Model->insertUserVideoPic($newVideoPic,$newCommentId);
					break;
				}
				if($addPic){
					return true;
				} else {
					return false;
				}
			} else {
				return 'No Pic';
			}
		}

		public function generateNewComment($newCommentId,$xhr,$postType){
			if($postType == 'product'){
				$newComment = $this->Model->findNewProdComment($newCommentId);
			} else {
				$newComment = $this->Model->findNewUserComment($newCommentId);
			}
			if($newComment){
				return $newComment;
			} else {
				return false;
			}
		}

		public function generateNewReply($newReplyId,$xhr,$postType){
			if($postType == 'product'){
				$newReply = $this->Model->findNewProdReply($newReplyId);
			} else {
				$newReply = $this->Model->findNewUserReply($newReplyId);
			}
			return $newReply;
		}

		public function addTempVideo($Userid,$video,$type){
			$tempVideo = $this->Model->insertTempVideo($Userid,$video,$type);
			return $tempVideo;
		}

		public function getTempProfilePic($userId){
			$tempPic = $this->Model->findTempProfilePic($userId);
			return $tempPic;
		}

		public function removeTempProfilePic($userId,$tempPic,$userdir){
			$removeProfilePic = $this->Model->deleteTempProfilePic($userId,$tempPic,$userdir);
			return $removeProfielPic;
		}

		public function getUserProfilePic($userId){
			$curProfilePic = $this->Model->findUserProfilePic($userId);
			return $curProfilePic;
		}

		public function addNewProfilePic($userId,$photo){
			$newProfilePic = $this->Model->insertProfilePic($userId,$photo);
			return $newProfilePic;
		}

		public function doRelationButtons($id){
			$relation = $this->Model->findRelation($id);
			return $relation;
		}

		public function getStoreIdFromUserId($userId){
			$storeId = $this->Model->getStoreId($userId);
			return $storeId;
		}

		public function getStoreRating($storeId){
			$storeRating = $this->Model->getStoreRating($storeId);
			return $storeRating;
		}

		public function updateStoreRating($storeId,$rating,$oldValue,$oldVotes){
			$curValue = (float)($oldValue + $rating);
			$curVotes = (float)($oldVotes + 1);
			if($curValue && $curVotes){
				$updateRating = $this->Model->changeStoreRating($storeId,$curValue,$curVotes);
				return $updateRating;
			} else {
				return false;
			}
		}

		public function addStoreRating($storeId,$rating){
			$insertRating = $this->Model->insertStoreRating($storeId,$rating);
			return $insertRating;
		}

		public function addTags($commentId,$tags){
			if(is_array($tags)){
				foreach($tags as $key=>$tag){
					$tag = trim(strip_tags($tag));
					$tagCheckId = $this->Model->checkForTag($tag);
					if(!$tagCheckId){
						$newTagId = $this->Model->insertNewTag($tag);
						if($newTagId){
							$newTagRelation = $this->Model->newTagRelation($commentId,$newTagId);
						}
					} else {
						$tagId = $tagCheckId['id'];
						$newTagRelation = $this->Model->newTagRelation($commentId,$tagId);
					}
				}
				return true;
			}
			return false;
		}

		public function checkAlreadyRated($sessionId,$userId){
			$rated = $this->Model->checkForRating($sessionId,$userId);
			return $rated;
		}

	}//END APPLICATION CONTROLLER CLASS
?>
