<?php
	class Store extends ApplicationModels{
		
		public $pdo;
		public $Helper;
		private $Controller;
		
		public function __construct(){
			$this->pdo = $this->pdo_conn();
			$this->Controller = new ApplicationCtrl;
			$this->Helper = new ApplicationHelper;
		}
		
		public function getStore($store){
			$sql = "SELECT u.id AS user_id, u.username, u.slug, 
			u.profile_pic, u.email, u.type AS user_type, u.store_id, 
			u.store_reg, u.store_state, u.verified, 
			s.id, s.address, s.lat, s.lng, s.type, 
			s.picture, s.phone, s.website, s.cash_type, 
			r.store_id, r.value, r.votes 
			FROM users u 
			LEFT JOIN stores s ON u.store_id = s.id 
			LEFT JOIN ratings r ON s.id = r.store_id 
			WHERE u.slug=:store";
			$statement = $this->pdo->prepare($sql);
			$statement->bindValue(':store',$store);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function getRelation($id){
			$relation = $this->findRelation($id);
			return $relation;
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
		
		public function generateTotalMenu($id){
			$totalMenu = $this->getTotalUserMenu($id);
			return $totalMenu;
		}
		
		public function generateTotalFollowing($id){
			$totalFollowing = $this->getTotalUserFollowing($id);
			return $totalFollowing;
		}
		
		public function getTopStrains(){
			$topStrains = $this->findTopStrains();
			return $topStrains;
		}
		
		public function findStoreTimes($storeId){
			$storeTimes = "SELECT * FROM times WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($storeTimes);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function getTopPosters(){
			$topPosters = $this->findTopPosters();
			return $topPosters;
		}
		
		public function getRecentPosts(){
			$recentPosts = $this->findRecentPosts();
			return $recentPosts;
		}
		
		public function getUserFeed($id){
			$feed = $this->findUserFeed($id);
			return $feed;
		}
		
		public function getAjaxFeed($id,$offset){
			$ajaxFeed = $this->ajaxUserFeed($id,$offset);
			return $ajaxFeed;
		}
		
		public function getPostsFeed($id){
			$userPosts = $this->findPostsFeed($id);
			return $userPosts;
		}
		
		public function getAjaxPostsFeed($id,$offset){
			$ajaxPosts = $this->ajaxUserPosts($id,$offset);
			return $ajaxPosts;
		}
		
		public function findUserFollowers($userId){
			$userFollowers = $this->getUserFollowers($userId);
			return $userFollowers;
		}
		
		public function findUserFollowerCount($userId){
			$followerCount = $this->getUserFollowerCount($userId);
			return $followerCount;
		}
		
		public function findRecentUserPics($userId,$limit){
			$recentPics = $this->getRecentUserPics($userId,$limit);
			return $recentPics;
		}
		
		public function findUserFollowing($userId){
			$userFollowing = $this->getUserFollowing($userId);
			return $userFollowing;
		}
		
		public function findUserFollowingCount($userId){
			$followingCount = $this->getUserFollowingCount($userId);
			return $followingCount;
		}
		
		public function getMenuCount($storeId){
			$menuCount = "SELECT COUNT(*) 
			FROM store_menu 
			WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($menuCount);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : false;
		}
		
		public function findStoreAvailable($storeId){
			$storeAvailable = "SELECT smk, tin, other, oin, wax, edb, drk 
			FROM store_available 
			WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($storeAvailable);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreSpecial($storeId){
			$storeSpecial = "SELECT s.id, s.store_id, 
			s.description, s.photo, s.expiration, 
			u.username, u.id AS user_id, u.store_id
			FROM store_specials s 
			INNER JOIN users u ON u.store_id = s.store_id 
			WHERE s.store_id=:storeId";
			$statement = $this->pdo->prepare($storeSpecial);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreInd($storeId){
			$storeInd = "SELECT s.id, s.name, s.store_id, 
			s.prod_id, s.gram, s.eighth, s.fourth, s.half, s.ounce, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='indica'";
			$statement = $this->pdo->prepare($storeInd);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreSat($storeId){
			$storeSat = "SELECT s.id, s.name, s.store_id, 
			s.prod_id, s.gram, s.eighth, s.fourth, s.half, s.ounce, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='sativa'";
			$statement = $this->pdo->prepare($storeSat);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreHyb($storeId){
			$storeHyb = "SELECT s.id, s.name, s.store_id, 
			s.prod_id, s.gram, s.eighth, s.fourth, s.half, s.ounce, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='hybrid'";
			$statement = $this->pdo->prepare($storeHyb);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreEdb($storeId){
			$storeEdb = "SELECT s.id, s.name, s.store_id, s.prod_id, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='edible'";
			$statement = $this->pdo->prepare($storeEdb);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreDrk($storeId){
			$storeDrk = "SELECT s.id, s.name, s.store_id, s.prod_id, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='drink'";
			$statement = $this->pdo->prepare($storeDrk);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreWax($storeId){
			$storeWax = "SELECT s.id, s.name, s.store_id, s.prod_id, s.gram, s.half, 
			s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='wax'";
			$statement = $this->pdo->prepare($storeWax);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreTin($storeId){
			$storeTin = "SELECT s.id, s.name, s.store_id, s.prod_id, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='tincture'";
			$statement = $this->pdo->prepare($storeTin);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreOnt($storeId){
			$storeOnt = "SELECT s.id, s.name, s.store_id, s.prod_id, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='ointment'";
			$statement = $this->pdo->prepare($storeOnt);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findStoreOth($storeId){
			$storeOth = "SELECT s.id, s.name, s.store_id, s.prod_id, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label='other'";
			$statement = $this->pdo->prepare($storeOth);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function insertFlwrItems($g,$e,$f,$h,$o,
										$itemName,
										$menuType,
										$prodId,
										$prodType,
										$storeId){
			$newItem = "INSERT INTO store_menu 
			VALUES('NULL', :itemName, :storeId, 
			:prodId, :g, :e, :f, :h, :o, 
			'NULL', :prodType, :menuType)";
			$statement = $this->pdo->prepare($newItem);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->bindValue(':g',$g);
			$statement->bindValue(':e',$e);
			$statement->bindValue(':f',$f);
			$statement->bindValue(':h',$h);
			$statement->bindValue(':o',$o);
			$statement->bindValue(':prodType',$prodType);
			$statement->bindValue(':menuType',$menuType);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function getStoreProdRelation($storeId,$prodId){
			$relationCheck = "SELECT store_id FROM prod_store_relations 
			WHERE store_id=:storeId  
			AND prod_id=:prodId";
			$statement = $this->pdo->prepare($relationCheck);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function insertStoreProdRelation($storeId,$prodId){
			$newRelation = "INSERT INTO prod_store_relations 
			VALUES('NULL', :storeId, :prodId)";
			$statement = $this->pdo->prepare($newRelation);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function insertWaxItems($h,$g,
									   $itemName,
								       $menuType,
									   $prodId,
									   $prodType,
									   $storeId){
			$newItem = "INSERT INTO store_menu 
			VALUES('NULL', :itemName, :storeId, 
			:prodId, :g, 'NULL', 
			'NULL', :h, 'NULL', 'NULL', 
			:prodType, :menuType)";
			$statement = $this->pdo->prepare($newItem);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->bindValue(':g',$g);
			$statement->bindValue(':h',$h);
			$statement->bindValue(':prodType',$prodType);
			$statement->bindValue(':menuType',$menuType);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function insertSingleItems($e,
										  $itemName,
										  $menuType,
										  $prodId,
										  $prodType,
										  $storeId){
			$newItem = "INSERT INTO store_menu 
			VALUES('NULL', :itemName, :storeId, 
			:prodId, 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 
			:e, :prodType, :menuType)";
			$statement = $this->pdo->prepare($newItem);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':prodId',$prodId,PDO::PARAM_INT);
			$statement->bindValue(':e',$e);
			$statement->bindValue(':prodType',$prodType);
			$statement->bindValue(':menuType',$menuType);
			$statement->execute();
			return $statement->rowCount() ? $this->pdo->lastInsertId() : false;
		}
		
		public function changeFlwrItem($menuId,
									   $itemName,
									   $menuType,
									   $g,$e,$f,$h,$o){
			$itemUpdate = "UPDATE store_menu 
			SET name=:itemName, gram=:g, eighth=:e, 
			fourth=:f, half=:h, ounce=:o, used_for=:menuType  
			WHERE id=:menuId";
			$statement = $this->pdo->prepare($itemUpdate);
			$statement->bindValue(':menuId',$menuId,PDO::PARAM_INT);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':menuType',$menuType);
			$statement->bindValue(':g',$g);
			$statement->bindValue(':e',$e);
			$statement->bindValue(':f',$f);
			$statement->bindValue(':h',$h);
			$statement->bindValue(':o',$o);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function changeWaxItem($menuId,
									  $itemName,
									  $menuType,
									  $g,$h){
			$itemUpdate = "UPDATE store_menu 
			SET name=:itemName, gram=:g, half=:h, used_for=:menuType  
			WHERE id=:menuId";
			$statement = $this->pdo->prepare($itemUpdate);
			$statement->bindValue(':menuId',$menuId,PDO::PARAM_INT);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':menuType',$menuType);
			$statement->bindValue(':g',$g);
			$statement->bindValue(':h',$h);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function changeSingleItem($menuId,
										 $itemName,
										 $menuType,
										 $e){
			$itemUpdate = "UPDATE store_menu 
			SET name=:itemName, single_price=:e, used_for=:menuType  
			WHERE id=:menuId";
			$statement = $this->pdo->prepare($itemUpdate);
			$statement->bindValue(':menuId',$menuId,PDO::PARAM_INT);
			$statement->bindValue(':itemName',$itemName);
			$statement->bindValue(':menuType',$menuType);
			$statement->bindValue(':e',$e);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findCorrectMenuItem($menuId,$storeId){
			$menuCheck = "SELECT COUNT(*) FROM store_menu 
			WHERE id=:menuId AND store_id=:storeId";
			$statement = $this->pdo->prepare($menuCheck);
			$statement->bindValue(':menuId',$menuId,PDO::PARAM_INT);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchColumn(0) : false;
		}
		
		public function removeMenuItem($menuId){
			$deleteItem = "DELETE FROM store_menu WHERE id=:menuId";
			$statement = $this->pdo->prepare($deleteItem);
			$statement->bindValue(':menuId',$menuId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function removeTempSpecialPhoto($id){
			$deletePhoto = $this->deleteTempPhoto($id);
			return $deletePhoto;
		}
		
		public function checkStoreSpecial($storeId){
			$specialCheck = "SELECT photo 
			FROM store_specials WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($specialCheck);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetch(PDO::FETCH_ASSOC) : false;
		}
		
		public function changeStoreSpecial($storeId,
										   $specialOffer,
										   $specialImg,
										   $dateString){
			$updateSpecial = "UPDATE store_specials 
			SET description=:specialOffer, photo=:specialImg, 
			expiration=:dateString, created_at=NOW()  
			WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($updateSpecial);
			$statement->bindValue(':specialOffer',$specialOffer);
			$statement->bindValue(':specialImg',$specialImg);
			$statement->bindValue(':dateString',$dateString);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function insertStoreSpecial($storeId,
										   $specialOffer,
										   $specialImg,
										   $dateString){
			$newSpecial = "INSERT INTO store_specials 
			VALUES('NULL', :storeId, :specialOffer, 
			:specialImg, :dateString, NOW())";
			$statement = $this->pdo->prepare($newSpecial);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':specialOffer',$specialOffer);
			$statement->bindValue(':specialImg',$specialImg);
			$statement->bindValue(':dateString',$dateString);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function insertNewTimes($storeId,
								       $mondayOpen,$mondayClose,
								       $tuesdayOpen,$tuesdayClose,
								       $wednesdayOpen,$wednesdayClose,
								       $thursdayOpen,$thursdayClose,
								       $fridayOpen,$fridayClose,
								       $saturdayOpen,$saturdayClose,
								       $sundayOpen,$sundayClose){
			$newTimes = "UPDATE times SET mon_o=:mondayOpen, mon_c=:mondayClose, 
			tue_o=:tuesdayOpen, tue_c=:tuesdayClose, 
			wed_o=:wednesdayOpen, wed_c=:wednesdayClose, 
			thu_o=:thursdayOpen, thu_c=:thursdayClose, 
			fri_o=:fridayOpen, fri_c=:fridayClose, 
			sat_o=:saturdayOpen, sat_c=:saturdayClose, 
			sun_o=:sundayOpen, sun_c=:sundayClose 
			WHERE store_id=:storeId";
			$statement = $this->pdo->prepare($newTimes);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':mondayOpen',$mondayOpen);
			$statement->bindValue(':tuesdayOpen',$tuesdayOpen);
			$statement->bindValue(':wednesdayOpen',$wednesdayOpen);
			$statement->bindValue(':thursdayOpen',$thursdayOpen);
			$statement->bindValue(':fridayOpen',$fridayOpen);
			$statement->bindValue(':saturdayOpen',$saturdayOpen);
			$statement->bindValue(':sundayOpen',$sundayOpen);
			$statement->bindValue(':mondayClose',$mondayClose);
			$statement->bindValue(':tuesdayClose',$tuesdayClose);
			$statement->bindValue(':wednesdayClose',$wednesdayClose);
			$statement->bindValue(':thursdayClose',$thursdayClose);
			$statement->bindValue(':fridayClose',$fridayClose);
			$statement->bindValue(':saturdayClose',$saturdayClose);
			$statement->bindValue(':sundayClose',$sundayClose);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		#VERIFY USERNAME
		public function validateUsername($username){
			$errors = $this->checkUniqueUsername($username);
			return $errors;
		}
		
		public function changeUsername($username,$slug,$userId,$storeId){
			$updateUser = "UPDATE users 
			SET username=:username, slug=:slug  
			WHERE id=:userId";
			$stmUser = $this->pdo->prepare($updateUser);
			
			$updateStore = "UPDATE stores 
			SET name=:username  
			WHERE id=:storeId";
			$stmStore = $this->pdo->prepare($updateStore);
			
			$this->pdo->beginTransaction();
			
			$stmUser->bindValue(':username',$username);
			$stmUser->bindValue(':slug',$slug);
			$stmUser->bindValue(':userId',$userId,PDO::PARAM_INT);
			$stmUser->execute();
			
			$stmStore->bindValue(':username',$username);
			$stmStore->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$stmStore->execute();
			
			return $this->pdo->commit() ? true : false;
		}
		
		#VALIDATE EMAIL
		public function validateEmail($email){
			$errors = $this->checkUniqueEmail($email);
			return $errors;
		}
		
		public function changeEmail($userId,$storeId,$email){
			$updateUser = "UPDATE users 
			SET email=:email  
			WHERE id=:userId";
			$stmUser = $this->pdo->prepare($updateUser);
			
			$updateStore = "UPDATE stores 
			SET email=:email  
			WHERE id=:storeId";
			$stmStore = $this->pdo->prepare($updateStore);
			
			$this->pdo->beginTransaction();
			
			$stmUser->bindValue(':userId',$userId,PDO::PARAM_INT);
			$stmUser->bindValue(':email',$email);
			$stmUser->execute();
			
			$stmStore->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$stmStore->bindValue(':email',$email);
			$stmStore->execute();
			
			return $this->pdo->commit() ? true : false;
		}
		
		public function changeWebsite($storeId,$website){
			$updateWebsite = "UPDATE stores 
			SET website=:website  
			WHERE id=:storeId";
			$statement = $this->pdo->prepare($updateWebsite);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':website',$website);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function changePhone($storeId,$phone){
			$updatePhone = "UPDATE stores 
			SET phone=:phone  
			WHERE id=:storeId";
			$statement = $this->pdo->prepare($updatePhone);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':phone',$phone);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function changeStoreType($storeId,$type,$pic){
			$updateType = "UPDATE stores 
			SET type=:type, picture=:pic  
			WHERE id=:storeId";
			$statement = $this->pdo->prepare($updateType);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->bindValue(':pic',$pic);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function changeCashType($storeId,$cash){
			$updateCash = "UPDATE stores 
			SET cash_type=:cash 
			WHERE id=:storeId";
			$statement = $this->pdo->prepare($updateCash);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':cash',$cash);
			$statement->execute();
			return $statement->rowCount() ? true : false;
		}
		
		public function findMenuItems($storeId,$type){
			$menuItems = "SELECT s.id, s.name, s.store_id, 
			s.prod_id, s.gram, s.eighth, s.fourth, s.half, s.ounce, 
			s.single_price, s.prod_label, s.used_for 
			FROM store_menu s 
			WHERE store_id=:storeId  
			AND s.prod_label=:type";
			$statement = $this->pdo->prepare($menuItems);
			$statement->bindValue(':storeId',$storeId,PDO::PARAM_INT);
			$statement->bindValue(':type',$type);
			$statement->execute();
			return $statement->rowCount() ? $statement->fetchAll(PDO::FETCH_ASSOC) : false;
		}
		
		public function findBasicProdInfo($prodId){
			$prodInfo = $this->doBasicProdInfo($prodId);
			return $prodInfo;
		}
	}