<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/products_controller.php';
	$ProductsCtrl = new ProductsCtrl;
	$keyword = trim($_GET['keyword']);
	if(ob_get_length()) ob_clean();
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');
	header('Content-Type: text/xml');
	echo $ProductsCtrl->getMenuSuggestions($keyword);