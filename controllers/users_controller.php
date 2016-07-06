<?php
	require dirname(__DIR__) . '/models/User.php';
	
	class UsersCtrl extends ApplicationCtrl{
		private $username;
		private $email;
		private $password;
		private $confirmation;
		private $UserModel;
		private $Helper;
		private $Views;
		private $Mailer;
		private $errors = array();
		
		public function __construct(){
			$this->UserModel = new User;
			$this->Helper    = new ApplicationHelper;
			$this->Mailer    = new ApplicationMailer;
			$this->Views     = new ApplicationViews;
		}
		/*
		 *	# NEW USER FUNCTIONS #
		 *	 @validateUser();
		 *	 @validateUsername();
		 *	 @validateEmail();
		 *	 @validatePassword();
		 *	 @generateFeed();
		**/
		
		public function validateUser($username,$email,$password,$confirmation){
			$this->username = $username;
			$this->slug = $slug;
			$this->email = $email;
			$this->password = $password;
			$this->confirmation = $confirmation;
			
			$nameCheck = $this->validateUsername($username);
			$nameSuccess = $nameCheck['success'];
			if(!$nameSuccess){
				$this->errors['username'] = $nameCheck['username'];
			}
			
			$emailCheck = $this->validateEmail($email);
			$emailSuccess = $emailCheck['success'];
			if(!$emailSuccess){
				$this->errors['email'] = $emailCheck['email'];
			}
			
			$passCheck = $this->validatePassword($password,$confirmation);
			$passSuccess = $passCheck['success'];
			if(!$passSuccess){
				$this->errors['password'] = $passCheck['password'];
			}
			
			if(empty($this->errors)){
				$this->slug = $this->Helper->createUrl($this->username);
				$user = $this->UserModel->addUser($this->username, $this->slug, $this->email, $this->password);
				if($user){
					#SESSIONS AND REDIRECT
					$this->Helper->logIn($user);
					$this->finishSignup($user);
					$this->errors['sending_mail'] = 'Internal error';
					return $this->errors;
					exit();
				} else {
					$this->errors['internal'] = 'Internal error';
					//$this->errors['internal'] = $this->username.'*'.$this->email.'*'.$this->password;
					return $this->errors;
				}
			} else {
				return $this->errors;
			}
		}
		
		public function validateUsername($username){
			return $this->UserModel->validateUsername($username);
		}
		public function validateEmail($email){
			return $this->UserModel->validateEmail($email);
		}
		public function validatePassword($password,$confirmation){
			return $this->UserModel->validatePassword($password, $confirmation);
		}
		
		#SEND MAIL CONFIRMATION TO NEW USER
		public function finishSignup($user){
			$link = $this->Helper->obfuscateLink($this->UserModel->token);
			$subject = 'Complete Sign Up';
			$linkName = $user['slug'];
			$url = __LOCATION__ . "/verify?link=".$link."&user=".$linkName;
			$email = $user['email'];
			$this->Mailer->setMessage($subject,$email,$url);
			if($this->Mailer->sendMail()){
				header('Location: '. __LOCATION__ .'/'. $linkName);
				exit();
			} else {
				return false;
			}
		}
		
		public function checkUserLogin($email,$pass){
			$this->email = $email;
			$this->password = $pass;
			
			$user = $this->UserModel->checkUserCredentials($this->email,$this->password);
			if($user && is_array($user)){
				$this->Helper->logIn($user);
				if($user['type'] == 'store'){
					$url = __LOCATION__ . '/dispensary/'.$user['store_state'].'/'.$user['store_reg'].'/'.$user['slug'];
				} else {
					$url = __LOCATION__ .'/'. $user['slug'];
				}
				
				header('Location: ' . $url);
			} else {
				$this->errors['login'] = 'Incorrect username and password combination';
				return $this->errors;
			}
		}
		
		/*
		 *	###USER SHOW FUNCTIONS###
		 *	 @getUser();
		 *	 @generateRelationButtons();
		 *	 @generateUserCountBar();
		 *	 @doTopStrains();
		 *	 @doTopPosters();
		 *	 @generatePostForm();
		 *	 @generateFeed();
		 *	 @doRecent();
		**/
		public function getUser($user){
			$user = $this->UserModel->getUser($user);
			if(!empty($user) && $user['type'] != 'store'){
				return $user;
			} else {
				header('Location: ' . __LOCATION__);
				exit();
			}
		}
		
		public function generateRelationButtons($id,$user) {
			$relation = $this->UserModel->getRelation($id);
			if($relation){
				$this->Views->generateFollowingButtons($id,$user);			
			} else if(@$_SESSION['logged_in_id'] == $id) {
				$this->Views->generateEditButtons($id,$user);			
			} else {
				$this->Views->generateFollowerButtons($id,$user);
			}
		}
		
		public function checkUserRelation($followId){
			$relation = $this->UserModel->getRelation($followId);
			return $relation;
		}
		
		public function generateUserCountBar($id,$user,$type){
			$url = __LOCATION__ .'/'. $user;
			$feedclass = '';
			$postclass = '';
			$photoclass = '';
			$budclass = '';
			$followerclass = '';
			$followingclass = '';
			$videoclass = '';
			switch($type){
				case 'feed':
					$feedclass = 'selected';
				break;
				case 'post':
					$postclass = 'selected';
				break;
				case 'photo':
					$photoclass = 'selected';
				break;
				case 'bud':
					$budclass = 'selected';
				break;
				case 'follower':
					$followerclass = 'selected';
				break;
				case 'following':
					$followingclass = 'selected';
				break;
				case 'video':
					$videoclass = 'selected';
				break;
			}
			$feedCount = $this->UserModel->generateTotalFeed($id);
			$postCount = $this->UserModel->generateTotalPosts($id);
			$photoCount = $this->UserModel->generateTotalPhotos($id);
			$videoCount = $this->UserModel->generateTotalVideos($id);
			$followerCount = $this->UserModel->generateTotalFollowers($id);
			$budCount = $this->UserModel->generateTotalBuds($id);
			$followingCount = $this->UserModel->generateTotalFollowing($id);
			
			$this->Views->generateUserCountBar($url,$feedclass,$postclass,$photoclass,
											   $budclass,$followerclass,$followingclass,$videoclass,
											   $feedCount,$postCount,$photoCount,$videoCount,
											   $followerCount,$budCount,$followingCount);	
		}
		
		public function doTopStrains(){
			$topStrains = $this->UserModel->getTopStrains();
			$this->Views->doTopStrains($topStrains);
		}
		
		public function doTopPosters(){
			$topPosters = $this->UserModel->getTopPosters();
			$this->Views->doTopPosters($topPosters);
		}
		
		public function generateFeed($feedType,$id,$alt=''){
			switch($feedType){
				case 'feed':
					$results = $this->UserModel->getUserFeed($id);
					if($results){
						$this->Views->generateFeed($results,$feedType);
					} else {
						return false;
					}
				break;
				case 'ajax-feed':
					$results = $this->UserModel->getAjaxFeed($id,$alt);
					if($results){
						return $this->Views->generateFeed($results,$feedType,true);
					} else {
						return false;
					}
				break;
				case 'posts':
					$results = $this->UserModel->getPostsFeed($id);
					if($results){
						$this->Views->generateFeed($results,$feedType);
					} else {
						return false;
					}
				break;
				case 'ajax-posts':
					$results = $this->UserModel->getAjaxPostsFeed($id,$alt);
					if($results){
						return $this->Views->generateFeed($results,$feedType,true);
					} else {
						return false;
					}
				break;
				case 'tags':
					$this->UserModel->doTagsFeed();
				break;
				case 'ajax-search':
					$this->UserModel->doAjaxSearchFeed();
				break;
				case 'forums':
					$this->UserModel->doForumFeed();
				break;
				case 'ajax-forums':
					$this->UserModel->doAjaxForumFeed();
				break;
				case 'ajax-front':
					$this->UserModel->doAjaxFrontFeed();
				break;
				case 'map':
					$this->UserModel->doMapFeed();
				break;
				case 'user-post':
					$this->UserModel->doUserPostFeed();
				break;
				case 'strain-post':
					$this->UserModel->doStrainPostFeed();
				break;
			}
		}
		
		public function generateUserPhotos($id,$alt=''){
			$results = $this->UserModel->getAjaxUserPhotos($id,$alt);
			if($results){
			   $ajaxPhotos = $this->Views->generatePhotos($results,true);
			   return $ajaxPhotos;
		    } else {
			   return false;
			}
		}
		
		public function generateUserVideos($id,$alt=''){
			$results = $this->UserModel->getAjaxUserVideos($id,$alt);
			if($results){
			    $ajaxVideos = $this->Views->generateVideos($results,true);
				return $ajaxVideos;
				exit();
		    } else {
				return false;
				exit();
		    }
		}
		
		public function checkUserEmail($email){
			$verify = $this->UserModel->verifyUserEmail($email);
			if($verify){
				$newPass = $this->UserModel->resetPassword($email);
				if($newPass){
					$subject = 'Forgot Password';
					$this->Mailer->setMessage($subject,$email,$newPass,'reset');
					if($this->Mailer->sendMail()){
						//SUCCESS
						$url = __LOCATION__ . '/forgot?success=yes';
						header('Location: ' . $url);
						exit();
					} else {
						//ERROR
						$errors['internal'] = 'Internal error';
					}
				} else {
					//ERROR
					$errors['internal'] = 'Internal error';
				}
			} else {
				//ERROR
				$errors['invalid'] = 'Email is not registered';
			}
		}
		
		public function updateUsername($userId,$username,$slug){
			$updateUsername = $this->UserModel->updateUsername($userId,$username,$slug);
			return $updateUsername;
		}
		
		public function updateEmail($userId,$email){
			$updateEmail = $this->UserModel->updateEmail($userId,$email);
			return $updateEmail;
		}
		
		public function updatePassword($userId,
		                               $newPass,
									   $oldPass){
			$updatePassword = $this->UserModel->updatePassword($userId,
			                                                   $newPass,
															   $oldPass);
			return $updatePassword;
		}
		
		public function verifyRegToken($user,$token){
			$check = $this->UserModel->checkRegToken($user,$token);
			if($check){
				$this->UserModel->verifyNewUser($user);
				$url = __LOCATION__ .'/'. $user;
				header('Location: ' . $url);
				exit();
			} else {
				header('Location: ' . __LOCATION__);
				exit();
			}
		}
		
		public function getUserFollowers($userId){
			$userFollowers = $this->UserModel->findUserFollowers($userId);
			if($userFollowers){
				return $userFollowers;
			} else {
				return false;
			}
		}
		
		public function getUserFollowersCount($userId){
			$followerCount = $this->UserModel->findUserFollowerCount($userId);
			return $followerCount;
		}
		
		public function addUserFollowing($followId,$userId){
			$addFollowing = $this->UserModel->insertNewFollowing($followId,$userId);
			return $addFollowing;
		}
		
		public function removeUserFollowing($unfollowId,$userId){
			$removeFollowing = $this->UserModel->deleteUserFollowing($unfollowId,$userId);
			return $removeFollowing;
		}
		
		public function getUserFollowing($userId){
			$userFollowing = $this->UserModel->findUserFollowing($userId);
			if($userFollowing){
				return $userFollowing;
			} else {
				return false;
			}
		}
		
		public function getUserFollowingCount($userId){
			$followingCount = $this->UserModel->findUserFollowingCount($userId);
			return $followingCount;
		}
		
		public function getUserBuds($userId){
			$userBuds = $this->UserModel->findUserBuds($userId);
			return $userBuds;
		}
		
		public function getRecentBudPics($budId){
			$budPics = $this->UserModel->findUserBudPics($budId);
			return $budPics;
		}
		
		public function findUserBySlug($userSlug){
			$userInfo = $this->UserModel->findUserBySlug($userSlug);
			return $userInfo;
		}
		
		/*
		 *	###USER SESSION FUNCTIONS###
		 *	@checkIfLoggedIn(); 
		**/
		public function checkIfLoggedIn(){
			if(isset($_SESSION['logged_in_user'])){
				$uri = $this->remove_whitespace($_SESSION['logged_in_user']);
				$url = __LOCATION__ .'/'. $uri;
				header('Location: ' . $url);
			}
		}
		
		public function doRecent(){
			$recent = $this->UserModel->getRecentPosts();
			$this->Views->generateRecentPosts($recent);
		}
		
		public function getRecentUserPics($userId,$limit=2){
			$pics = $this->UserModel->findRecentUserPics($userId,$limit);
			return $pics;
		}

	}//END UserCtrl Class
?>