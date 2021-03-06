<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/products_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';

	$ProdCtrl = new ProductsCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;

	$product = $_POST['user'];
	$productSlug = $Helper->createUrl($product);

	$curProduct = $ProdCtrl->findProductBySlug($productSlug);
	if($curProduct){
		$curProdId = $curProduct['id'];
		$relationStatus = $ProdCtrl->checkProdRelation($curProdId);
		$curPics = $ProdCtrl->getRecentProdPics($curProdId,4);
		echo $Views->generateProdToolTip($curProduct,$curPics,$relationStatus);
		exit();
	}
