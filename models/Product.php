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
	}