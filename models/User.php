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
		
		public function getUserById($userId){
			$sql = "SELECT id, username, slug, profile_pic, email, type, 
					store_id, store_reg, store_state, verified 
					FROM users WHERE id=:userId";
					  
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':userId', $userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function getRelation($id){
			$relation = "SELECT follower_id, following_id 
			FROM relationships 
			WHERE follower_id= :user_id 
			AND following_id= :following_id";
			$statement = $this->pdo->prepare($relation);
			$statement->bindValue(':user_id', @$_SESSION['logged_in_id'], PDO::PARAM_INT);
			$statement->bindValue(':following_id', $id, PDO::PARAM_INT);
			$statement->execute();
			$num = $statement->rowCount();
			return $num ? $num : 0;
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
			return $totalFeed ? $totalFeed : 0;
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
			return $totalPosts ? $totalPosts : 0;
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
			return $totalPhotos ? $totalPhotos : 0;
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
			return $totalVideo ? $totalVideo : 0;
		}
		
		public function generateTotalFollowers($id){
			$followerCount = "SELECT COUNT(*) 
							  FROM users u 
							  WHERE u.id IN(SELECT follower_id FROM relationships WHERE following_id=:id)";
			$statement = $this->pdo->prepare($followerCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalFollowers = $statement->fetchColumn(0);
			return $totalFollowers ? $totalFollowers : 0;
		}
		
		public function generateTotalBuds($id){
			$budCount = "SELECT COUNT(*) 
						 FROM products p 
						 WHERE p.id IN(SELECT prod_id FROM prod_relationships WHERE user_id=:id)";
			$statement = $this->pdo->prepare($budCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalBuds = $statement->fetchColumn(0);
			return $totalBuds ? $totalBuds : 0;
		}
		
		public function generateTotalFollowing($id){
			$followingCount = "SELECT COUNT(*) 
							   FROM users u 
							   WHERE u.id IN(SELECT following_id FROM relationships WHERE follower_id=:id)";
			$statement = $this->pdo->prepare($followingCount);
			$statement->bindValue(':id', $id, PDO::PARAM_INT);
			$statement->execute();
			$totalFollowing = $statement->fetchColumn(0);
			return $totalFollowing ? $totalFollowing : 0;
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
			return $results ? $results : false;
		}
		
		public function getTopPosters(){
			$topPosters = "SELECT u.id, u.username, u.profile_pic, 
			u.type, u.store_id, u.store_reg, u.store_state, c.comm_id
			FROM users u
			LEFT JOIN user_comments c ON u.id=c.comm_id
			GROUP BY c.comm_id 
			ORDER BY RAND() DESC 
			LIMIT 9"; //PREVIOUSLY USED COUNT(*) IN PLACE OF RAND()
			$statement = $this->pdo->prepare($topPosters);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $results ? $results : false;
		}
		
		public function getUserFeed($id){
			$userFeed = "SELECT c.id, c.user_id AS user_comm_id, 
			c.rating, c.comm_type, c.comm_id, c.orig_id, c.comment, 
			c.pic, c.vid, c.tags, c.created_at, u.id AS user_id, 
			u.username, u.profile_pic, u.type, u.store_id, u.store_reg, 
			u.store_state,
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
			pu.id AS user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, 
			pu.store_reg, pu.store_state, pp.id AS prod_id, pp.pic AS prod_pic 
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
			return $results ? $results : false;
		}
		
		public function getPostsFeed($id){
			$userPosts = "SELECT c.id, c.user_id AS user_comm_id, c.rating, c.comm_id, 
		    c.comm_type, c.orig_id, c.comment, c.pic, c.vid, c.tags, c.created_at, 
		    u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, 
			u.store_state, 
		    NULL,NULL 
		    FROM user_comments c
		    LEFT JOIN users u ON c.comm_id = u.id 
		    WHERE c.comm_id IN(SELECT comment_id FROM user_replies WHERE user_id=:id) 
		    OR c.user_id = :id 
		    OR c.comm_id = :id 
		    UNION ALL 
		    SELECT pc.id, pc.user_id AS user_comm_id, pc.rating, pc.comm_id, 
		    pc.comm_type, pc.orig_id, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
		    pu.id AS user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, 
			pu.store_reg, pu.store_state, 
		    pp.id AS prod_id, pp.pic AS prod_pic 
		    FROM prod_comments pc 
		    LEFT JOIN users pu ON pc.comm_id = pu.id 
		    LEFT JOIN products pp ON pc.user_id = pp.id 
		    WHERE 
		    pc.comm_id IN(SELECT user_id FROM prod_replies WHERE user_id=:id) 
		    OR 
		    pc.comm_id = :id 
		    ORDER BY created_at DESC 
		    LIMIT 15";
			$statement = $this->pdo->prepare($userPosts);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function getAjaxFeed($id,$offset){
			$ajaxFeed = "SELECT c.id, c.user_id AS user_comm_id, c.rating, c.comm_id, 
		    c.comm_type, c.orig_id, c.comment, c.pic, c.vid, c.tags, c.created_at,
		    u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, 
			u.store_state,
		    NULL,NULL 
		    FROM user_comments c 
		    LEFT JOIN users u ON c.comm_id = u.id 
		    WHERE 
		    c.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
		    OR c.comm_id = :id 
		    OR c.user_id = :id 
		    UNION ALL 
		    SELECT pc.id, pc.user_id AS user_comm_id, pc.rating, pc.comm_id, 
		    pc.comm_type, pc.orig_id, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
		    pu.id AS user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, 
			pu.store_state, 
		    pp.id AS prod_id, pp.pic AS prod_pic 
		    FROM prod_comments pc 
		    LEFT JOIN users pu ON pc.comm_id = pu.id 
		    LEFT JOIN products pp ON pc.user_id = pp.id 
		    WHERE 
		    pp.id IN(SELECT prod_id FROM prod_relationships WHERE user_id=:id) 
		    OR pc.comm_id IN(SELECT following_id FROM relationships WHERE follower_id=:id) 
		    OR pc.comm_id = :id 
		    ORDER BY created_at DESC 
		    LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxFeed);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function getAjaxPostsFeed($id,$offset){
			$ajaxPosts = "SELECT c.id, c.user_id AS user_comm_id, c.rating, c.comm_id, 
		    c.comm_type, c.orig_id, c.comment, c.pic, c.vid, c.tags, c.created_at, 
		    u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id, u.store_reg, 
			u.store_state, 
		    NULL,NULL 
		    FROM user_comments c
		    LEFT JOIN users u ON c.comm_id = u.id 
		    WHERE c.comm_id IN(SELECT comment_id FROM user_replies WHERE user_id=:id) 
		    OR c.user_id = :id 
		    OR c.comm_id = :id 
		    UNION ALL 
		    SELECT pc.id, pc.user_id AS user_comm_id, pc.rating, pc.comm_id, 
		    pc.comm_type, pc.orig_id, pc.comment, pc.pic, pc.vid, pc.tags, pc.created_at, 
		    pu.id AS user_id, pu.username, pu.profile_pic, pu.type, pu.store_id, pu.store_reg, 
			pu.store_state, 
		    pp.id AS prod_id, pp.pic AS prod_pic 
		    FROM prod_comments pc 
		    LEFT JOIN users pu ON pc.comm_id = pu.id 
		    LEFT JOIN products pp ON pc.user_id = pp.id 
		    WHERE 
		    pc.comm_id IN(SELECT user_id FROM prod_replies WHERE user_id=:id) 
		    OR 
		    pc.comm_id = :id 
		    ORDER BY created_at DESC 
		    LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxPosts);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
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
			$recentPosts = "SELECT c.id, c.user_id AS user_comm_id, 
			c.rating, c.comm_id, 
			c.comm_type, c.orig_id, c.comment, c.pic, 
			c.tags, c.created_at, u.id AS user_id, 
			u.username, u.profile_pic, u.type, 
			u.store_id, u.store_reg, u.store_state,
			NULL,NULL
			FROM user_comments c
			LEFT JOIN users u ON c.comm_id = u.id
			WHERE c.pic <> 'NULL' 
			AND c.comm_type <> 'svf' 
			AND c.comm_type <> 'svv' 
			AND c.comm_type <> 'shpvv' 
			AND c.comm_type <> 'shpvf' 
			AND c.comm_type <> 'shsvf' 
			AND c.comm_type <> 'shsvv' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shpp' 
			AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shpvf'  
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shplf'  
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			UNION ALL
			SELECT pc.id, pc.user_id AS user_comm_id, 
			pc.rating, pc.comm_id, pc.comm_type, 
			pc.orig_id, pc.comment, pc.pic, pc.tags, 
			pc.created_at, pu.id AS user_id, pu.username, 
			pu.profile_pic, pu.type, pu.store_id, pu.store_reg, 
			pu.store_state, pp.id AS prod_id, pp.pic AS prod_pic
			FROM prod_comments pc
			LEFT JOIN users pu ON pc.comm_id = pu.id 
			LEFT JOIN products pp ON pc.user_id = pp.id
			WHERE pc.pic <> 'NULL' 
			AND pc.comm_type <> 'svf' 
			AND pc.comm_type <> 'svv'
			AND pc.comm_type <> 'shpvv' 
			AND pc.comm_type <> 'shpvf' 
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsf' 
			AND pc.comm_type <> 'shst' 
			AND pc.comm_type <> 'shsp'  
			AND pc.comm_type <> 'shsvf' 
			AND pc.comm_type <> 'shsvv' 
			AND pc.comm_type <> 'shsll' 
			AND pc.comm_type <> 'shslf' 
			AND pc.comm_type <> 'shsmk' 
			ORDER BY rand() 
			DESC LIMIT 15";
			$statement = $this->pdo->prepare($recentPosts);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
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
			$userFollowers = "SELECT 
			u.id AS user_id, 
			u.username, 
			u.slug,
			u.profile_pic, 
			u.type, 
			u.store_state, 
			u.store_reg
		    FROM users u
		    WHERE u.id 
			IN(SELECT follower_id FROM relationships WHERE following_id=:userId)";
			$statement = $this->pdo->prepare($userFollowers);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findUserFollowerCount($userId){
			$followerCount = "SELECT COUNT(*) 
			FROM users u 
			WHERE u.id 
			IN(SELECT follower_id FROM relationships WHERE following_id=:userId)";
			$statement = $this->pdo->prepare($followerCount);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}
		
		public function findUserFollowing($userId){
			$userFollowing = "SELECT 
			u.id AS user_id, 
			u.username, 
			u.slug, 
			u.profile_pic, 
			u.type, 
			u.store_state, 
			u.store_reg
		    FROM users u
		    WHERE u.id 
			IN(SELECT following_id FROM relationships WHERE follower_id=:userId)";
			$statement = $this->pdo->prepare($userFollowing);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findUserFollowingCount($userId){
			$followingCount = "SELECT COUNT(*) 
			FROM users u 
			WHERE u.id 
			IN(SELECT following_id FROM relationships WHERE follower_id=:userId)";
			$statement = $this->pdo->prepare($followingCount);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
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
			$recentPics = "SELECT u.username, c.comm_id, 
			c.comm_type, c.orig_id, c.pic, c.created_at 
			FROM user_comments c
			LEFT JOIN users u 
			ON u.id = c.comm_id
			WHERE user_id=:userId  
			AND pic <> 'NULL' 
			AND c.comm_type <> 'rvf' 
			AND c.comm_type <> 'pvf' 
			AND c.comm_type <> 'svf' 
			AND c.comm_type <> 'rvv' 
			AND c.comm_type <> 'pvv' 
			AND c.comm_type <> 'svv' 
			AND c.comm_type <> 'shpvv' 
			AND c.comm_type <> 'shpvf' 
			AND c.comm_type <> 'shsvf' 
			AND c.comm_type <> 'shsvv' 
			AND c.comm_type <> 'shpf' 
			AND c.comm_type <> 'shrf' 
			AND c.comm_type <> 'shsf' 
			AND c.comm_type <> 'shpt' 
			AND c.comm_type <> 'shrt' 
			AND c.comm_type <> 'shst'
			AND c.comm_type <> 'shpp' 
		    AND c.comm_type <> 'shrp' 
			AND c.comm_type <> 'shsp' 
			AND c.comm_type <> 'shpvf'  
			AND c.comm_type <> 'shrvf' 
			AND c.comm_type <> 'shsvf' 
			AND c.comm_type <> 'shpvv'  
			AND c.comm_type <> 'shrvv' 
			AND c.comm_type <> 'shsvv' 
			AND c.comm_type <> 'shpll' 
			AND c.comm_type <> 'shrll' 
			AND c.comm_type <> 'shsll' 
			AND c.comm_type <> 'shplf'  
			AND c.comm_type <> 'shrlf' 
			AND c.comm_type <> 'shslf' 
			AND c.comm_type <> 'shsmk' 
			AND c.comm_type <> 'shfg' 
			AND c.comm_type <> 'smk' 
			AND c.comm_type <> 'fg' 
			ORDER BY c.created_at 
			DESC LIMIT :limit";
			$statement = $this->pdo->prepare($recentPics);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':limit',$limit,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
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
		
		public function getChatStatus($sessionId,$chatWithId){
			$chatStatus = "SELECT id FROM messages 
			WHERE user_one IN(SELECT user_one FROM messages WHERE user_one=:sessionId AND user_two=:chatWithId) 
			OR user_one IN(SELECT user_one FROM messages WHERE user_one=:chatWithId AND user_two=:sessionId) 
			AND parent=0";
			$statement = $this->pdo->prepare($chatStatus);
			$statement->bindValue(':sessionId',$sessionId,PDO::PARAM_INT);
			$statement->bindValue(':chatWithId',$chatWithId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function setChatToRead($sessionId,$parent){
			$setChatRead = "UPDATE messages SET status='r' 
			WHERE parent=:parent  
			AND user_two=:sessionId";
			$statement = $this->pdo->prepare($setChatRead);
			$statement->bindValue(':parent',$parent,PDO::PARAM_INT);
			$statement->bindValue(':sessionId',$sessionId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findChatThread($parent){
			$chatThread = "SELECT m.id, m.parent, m.user_one, 
			m.user_two, m.message_type, m.comm_text, m.pic, m.created_at, 
			u.id AS user_id, u.username, u.profile_pic FROM messages m 
		    INNER JOIN users u ON u.id = m.user_one 
		    WHERE m.id=:parent 
		    OR m.parent=:parent 
			ORDER BY m.created_at DESC";
			$statement = $this->pdo->prepare($chatThread);
			$statement->bindValue(':parent',$parent,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
	}
?>