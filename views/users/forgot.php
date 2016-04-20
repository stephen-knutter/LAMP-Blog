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
	
	@$yes = $_GET['success'];
	
	$UsersCtrl->checkIfLoggedIn();
	
	if(isset($_POST['forgot'])){
		$email = trim($_POST['email']);
		$errors = array();
		if(!empty($email)){
			$errors = $UsersCtrl->checkUserEmail($email);
		} else {
			$errors['blank'] = '1 or more fields blank';
		}
	}
	
	$params = array('title'=>'Forgot Password'.__URLTITLE__, 
					'location'=>'/forgot',
					'meta'=>'Forgot Password | '.__SITENAME__);
	$Views->addHead('custom',$params);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<div id="forgotWrap">
	<?php
		if(!empty($errors)){
			foreach($errors as $error){
				echo '<p class="error">'.$error.'</p>';
			}
		} else if(@$yes == 'yes'){
			echo '<p class="success">new password has been sent</p>';
		}
			
		include '_forgot.php';
	?>
</div>
<?php
	$Views->doFooter();
?>