<?php

	class User extends ApplicationModels{
		
		public $pdo;
		public $Helper;
		public $token;
		private $url;
		private $Controller;
		
		public function __construct(){
			$this->pdo = $this->pdo_conn();
			$this->Controller = new ApplicationCtrl;
			$this->Helper = new ApplicationHelper;
		}
		
		#VERIFY USERNAME
		public function validateUsername($username){
			$errors = $this->checkUniqueUsername($username);
			return $errors;
		}
		#VERIFY EMAIL
		public function validateEmail($email){
			$errors = $this->checkUniqueEmail($email);
			return $errors;
		}
		#VERIFY PASSWORD
		public function validatePassword($password,$confirmation){
			if(strlen($password) > 5){
				if($password == $confirmation){
					$this->errors['success'] = true;
				} else {
					$this->errors['password'] = 'Passwords do not match';
					$this->errors['success'] = false;
				}
			} else {
				$this->errors['password'] = 'Invalid password';
				$this->errors['success'] = false;
			}
			return $this->errors;
		}
		#ADD NEW USER
		public function addUser($username,$slug,$email,$password){
			$this->token = $this->Helper->createToken();
			$newUser = "INSERT INTO users (username,slug, profile_pic, 
			email, type, store_id, store_reg, store_state, 
			password_digest, verified, reg_digest, created_at,updated_at)
			VALUES(:username, :slug, 'no-profile.png',
			:email, 'user', 0, 0, 0, sha1(:password), 0, sha1(:token), NOW(), NOW())";
			$statement = $this->pdo->prepare($newUser);
			$statement->bindValue(':username',$username);
			$statement->bindValue(':email',$email);
			$statement->bindValue(':token', $this->token);
			$statement->bindValue(':password',$password);
			$statement->bindValue(':slug',$slug);
			$statement->execute();
			$newId = $this->pdo->lastInsertId();
			if($newId){
				$newdir = dirname(__DIR__) . '/assets/user-images/'.$newId;
				if(!file_exists($newdir)){
					mkdir($newdir, 0755);
				}
				$addedUser = "SELECT id, username,slug, profile_pic, email, type, store_id, store_reg, store_state, verified 
				FROM users WHERE id=:id";
				$statement = $this->pdo->prepare($addedUser);
				$statement->bindValue(':id',$newId, PDO::PARAM_INT);
				$statement->execute();
				$user = $statement->fetch(PDO::FETCH_ASSOC);
				return $user;
			} else {
				return false;
			}
		}
		#CHECK USER FOR LOGIN
		public function checkUserCredentials($email,$password){
			$userLogin = "SELECT id, username, slug, profile_pic, email, type, 
					 store_id, store_reg, store_state, verified 
					 FROM users WHERE email=:email AND password_digest=sha1(:password)";
			$statement = $this->pdo->prepare($userLogin);
			$statement->bindValue(':email',$email);
			$statement->bindValue(':password',$password);
			$statement->execute();
			$num = $statement->rowCount();
			if($num == 1){
				$user = $statement->fetch(PDO::FETCH_ASSOC);
				return $user;
			} else {
				return false;
			}
		}
		
		public function getUser($user){
			$sql = "SELECT id, username, slug, profile_pic, email, type, 
					store_id, store_reg, store_state, verified 
					FROM users WHERE slug=:user";
					  
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':user', $user);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function generateTotalFeed($id){
			$totalFeed = $this->getTotalUserFeed($id);
			return $totalFeed;
		}
		
		public function generateTotalPosts($id){
			$totalPosts = $this->getTotalUserPosts($id);
			return $totalPosts;
		}
		
		public function generateTotalPhotos($id){
			$totalPhotos = $this->getTotalUserPhotos($id);
			return $totalPhotos;
		}
		
		public function generateTotalVideos($id){
			$totalVideos = $this->getTotalUserVideos($id);
			return $totalVideos;
		}
		
		public function generateTotalFollowers($id){
			$totalFollowers = $this->getTotalUserFollowers($id);
			return $totalFollowers;
		}
		
		public function generateTotalBuds($id){
			$totalBuds = $this->getTotalUserBuds($id);
			return $totalBuds;
		}
		
		public function generateTotalFollowing($id){
			$totalFollowing = $this->getTotalUserFollowing($id);
			return $totalFollowing;
		}
		
		public function getTopStrains(){
			$topStrains = $this->findTopStrains();
			return $topStrains;
		}
		
		public function getTopPosters(){
			$topPosters = $this->findTopPosters();
			return $topPosters;
		}
		
		public function getUserFeed($id){
			$userFeed = $this->findUserFeed($id);
			return $userFeed;
		}
		
		public function getPostsFeed($id){
			$userPosts = $this->findPostsFeed($id);
			return $userPosts;
		}
		
		public function getAjaxFeed($id,$offset){
			$ajaxFeed = $this->ajaxUserFeed($id,$offset);
			return $ajaxFeed;
		}
		
		public function getAjaxPostsFeed($id,$offset){
			$ajaxPosts = $this->ajaxUserPosts($id,$offset);
			return $ajaxPosts;
		}
		
		public function getAjaxUserPhotos($id,$offset){
			$ajaxUserPhotos = "SELECT c.id, c.user_id AS user_comm_id, c.comm_id, 
			c.comm_type, c.comment, c.pic, c.created_at, 
			u.id AS user_id, u.username, u.profile_pic 
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.id 
			WHERE c.comm_id = :id 
			AND c.pic <> 'NULL' 
			ORDER BY c.created_at DESC 
			LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxUserPhotos);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function getAjaxUserVideos($userId,$offset){
			$ajaxUserVideos = "SELECT c.id, c.user_id AS user_comm_id, 
			c.comm_id, c.comm_type, c.comment, c.pic, c.vid, c.created_at, 
		    u.id AS user_id, u.username, u.profile_pic 
		    FROM user_comments c
		    LEFT JOIN users u ON c.comm_id = u.id 
		    WHERE (c.comm_id = :userId AND c.vid <> 'NULL')
		    UNION ALL
		    SELECT p.id, p.user_id AS user_comm_id, p.comm_id, 
			p.comm_type, p.comment, p.pic, p.vid, p.created_at, 
		    u.id AS user_id, u.username, u.profile_pic 
		    FROM prod_comments p
		    LEFT JOIN users u ON p.comm_id = u.id 
		    WHERE (p.user_id = :userId AND p.vid <> 'NULL')
		    ORDER BY created_at DESC
		    LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxUserVideos);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function verifyUserEmail($email){
			$emailCheck = "SELECT email FROM users WHERE email=:email";
			$statement = $this->pdo->prepare($emailCheck);
			$statement->bindValue(':email',$email);
			$statement->execute();
			$count = $statement->rowCount() ? true : false;
		}
		
		public function resetPassword($email){
			$date = new DateTime();
			$newPass = $date->format('U');
			$updatePass = "UPDATE users SET password_digest=sha1(:password) 
			WHERE email=:email";
			$statement = $this->pdo->prepare($updatePass);
			$statement->bindValue(':password',$newPass);
			$statement->bindValue(':email',$email);
			$statement->execute();
			$count = $statement->rowCount() ? true : false;
			if($count){
				return $newPass;
			} else {
				return false;
			}
		}
		
		public function checkRegToken($user,$token){
			$tokenCheck = "SELECT id FROM users 
			WHERE slug=:user AND reg_digest=sha1(:token)";
			$statement = $this->pdo->prepare($tokenCheck);
			$params = array(':user'=>$user, ':token'=>$token);
			$statement->execute($params);
			return $statement->rowCount() ? true : false;
		}
		
		public function verifyNewUser($user){
			$verifyUser = "UPDATE users SET verified=1, updated_at=NOW() 
			WHERE slug=:user";
			$statement = $this->pdo->prepare($verifyUser);
			$statement->bindValue(':user',$user);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function getRecentPosts(){
			$recentPosts = $this->findRecentPosts();
			return $recentPosts;
		}
		
		public function updateUsername($userId,$username,$slug){
			$updateUsername = "UPDATE users 
			SET username=:username, slug=:slug 
			WHERE id=:userId";
			$statement = $this->pdo->prepare($updateUsername);
			$statement->bindValue(':username',$username);
			$statement->bindValue(':slug',$slug);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function updateEmail($userId,$email){
			$updateEmail = "UPDATE users SET email=:email 
			WHERE id=:userId";
			$statement = $this->pdo->prepare($updateEmail);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':email',$email);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function updatePassword($userId,$newPass,$oldPass){
			$updatePassword = "UPDATE users 
			SET password_digest=sha1(:newPass) 
			WHERE id=:userId AND password_digest=sha1(:oldPass)";
			$statement = $this->pdo->prepare($updatePassword);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':newPass',$newPass);
			$statement->bindValue(':oldPass',$oldPass);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findUserFollowers($userId){
			$userFollowers = $this->getUserFollowers($userId);
			return $userFollowers;
		}
		
		public function findUserFollowerCount($userId){
			$followerCount = $this->getUserFollowerCount($userId);
			return $followerCount;
		}
		
		public function findUserFollowing($userId){
			$userFollowing = $this->getUserFollowing($userId);
			return $userFollowing;
		}
		
		public function findUserFollowingCount($userId){
			$followingCount = $this->getUserFollowingCount($userId);
			return $followingCount;
		}
		
		public function insertNewFollowing($followId,$userId){
			$addFollowing = "INSERT INTO relationships 
			VALUES('NULL',:userId, :followId, NOW())";
			$statement = $this->pdo->prepare($addFollowing);
			$statement->bindValue(':followId',$followId,PDO::PARAM_INT);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function deleteUserFollowing($unfollowId,$userId){
			$removeFollowing = "DELETE FROM relationships 
			WHERE follower_id=:userId  
			AND following_id=:unfollowId";
			$statement = $this->pdo->prepare($removeFollowing);
			$statement->bindValue(':unfollowId',$unfollowId,PDO::PARAM_INT);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findRecentUserPics($userId,$limit){
			$recentPics = $this->getRecentUserPics($userId,$limit);
			return $recentPics;
		}
		
		public function findUserBuds($userId){
			$userBuds = "SELECT p.id, p.name, p.type, 
			p.avg_price, p.avg_thc, p.pic,p.descrip
		    FROM products p WHERE p.id 
			IN(SELECT prod_id FROM prod_relationships WHERE user_id=:userId) 
		    ORDER BY p.name ASC";
			$statement = $this->pdo->prepare($userBuds);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findUserBudPics($budId){
			$budPics = "SELECT id, user_id, 
			comm_type,comm_id, pic, created_at 
		    FROM prod_comments 
		    WHERE user_id = :budId  
		    AND pic <> 'NULL' 
		    AND comm_type <> 'rvv' 
		    AND comm_type <> 'svv' 
		    AND comm_type <> 'pvv' 
		    AND comm_type <> 'rvf' 
		    AND comm_type <> 'svf' 
		    AND comm_type <> 'pvf' 
		    AND comm_type <> 'shpvv' 
		    AND comm_type <> 'shpvf' 
		    AND comm_type <> 'shsvf' 
		    AND comm_type <> 'shsvv' 
		    AND comm_type <> 'shpf' 
		    AND comm_type <> 'shrf' 
		    AND comm_type <> 'shsf' 
		    AND comm_type <> 'shpt' 
		    AND comm_type <> 'shrt' 
		    AND comm_type <> 'shst'
		    AND comm_type <> 'shpp' 
		    AND comm_type <> 'shrp' 
		    AND comm_type <> 'shsp' 
		    AND comm_type <> 'shpvf'  
		    AND comm_type <> 'shrvf' 
		    AND comm_type <> 'shsvf' 
		    AND comm_type <> 'shpvv'  
		    AND comm_type <> 'shrvv' 
		    AND comm_type <> 'shsvv' 
		    AND comm_type <> 'shpll' 
		    AND comm_type <> 'shrll' 
		    AND comm_type <> 'shsll' 
		    AND comm_type <> 'shplf'  
		    AND comm_type <> 'shrlf' 
		    AND comm_type <> 'shslf' 
		    AND comm_type <> 'shsmk' 
		    AND comm_type <> 'shfg' 
		    AND comm_type <> 'smk' 
		    AND comm_type <> 'fg' 
		    ORDER BY created_at DESC
		    LIMIT 3";
			$statement = $this->pdo->prepare($budPics);
			$statement->bindValue(':budId',$budId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findUserBySlug($userSlug){
			$findUser = "SELECT u.id, u.username, u.slug, 
			u.profile_pic, u.type, u.store_state, u.store_reg
			FROM users u WHERE u.slug=:userSlug";
			$statement = $this->pdo->prepare($findUser);
			$statement->bindValue(':userSlug',$userSlug);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function getRelation($id){
			$relation = $this->findRelation($id);
			return $relation;
		}
	}
?>