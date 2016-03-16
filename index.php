<?php
	include('controllers/application_controller.php');
	include('views/application_views.php');
	$app = new ApplicationCtrl;
	$views = new ApplicationViews;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Budvibes</title>
	<meta charset="utf-8" />
</head>
<body>
	<h1>
		<?php $msg = $app->Hi(); echo $msg; ?>
	</h1>
	<a href="/signup">Sign Up</a>
	<a href="/login">Log In</a>
</body>
</html>


