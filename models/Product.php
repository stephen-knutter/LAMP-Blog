<?php

	class Product extends ApplicationModels{
		public $pdo;
		public $Helper;
		private $Controller;

		public function __construct(){
			$this->pdo = $this->pdo_conn();
			$this->Controller = new ApplicationCtrl;
			$this->Helper = new ApplicationHelper;
		}

		public function getAjaxStrainPhotos($id,$offset){
			$query_select = "SELECT p.id, p.user_id AS user_comm_id, p.comm_id,
			p.comm_type, p.comment, p.pic, p.created_at,
		    u.id AS user_id, u.username, u.profile_pic
			FROM prod_comments p
			LEFT JOIN users u ON p.comm_id = u.id
			WHERE p.user_id = :id
			AND p.pic <> 'NULL'
			ORDER BY p.created_at DESC
			LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxUserPhotos);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function getAjaxStrainVideos($userId,$offset){
			$ajaxProdVideos = "SELECT p.id, p.user_id AS user_comm_id,
			p.comm_id, p.comm_type, p.comment, p.pic, p.vid, p.created_at,
		    u.id AS user_id, u.username, u.profile_pic
		    FROM prod_comments p
		    LEFT JOIN users u ON p.comm_id = u.id
		    WHERE p.user_id = :userId
		    AND p.vid <> 'NULL'
		    ORDER BY p.created_at DESC
		    LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxProdVideos);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function suggestMenuItems($keyword){
			$menuSuggest = "SELECT p.id, p.name, p.pic, p.tags
			FROM products p
			WHERE p.name LIKE ?
			LIMIT 7";
			$statement = $this->pdo->prepare($menuSuggest);
			$keyword = '%'.$keyword.'%';
			$statement->execute(array($keyword));
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function getProduct($product){
			$sql = "SELECT id,name,slug,type,split,avg_price,
			avg_thc,pic,descrip,tags FROM products
			WHERE slug=:product LIMIT 1";
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':product',$product);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}

		public function getRelation($id){
			$relation = "SELECT prod_id, user_id
			FROM prod_relationships
			WHERE prod_id=:id
			AND user_id=:sessionId";
			$statement = $this->pdo->prepare($relation);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':sessionId',@$_SESSION['logged_in_id'],PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}

		public function generateTotalFeed($id){
			$feedCount = "SELECT COUNT(*)
			FROM prod_comments c
			WHERE c.user_id =:id";
			$statement = $this->pdo->prepare($feedCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}

		public function generateTotalPhotos($id){
			$photoCount = "SELECT COUNT(*)
			FROM prod_comments p
			WHERE p.user_id =:id
			AND p.pic <> 'NULL'";
			$statement = $this->pdo->prepare($photoCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}

		public function generateTotalVideos($id){
			$videoCount = "SELECT COUNT(*)
			FROM prod_comments
			WHERE user_id=:id AND
			(comm_type='pvv'
			OR comm_type='svv'
			OR comm_type='rvv'
			OR comm_type='pvf'
			OR comm_type='svf'
			OR comm_type='rvf')";
			$statement = $this->pdo->prepare($videoCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}

		public function generateTotalFollowers($id){
			$followerCount = "SELECT COUNT(*)
			FROM users u
			WHERE u.id
			IN(SELECT user_id FROM prod_relationships WHERE prod_id=:id)";
			$statement = $this->pdo->prepare($followerCount);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}

		public function findSimilarProds($tagOne,$tagTwo,
										 $tagThree,$tagFour,
										 $tagFive,$name){
			$similarProducts = "SELECT id,name,slug,pic,tags
			FROM products WHERE
		   (tags LIKE :tagOne AND name <> :name) OR
		   (tags LIKE :tagTwo AND name <> :name) OR
	       (tags LIKE :tagThree AND name <> :name) OR
	       (tags LIKE :tagFour AND name <> :name) OR
	       (tags LIKE :tagFive AND name <> :name)
	       LIMIT 5";
		   $statement = $this->pdo->prepare($similarProducts);
		   $statement->bindValue(':tagOne',"%".$tagOne."%");
		   $statement->bindValue(':tagTwo',"%".$tagTwo."%");
		   $statement->bindValue(':tagThree',"%".$tagThree."%");
		   $statement->bindValue(':tagFour',"%".$tagFour."%");
		   $statement->bindValue(':tagFive',"%".$tagFive."%");
		   $statement->bindValue(':name',$name);
		   $statement->execute();
		   return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : 0;
		}

		public function getTopPosters(){
			$topPosters = $this->findTopPosters();
			return $topPosters;
		}

		public function getProductFeed($id){
			$prodFeed = "SELECT c.id, c.user_id AS user_comm_id,
			c.rating, c.comm_id, c.comm_type, c.orig_id,
			c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id,
			u.store_reg, u.store_state,
			p.id AS prod_id, p.pic AS prod_pic
			FROM prod_comments c
			LEFT JOIN users u ON c.comm_id = u.id
			LEFT JOIN products p ON c.user_id = p.id
			WHERE c.user_id = :id
			ORDER BY created_at DESC
			LIMIT 15";
			$statement = $this->pdo->prepare($prodFeed);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function getAjaxStrainFeed($id,$alt){
			$prodFeed = "SELECT c.id, c.user_id AS user_comm_id,
			c.rating, c.comm_id, c.comm_type, c.orig_id,
			c.comment, c.pic, c.vid, c.tags, c.created_at,
			u.id AS user_id, u.username, u.profile_pic, u.type, u.store_id,
			u.store_reg, u.store_state,
			p.id AS prod_id, p.pic AS prod_pic
			FROM prod_comments c
			LEFT JOIN users u ON c.comm_id = u.id
			LEFT JOIN products p ON c.user_fid = p.id
			WHERE c.user_id = :id
			ORDER BY created_at DESC
			LIMIT :alt, 15";
			$statement = $this->pdo->prepare($prodFeed);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':alt',$alt,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function getRecentPosts(){
			$recentPosts = $this->findRecentPosts();
			return $recentPosts;
		}

		public function getProdRelation($followId){
			$relation = "SELECT prod_id
			FROM prod_relationships
			WHERE prod_id=:follow_id
			AND user_id=:user_id";
			$statement = $this->pdo->prepare($relation);
			$statement->bindValue(':follow_id',$followId,PDO::PARAM_INT);
			$statement->bindValue(':user_id',@$_SESSION['logged_in_id'],PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}

		public function findRecentProdPics($prodId,$limit){
			$pics = "SELECT comm_id, comm_type, orig_id, pic, created_at
			FROM prod_comments
			WHERE user_id=:prodId
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
			AND comm_type <> 'shSvf'
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
			ORDER BY created_at DESC LIMIT :limit";
			$statement = $this->pdo->prepare($pics);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->bindValue(':limit',$limit,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function findProductBySlug($prodSlug){
			$product = "SELECT p.id, p.name, p.slug, p.pic
			FROM products p
			WHERE p.slug=:prodSlug
			LIMIT 1";
			$statement = $this->pdo->prepare($product);
			$statement->bindValue(':prodSlug',$prodSlug);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}

		public function insertNewFollowing($followId,$userId){
				$addFollowing = "INSERT INTO prod_relationships
				VALUES('NULL', :followId, :userId, NOW())";
				$statement = $this->pdo->prepare($addFollowing);
				$statement->bindValue(':followId',$followId,PDO::PARAM_INT);
				$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
				$statement->execute();
				return $statement->rowCount() ? true : false;
		}

		public function deleteProductFollowing($unfollowId,$userId){
			$removeFollowing = "DELETE FROM prod_relationships
			WHERE user_id=:userId AND prod_id=:unfollowId";
			$statement = $this->pdo->prepare($removeFollowing);
			$statement->bindValue(':userId',$userId,PDO::PARAM_INT);
			$statement->bindValue(':unfollowId',$unfollowId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}

		public function getAjaxProdPhotos($id,$offset){
			$ajaxProdPhotos = "SELECT p.id, p.user_id AS user_comm_id,
			p.comm_id, p.comm_type, p.comment, p.pic, p.created_at,
			u.id AS user_id, u.username, u.profile_pic
			FROM prod_comments p
			LEFT JOIN users u ON p.comm_id = u.id
			WHERE p.user_id = :id
			AND p.pic <> 'NULL'
			ORDER BY p.created_at DESC
			LIMIT :offset, 15";
			$statement = $this->pdo->prepare($ajaxProdPhotos);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function getAjaxProdVideos($id,$offset){
			$ajaxProdVideos = "SELECT p.id, p.user_id AS user_comm_id,
			p.comm_id, p.comm_type, p.comment, p.pic, p.vid, p.created_at,
			u.id AS user_id, u.username, u.profile_pic
			FROM prod_comments p
			LEFT JOIN users u ON p.comm_id = u.id
			WHERE p.user_id = :id
			AND p.vid <> 'NULL'
			ORDER BY p.created_at DESC
			LIMIT :offset, 20";
			$statement = $this->pdo->prepare($ajaxProdVideos);
			$statement->bindValue(':id',$id,PDO::PARAM_INT);
			$statement->bindValue(':offset',$offset,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function findProdFollowers($prodId){
			$prodFollowers = "SELECT u.id AS user_id, u.username, u.slug, u.profile_pic,
			u.type, u.store_state, u.store_reg FROM users u
			WHERE u.id IN(SELECT user_id FROM prod_relationships WHERE prod_id=:prodId)";
			$statement = $this->pdo->prepare($prodFollowers);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}

		public function findProdFollowerCount($prodId){
			$followerCount = "SELECT COUNT(*) FROM users u
			WHERE u.id IN(SELECT user_id FROM prod_relationships WHERE prod_id=:prodId)";
			$statement = $this->pdo->prepare($followerCount);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : 0;
		}

		public function findRecentUserPics($userId,$limit){
			$recentPics = $this->getRecentUserPics($userId,$limit);
			return $recentPics;
		}
	}
