<?php

	class User extends ApplicationModels{
		
		public $pdo;
		public $token;
		private $url;
		private $Controller;
		private $Helper;
		private $errors = array();
		
		public function __construct(){
			$this->pdo = $this->pdo_conn();
			$this->Controller = new ApplicationCtrl;
			$this->Helper = new ApplicationHelper;
		}
		
		#VERIFY USERNAME
		public function validateUsername($username){
			if( (strlen($username) >= 4) && (strlen($username) <=29) ){
				
				#$reserved_words = array("sign-up", "sign-in", "privacy", "terms", "claim", "finish", "tags", "users", "weed", "stores", 
				#"forums", "profile", "posts", "photos", "videos", "buds", "followers", "following", "edit", "menu", "forgot");
				$reserved_words = array();
				if(in_array($username, $reserved_words)){
					$this->$errors['username'] = 'Invalid username';
					$this->$errors['success'] = false;
				} else {
					$regex = "/^[a-zA-Z0-9 \-\'\&]+$/";
					if(!preg_match($regex, $username)){
						$this->errors['username'] = 'Invalid username';
						$this->errors['success'] = false;
					} else {
						$this->url = $this->create_url($username);
						$usernameCheck = "SELECT slug FROM users WHERE slug=:slug";
						$statement = $this->pdo->prepare($usernameCheck);
						$statement->bindValue(':slug',$this->url);
						$statement->execute();
						$num = $statement->rowCount();
						if($num){
							$this->errors['username'] = 'Username already in use';
							$this->errors['success'] = false;
						} else {
							$this->errors['success'] = true;
						}
					}
				}
			} else {
				$this->errors['username'] = 'Username must be 4 to 29 characters';
				$this->errors['success'] = false;
			}
			return $this->errors;
		}
		#VERIFY EMAIL
		public function validateEmail($email){
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emailCheck = "SELECT email FROM users WHERE email=:email";
				$statement = $this->pdo->prepare($emailCheck);
				$statement->bindValue(':email',$email);
				$statement->execute();
				$num = $statement->rowCount();
				if($num){
					$this->errors['email'] = 'Email has been registered';
					$this->errors['success'] = false;
				} else {
					$this->errors['success'] = true;
				}
			} else {
				$this->errors['email'] = 'Invalid email';
				$this->errors['success'] = false;
			}
			return $this->errors;
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
		public function addUser($username,$email,$password){
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
			$statement->bindValue(':slug',$this->url);
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
			$num = $statement->rowCount();
			
			if($num > 0){
				$user = $statement->fetch(PDO::FETCH_ASSOC);
				return $user;
			} else {
				return false;
			}
		}
		
		public function getRelation($id){
			$sql = "SELECT follower_id, following_id
					FROM relationships 
					WHERE follower_id= :user_id 
					AND following_id= :following_id";
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':user_id', @$_SESSION['logged_in_id'], PDO::PARAM_INT);
			$statement->bindValue(':following_id', $id, PDO::PARAM_INT);
			$statement->execute();
			$num = $statement->rowCount();
			return $num;
		}
		
		
		public function generateTotalFeed($id){
			
			//FEED POSTS
			$feedCount = "SELECT COUNT(*) 
			FROM user_comments c 
			WHERE c.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
			OR c.comm_id=:id
			OR c.user_id=:id";
			$statement = $this->pdo->prepare($feedCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$feedcount = $statement->fetchColumn(0);
			//PROD POSTS
			$prodCount = "SELECT COUNT(*) 
			FROM prod_comments p 
			WHERE p.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
			OR p.comm_id=:id";
			$statement = $this->pdo->prepare($prodCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$prodcount = $statement->fetchColumn(0);
			//PRODUCT AND FEED COUNT
			$totalFeed = $feedcount + $prodcount;
			return $totalFeed;
		}
		
		public function generateTotalPosts($id){
			$postCount = "SELECT COUNT(*) 
						  FROM user_comments c 
						  WHERE c.comm_id IN(SELECT comment_id FROM user_replies WHERE user_id=:id) 
						  OR c.user_id = :id 
						  OR c.comm_id = :id";
			$statement = $this->pdo->prepare($postCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$postcount = $statement->fetchColumn(0);
	
			$postProdCount = "SELECT COUNT(*) 
							  FROM prod_comments pc
							  WHERE pc.comm_id IN(SELECT user_id FROM prod_replies WHERE user_id=:id)
							  OR 
							  pc.comm_id = :id";
			$statement = $this->pdo->prepare($postProdCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$postprodcount = $statement->fetchColumn(0);
	
			$totalPosts = $postcount + $postprodcount;
			return $totalPosts;
		}
		
		public function generateTotalPhotos($id){
			$photoCount = "SELECT COUNT(*) 
						   FROM user_comments  
						   WHERE comm_id=:id 
						   AND (comm_type='pf'
						   OR comm_type='sf' 
						   OR comm_type='rf' 
						   OR comm_type='pp' 
						   OR comm_type='sp' 
						   OR comm_type='rp')";
			$statement = $this->pdo->prepare($photoCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalPhotos = $statement->fetchColumn(0);
			return $totalPhotos;
		}
		
		public function generateTotalVideos($id){
			$videoCount = "SELECT COUNT(*) 
						   FROM user_comments 
						   WHERE comm_id=:id 
						   AND (comm_type='pvv' 
						   OR comm_type='svv' 
						   OR comm_type='rvv' 
						   OR comm_type='pvf' 
						   OR comm_type='svf' 
						   OR comm_type='rvf')";
			$statement = $this->pdo->prepare($videoCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalVideo = $statement->fetchColumn(0);
			return $totalVideo;
		}
		
		public function generateTotalFollowers($id){
			$followerCount = "SELECT COUNT(*) 
							  FROM users u 
							  WHERE u.id IN(SELECT follower_id FROM relationships WHERE following_id=:id)";
			$statement = $this->pdo->prepare($followerCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalFollowers = $statement->fetchColumn(0);
			return $totalFollowers;
		}
		
		public function generateTotalBuds($id){
			$budCount = "SELECT COUNT(*) 
						 FROM products p 
						 WHERE p.id IN(SELECT prod_id FROM prod_relationships WHERE user_id=:id)";
			$statement = $this->pdo->prepare($budCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalBuds = $statement->fetchColumn(0);
			return $totalBuds;
		}
		
		public function generateTotalFollowing($id){
			$followingCount = "SELECT COUNT(*) 
							   FROM users u 
							   WHERE u.id IN(SELECT following_id FROM relationships WHERE follower_id=:id)";
			$statement = $this->pdo->prepare($followingCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalFollowing = $statement->fetchColumn(0);
			return $totalFollowing;
		}
		
		public function getTopStrains(){
			$topStrains = "SELECT pr.prod_id, pr.user_id, p.id, p.name, p.pic 
						   FROM prod_relationships pr
						   INNER JOIN products p ON pr.prod_id = p.id
						   GROUP BY prod_id 
						   ORDER BY RAND() DESC
					       LIMIT 11"; //PREVIOUSLY USED COUNT(*) IN PLACE OF RAND()
			$statement = $this->pdo->prepare($topStrains);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
		
		public function getTopPosters(){
			$topPosters = "SELECT u.id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state, 
			c.comm_id
			FROM users u
			LEFT JOIN user_comments c ON u.id=c.comm_id
			GROUP BY c.comm_id 
			ORDER BY RAND() DESC 
			LIMIT 9"; //PREVIOUSLY USED COUNT(*) IN PLACE OF RAND()
			$statement = $this->pdo->prepare($topPosters);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
		
		public function getUserFeed($id){
			$userFeed = "SELECT c.id, c.user_id AS user_comm_id, c.rating, c.comm_type, 
			c.comm_id, c.orig_id, c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, u.store_state,
			NULL,NULL 
			FROM user_comments c 
			LEFT JOIN users u ON c.comm_id = u.id 
			WHERE 
			c.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
			OR c.comm_id=:id 
			OR c.user_id=:id 
			UNION ALL 
			SELECT pc.id, pc.user_id AS user_comm_id, pc.rating, pc.comm_type, 
			pc.comm_id, pc.orig_id, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
			pu.id AS user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, pu.store_state, 
			pp.id AS prod_id, pp.pic AS prod_pic 
			FROM prod_comments pc 
			LEFT JOIN users pu ON pc.comm_id = pu.id 
			LEFT JOIN products pp ON pc.user_id = pp.id 
			WHERE 
			pp.id IN(SELECT prod_id FROM prod_relationships WHERE user_id=:id) 
			OR pc.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
			OR pc.comm_id=:id 
			ORDER BY created_at DESC LIMIT 15";
			$statement = $this->pdo->prepare($userFeed);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
		
		public function verifyUserEmail($email){
			$emailCheck = "SELECT email FROM users WHERE email=:email";
			$statement = $this->pdo->prepare($emailCheck);
			$statement->bindValue(':email',$email);
			$statement->execute();
			$count = $statement->rowCount();
			if($count == 1){
				return true;
			} else {
				return false;
			}
		}
		
		public function resetPassword($email){
			$date = new DateTime();
			$newPass = $date->format(U);
			$updatePass = "UPDATE users SET password=sha1(:password) 
			WHERE email=:email";
			$statement = $this->pdo->prepare($updatePass);
			$statement->bindValue(':password',$newPass);
			$statement->bindValue(':email',$email);
			$statement->execute();
			if($statement->rowCount()){
				return $newPass;
			} else {
				return false;
			}
		}
	}
?>