<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	#FOR PRODUCTION ONLY
	if(__MODE__ == 'PRODUCTION'){
		$UsersCtrl->checkUrl();
	}
	
	$UsersCtrl->checkIfLoggedIn();
	
	if(isset($_POST['sign-in'])){
		$errors = array();
		$email = trim($_POST['email']);
		$password = trim($_POST['pass']);
		if(!empty($email) && !empty($password)){
			$errors = $UsersCtrl->checkUserLogin($email,$password);
		} else {
			$errors['blank'] = '1 or more fields blank';
		}
	}
	
	$params = array('title'=>'Log In'.__URLTITLE__, 
					'location'=>'/login',
					'meta'=>'Log In to '.__SITENAME__);
	$Views->addHead('custom',$params);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<div id="signInWrap">
	<?php
		if(!empty($errors)){
			foreach($errors as $error){
				echo '<p class="error">'.$error.'</p>';
			}
		}
		
		include '_log_in.php';
	?>
</div>
<?php
	$Views->doFooter();
?>