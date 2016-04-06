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
			$this->Helper = new ApplicationHelper;
			$this->Views = new ApplicationViews;
			$this->Mailer = new ApplicationMailer;
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
			$this->email = $email;
			$this->password = $password;
			$this->confirmation = $confirmation;
			
			$nameCheck = $this->validateUsername();
			$nameSuccess = $nameCheck['success'];
			if(!$nameSuccess){
				$this->errors['username'] = $nameCheck['username'];
			}
			
			$emailCheck = $this->validateEmail();
			$emailSuccess = $emailCheck['success'];
			if(!$emailSuccess){
				$this->errors['email'] = $emailCheck['email'];
			}
			
			$passCheck = $this->validatePassword();
			$passSuccess = $passCheck['success'];
			if(!$passSuccess){
				$this->errors['password'] = $passCheck['password'];
			}
			
			if(empty($this->errors)){
				$user = $this->UserModel->addUser($this->username, $this->email, $this->password);
				if($user){
					#SESSIONS AND REDIRECT
					$this->Helper->logIn($user);
					$this->finishSignup($user);
					$this->errors['sending_mail'] = 'Internal error';
					return $this->errors;
					exit();
				} else {
					$this->errors['internal'] = 'Internal error';
					return $this->errors;
				}
			} else {
				return $this->errors;
			}
		}
		
		private function validateUsername(){
			return $this->UserModel->validateUsername($this->username);
		}
		private function validateEmail(){
			return $this->UserModel->validateEmail($this->email);
		}
		private function validatePassword(){
			return $this->UserModel->validatePassword($this->password, $this->confirmation);
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
				$url = __LOCATION__ .'/'. $user['slug'];
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
			if(!empty($user)){
				return $user;
			} else {
				header('Location: ' . __LOCATION__);
			}
		}
		
		public function generateRelationButtons($id,$user) {
			$relation = $this->UserModel->getRelation($id);
			if($relation == 1){
				$this->Views->generateFollowingButtons($id,$user);			
			} else if(@$_SESSION['logged_in_id'] == $id) {
				$this->Views->generateEditButtons($id,$user);			
			} else {
				$this->Views->generateFollowerButtons($id,$user);
			}
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
					$this->Views->generateFeed($results,$feedType);
				break;
				case 'posts':
					$this->UserModel->doPostsFeed();
				break;
				case 'ajax-posts':
					$this->UserModel->doAjaxPostsFeed();
				break;
				case 'product':
					$this->UserModel->doProductFeed();
				break;
				case 'ajax-strainfeed':
					$this->UserModel->doAjaxStrainFeed();
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
				case 'ajax-feed':
					$this->UserModel->doAjaxFeed();
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
			
		}
		
	}//END UserCtrl Class
?>