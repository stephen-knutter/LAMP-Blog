<?php

	class ApplicationCtrl{
		
		private $Helper;
		private $Model;
		
		public function __construct(){
			$this->Helper = new ApplicationHelper;
			$this->Model = new ApplicationModels;
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
	}
?>


