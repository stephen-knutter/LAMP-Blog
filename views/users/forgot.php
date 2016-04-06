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
			$errors = $this->UsersCtrl->checkUserEmail($email);
		} else {
			$errors['blank'] = '1 or more fields blank';
		}
	}
	
	$Views->addHead();
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<div id="forgotWrap">
		<div id="forgotHead">
			<h3>Forgot Password</h3>
		</div>
		<?php
			if(!empty($errors)){
				foreach($errors as $error){
					echo '<p class="error">'.$error.'</p>';
				}
			} else if(@$yes == 'success'){
				echo '<p class="success">new password has been sent</p>';
			}
			
			include '_forgot.php';
		?>
</div>
<?php
	$Views->doFooter();
?>