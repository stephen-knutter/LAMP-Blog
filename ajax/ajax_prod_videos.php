<?php
require dirname(__DIR__) . '/bv_inc.php';
require dirname(__DIR__) . '/controllers/products_controller.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$ProdCtrl = new ProductsCtrl;
$Views = new ApplicationViews;
$Helper = new ApplicationHelper;

$prodId = (int)$_POST['user'];
$offset = (int)$_POST['start'];

$videos = $ProdCtrl->generateProdVideos($prodId,$offset);
if($videos){
  echo $videos;
  exit();
} else {
  $done['code'] = 201;
  echo json_encode($done);
  exit();
}
