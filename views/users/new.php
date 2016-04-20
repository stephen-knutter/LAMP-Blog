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
	
	if(isset($_POST['register'])){
		$errors = array();
		@$username = trim($_POST['username']);
		@$email = trim($_POST['email']);
		@$password = trim($_POST['password']);
		@$confirmation = trim($_POST['confirmation']);
		
		if(!empty($username) && !empty($email) && !empty($password) && !empty($confirmation)){
			$errors = $UsersCtrl->validateUser($username,$email,$password,$confirmation);
		} else {
			$errors['empty'] = '1 or more fields empty';
		}
		
	}
	
	$params = array('title'=>'Sign Up'.__URLTITLE__, 
					'location'=>'/signup',
					'meta'=>'Sign up for '.__SITENAME__);
	$Views->addHead('custom',$params);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<div id="signInWrap">
	<?php
		#echo print_r(@$errors);
		if(@$errors){
			foreach($errors as $error=>$status){
				echo '<p class="error">'.$status.'</p>';
			}
		}
		
		include '_sign_up.php';
	?>
</div>
<?php
	$Views->doFooter();
?>
