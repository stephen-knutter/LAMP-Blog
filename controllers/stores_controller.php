<?php
	require dirname(__DIR__) . '/models/Store.php';
	
	class StoresCtrl extends ApplicationCtrl{
		
		private $StoreModel;
		private $Helper;
		private $Mailer;
		private $Views;
		
		public function __construct(){
			$this->StoreModel = new Store;
			$this->Helper = new ApplicationHelper;
			$this->Mailer = new ApplicationMailer;
			$this->Views = new ApplicationViews;
		}
		
		public function getStore($store){
			$store = $this->StoreModel->getStore($store);
			if(!empty($store)){
				return $store;
			} else {
				header('Location: ' . __LOCATION__);
				exit();
			}
		}
		
		public function generateRelationButtons($id,$store) {
			$relation = $this->StoreModel->getRelation($id);
			if($relation){
				$this->Views->generateFollowingButtons($id,$store);			
			} else if(@$_SESSION['logged_in_id'] == $id) {
				$this->Views->generateEditButtons($id,$store);			
			} else {
				$this->Views->generateFollowerButtons($id,$store);
			}
		}
		
		public function generateStoreCountBar($id,
											  $store_id,
			                                  $store,
											  $state,
											  $region,
											  $type){
			$url = __LOCATION__ .'/dispensary/'.$state.'/'.$region.'/'.$store;
			$feedclass = '';
			$postclass = '';
			$photoclass = '';
			$menuclass = '';
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
				case 'menu':
					$menuclass = 'selected';
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
			$feedCount = $this->StoreModel->generateTotalFeed($id);
			$postCount = $this->StoreModel->generateTotalPosts($id);
			$photoCount = $this->StoreModel->generateTotalPhotos($id);
			$videoCount = $this->StoreModel->generateTotalVideos($id);
			$followerCount = $this->StoreModel->generateTotalFollowers($id);
			$menuCount = $this->StoreModel->generateTotalMenu($store_id);
			$followingCount = $this->StoreModel->generateTotalFollowing($id);
			$this->Views->generateStoreCountBar($url,$feedclass,$postclass,$photoclass,
											   $menuclass,$followerclass,$followingclass,$videoclass,
											   $feedCount,$postCount,$photoCount,$videoCount,
											   $followerCount,$menuCount,$followingCount);
		}
		
		public function doTopStrains(){
			$topStrains = $this->StoreModel->getTopStrains();
			$this->Views->doTopStrains($topStrains);
		}
		
		public function doTopPosters(){
			$topPosters = $this->StoreModel->getTopPosters();
			$this->Views->doTopPosters($topPosters);
		}
		
		public function getStoreTime($storeId){
			$times = $this->StoreModel->findStoreTimes($storeId);
			return $times;
		}
		
		public function doRecent(){
			$recent = $this->StoreModel->getRecentPosts();
			$this->Views->generateRecentPosts($recent);
		}
		
		public function generateFeed($feedType,$id,$alt=''){
			switch($feedType){
				case 'feed':
					$results = $this->StoreModel->getUserFeed($id);
					if($results){
						$this->Views->generateFeed($results,$feedType);
					} else {
						return false;
					}
				break;
				case 'ajax-feed':
					$results = $this->StoreModel->getAjaxFeed($id,$alt);
					if($results){
						return $this->Views->generateFeed($results,$feedType,true);
					} else {
						return false;
					}
				break;
				case 'posts':
					$results = $this->StoreModel->getPostsFeed($id);
					if($results){
						$this->Views->generateFeed($results,$feedType);
					} else {
						return false;
					}
				break;
				case 'ajax-posts':
					$results = $this->StoreModel->getAjaxPostsFeed($id,$alt);
					if($results){
						return $this->Views->generateFeed($results,$feedType,true);
					} else {
						return false;
					}
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
		
		public function getUserFollowers($userId){
			$userFollowers = $this->StoreModel->findUserFollowers($userId);
			if($userFollowers){
				return $userFollowers;
			} else {
				return false;
			}
		}
		
		public function getUserFollowersCount($userId){
			$followerCount = $this->StoreModel->findUserFollowerCount($userId);
			return $followerCount;
		}
		
		public function getRecentUserPics($userId,$limit=2){
			$pics = $this->StoreModel->findRecentUserPics($userId,$limit);
			return $pics;
		}
		
		public function checkUserRelation($followId){
			$relation = $this->StoreModel->getRelation($followId);
			return $relation;
		}
		
		public function getUserFollowing($userId){
			$userFollowing = $this->StoreModel->findUserFollowing($userId);
			if($userFollowing){
				return $userFollowing;
			} else {
				return false;
			}
		}
		
		public function getUserFollowingCount($userId){
			$followingCount = $this->StoreModel->findUserFollowingCount($userId);
			return $followingCount;
		}
		
		public function checkMenuCount($storeId){
			$menuCount = $this->StoreModel->getMenuCount($storeId);
			return $menuCount;
		}
		
		public function getStoreAvaliable($storeId){
			$storeAvaliable = $this->StoreModel->findStoreAvailable($storeId);
			return $storeAvaliable;
		}
		
		public function getCurrentSpecial($storeId){
			$curSpecial = $this->StoreModel->findStoreSpecial($storeId);
			return $curSpecial;
		}
		
		public function getStoreInd($storeId){
			$ind = $this->StoreModel->findStoreInd($storeId);
			return $ind;
		}
		
		public function getStoreSat($storeId){
			$sat = $this->StoreModel->findStoreSat($storeId);
			return $sat;
		}
		
		public function getStoreHyb($storeId){
			$hyb = $this->StoreModel->findStoreHyb($storeId);
			return $hyb;
		}
		
		public function getStoreEdb($storeId){
			$edb = $this->StoreModel->findStoreEdb($storeId);
			return $edb;
		}
		
		public function getStoreDrk($storeId){
			$drk = $this->StoreModel->findStoreDrk($storeId);
			return $drk;
		}
		
		public function getStoreWax($storeId){
			$wax = $this->StoreModel->findStoreWax($storeId);
			return $wax;
		}
		
		public function getStoreTin($storeId){
			$tin = $this->StoreModel->findStoreTin($storeId);
			return $tin;
		}
		
		public function getStoreOnt($storeId){
			$ont = $this->StoreModel->findStoreOnt($storeId);
			return $ont;
		}
		
		public function getStoreOth($storeId){
			$oth = $this->StoreModel->findStoreOth($storeId);
			return $oth;
		}
		
		public function addFlwrItems($g,$e,$f,$h,$o,
									 $itemName,
									 $menuType,
									 $prodId,
									 $prodType,
									 $storeId){
			$newItemId = $this->StoreModel->insertFlwrItems($g,$e,$f,$h,$o,
														   $itemName,
														   $menuType,
														   $prodId,
														   $prodType,
														   $storeId);
			return $newItemId;
		}
		
		public function checkStoreProdRelation($storeId,$prodId){
			$relation = $this->StoreModel->getStoreProdRelation($storeId,$prodId);
			return $relation;
		}
		
		public function addStoreProdRelation($storeId,$prodId){
			$newRelation = $this->StoreModel->insertStoreProdRelation($storeId,$prodId);
			return $newRelation;
		}
		
		
		public function addWaxItems($h,$g,
									$itemName,
									$menuType,
									$prodId,
									$prodType,
									$storeId){
			$newItemId = $this->StoreModel->insertWaxItems($h,$g,
									                       $itemName,
														   $menuType,
														   $prodId,
														   $prodType,
														   $storeId);
			return $newItemId;
		}
		
		public function addSingleItems($e,
									   $itemName,
									   $menuType,
									   $prodId,
									   $prodType,
									   $storeId){
			$newItemId = $this->StoreModel->insertSingleItems($e,
														      $itemName,
															  $menuType,
															  $prodId,
															  $prodType,
															  $storeId);
			return $newItemId;
		}
		
		public function updateFlwrItem($menuId,
									   $itemName,
									   $menuType,
									   $g,$e,$f,$h,$o){
			$updateItem = $this->StoreModel->changeFlwrItem($menuId,
									                        $itemName,
									                        $menuType,
									                        $g,$e,$f,$h,$o);
			return $updateItem;
		}
		
		public function updateWaxItem($menuId,
									  $itemName,
									  $menuType,
									  $g,$h){
			$updateItem = $this->StoreModel->changeWaxItem($menuId,
														   $itemName,
														   $menuType,
														   $g,$h);
			return $updateItem;
		}
		
		public function updateSingleItem($menuId,
									     $itemName,
									     $menuType,
									     $e){
			$updateItem = $this->StoreModel->changeSingleItem($menuId,
															  $itemName,
															  $menuType,
															  $e);
			return $updateItem;
		}
		
		public function checkCorrectMenuItem($menuId,$storeId){
			$checkItem = $this->StoreModel->findCorrectMenuItem($menuId,$storeId);
			return $checkItem;
		}
		
		public function deleteMenuItem($menuId){
			$deleteItem = $this->StoreModel->removeMenuItem($menuId);
			return $deleteItem;
		}
		
		public function deleteTempSpecialPhoto($id){
			$deletePhoto = $this->StoreModel->removeTempSpecialPhoto($id);
			return $deletePhoto;
		}
		
		public function checkCurStoreSpecial($storeId){
			$specialCheck = $this->StoreModel->checkStoreSpecial($storeId);
			return $specialCheck;
		}
		
		public function updateSpecial($storeId,
									  $specialOffer,
									  $specialImg,
									  $dateString){
			$updateSpecial = $this->StoreModel->changeStoreSpecial($storeId,
														           $specialOffer,
																   $specialImg,
																   $dateString);
			return $updateSpecial;
		}
		
		public function addSpecial($storeId,
								   $specialOffer,
								   $specialImg,
								   $dateString){
			$addSpecial = $this->StoreModel->insertStoreSpecial($storeId,
															    $specialOffer,
																$specialImg,
																$dateString);
			return $addSpecial;
		}
	}
	
	