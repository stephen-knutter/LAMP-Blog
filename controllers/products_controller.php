<?php
	require dirname(__DIR__) . '/models/Product.php';
	
	class ProductsCtrl extends ApplicationCtrl{
		private $StoreModel;
		private $Helper;
		private $Mailer;
		private $Views;
		
		public function __construct(){
			$this->ProductModel = new Product;
			$this->Helper = new ApplicationHelper;
			$this->Mailer = new ApplicationMailer;
			$this->Views = new ApplicationViews;
		}
		
		public function getMenuSuggestions($keyword){
			$menuItems = $this->ProductModel->suggestMenuItems($keyword);
			if($menuItems){
				return $this->Views->generateMenuItemList($menuItems);
			}
		}
		
		public function getProduct($product){
			$product = $this->ProductModel->getProduct($product);
			if(!empty($product)){
				return $product;
			} else {
				header('Location: ' . __LOCATION__);
				exit();
			}
		}
		
		public function generateRelationButtons($id,$product) {
			$relation = $this->ProductModel->getRelation($id);
			if($relation){
				$this->Views->generateProdFollowingButtons($id,$product);			
			} else {
				$this->Views->generateProdFollowerButtons($id,$product);
			}
		}
		
		public function generateUserCountBar($id,$product,$type){
			$url = __LOCATION__ .'/'. __KEYWORD_PLURAL_S .'/'. $product;
			$feedclass = '';
			$photoclass = '';
			$videoclass = '';
			$followerclass = '';
			switch($type){
				case 'feed':
					$feedclass = 'selected';
				break;
				case 'photo':
					$photoclass = 'selected';
				break;
				case 'video':
					$videoclass = 'selected';
				break;
				case 'follower':
					$followerclass = 'selected';
				break;
			}
			$feedCount = $this->ProductModel->generateTotalFeed($id);
			$photoCount = $this->ProductModel->generateTotalPhotos($id);
			$videoCount = $this->ProductModel->generateTotalVideos($id);
			$followerCount = $this->ProductModel->generateTotalFollowers($id);
			
			$this->Views->generateProdCountBar($url,$feedclass,$feedCount,
											   $photoclass,$photoCount,
											   $videoclass,$videoCount,
											   $followerclass,$followerCount);
		}
		
		public function getSimilarProds($tags,$name){
			$tagArray = explode(',',$tags);
			$tagOne = $tagArray[0] ? $tagArray[0] : '';
			$tagTwo = $tagArray[1] ? $tagArray[1] : '';
			$tagThree = $tagArray[2] ? $tagArray[2] : '';
			$tagFour = $tagArray[3] ? $tagArray[3] : '';
			$tagFive = 'none';
			$similarProducts = $this->ProductModel->findSimilarProds($tagOne,$tagTwo,
																     $tagThree,$tagFour,
																     $tagFive,$name);
			return $similarProducts;
		}
		
		public function doTopPosters(){
			$topPosters = $this->ProductModel->getTopPosters();
			$this->Views->doTopPosters($topPosters);
		}
		
		public function generateFeed($feedType,$id,$alt=''){
			switch($feedType){
				case 'product':
					$results = $this->ProductModel->getProductFeed($id);
					if($results){
						$this->Views->generateFeed($results,$feedType);
					}
				break;
				case 'ajax-strainfeed':
					$this->ProductModel->getAjaxStrainFeed();
				break;
			}
		}

		public function doRecent(){
			$recent = $this->ProductModel->getRecentPosts();
			$this->Views->generateRecentPosts($recent);
		}
		
		public function findProductBySlug($prodSlug){
			$prodInfo = $this->ProductModel->findProductBySlug($prodSlug);
			return $prodInfo;
		}
		
		public function checkProdRelation($followId){
			$relation = $this->ProductModel->getProdRelation($followId);
			return $relation;
		}
		
		public function getRecentProdPics($prodId,$limit=2){
			$pics = $this->ProductModel->findRecentProdPics($prodId,$limit);
			return $pics;
		}
	}
	
	
	
	
	
	
	