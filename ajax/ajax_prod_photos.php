<?php
  require dirname(__DIR__) . '/bv_inc.php';
  require dirname(__DIR__) . '/controllers/products_controller.php';
  require dirname(__DIR__) . '/vendor/autoload.php';

  $ProdCtrl = new ProductsCtrl;
  $Views = new ApplicationViews;
  $Helper = new ApplicationHelper;

  $prodId = (int)$_POST['user'];
	$offset = (int)$_POST['start'];

  $photos = $ProdCtrl->generateProdPhotos($prodId,$offset);
	if($photos){
	    echo $photos;
		  exit();
	} else {
	    $done['code'] = 201;
		  echo json_encode($done);
		  exit();
	}
