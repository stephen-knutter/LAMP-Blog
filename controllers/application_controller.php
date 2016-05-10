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
		
		public function addProdPhotoFull($curWallId,$rating,
										 $type,$userId,
										 $userText,$newPhoto,
										 $tagString){
			$newCommentId = $this->Model->insertProdPhotoFull($curWallId,$rating,
															  $type,$userId,
															  $userText,$newPhoto,
															  $newVideo,$tagString);
			return $newCommentId;
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
		
		public function addUserVideoOnly($curWallId,$rating,
								         $type,$userId,
										 $userText,$newVideo,
						                 $tagString){
			$newCommentId = $this->Model->insertUserVideoOnly($curWallId,$rating,
															  $type,$userId,
										                      $userText,$newVideo,
						                                      $newVideo,$tagString);
			return $newCommentId;
		}
		
		public function addProdVideoOnly($curWallId,$rating,
								         $type,$userId,
										 $userText,$newVideo,
						                 $tagString){
			$newCommentId = $this->Model->insertProdVideoOnly($curWallId,$rating,
															  $type,$userId,
										                      $userText,$newVideo,
						                                      $tagString);
			return $newCommentId;
		}
		
		public function generateVideoPic($newVideo,
		                                 $userId,
									     $newCommentId,
										 $postType){
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
		
	}//END APPLICATION CONTROLLER CLASS
?>


