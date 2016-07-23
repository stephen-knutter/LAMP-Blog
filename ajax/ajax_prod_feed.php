<?php
require dirname(__DIR__) . '/bv_inc.php';
require dirname(__DIR__) . '/controllers/products_controller.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$ProdCtrl = new ProductsCtrl;
$Views = new ApplicationViews;
$Helper = new ApplicationHelper;


$prodId = (int)$_POST['user'];
$offset = (int)$_POST['start'];
$type = $_POST['type'];
$word = $_POST['word'];

switch($type){
  case 'product':
    $feed = $ProdCtrl->generateFeed('ajax-product',$prodId,$offset);
    if($feed){
      echo $feed;
      exit();
    } else {
      $done['code'] = 201;
      echo json_encode($done);
      exit();
    }
  break;
}
