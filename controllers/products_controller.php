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
		
		
	}
	
	
	
	
	
	
	