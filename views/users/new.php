<?php
	include('../application_views.php');
	include('../../controllers/users_controller.php');
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	
	#FOR PRODUCTION ONLY
	#$UsersCtrl->check_url();
	$UsersCtrl->is_logged_in();
	
	if(isset($_POST['register'])){
		$errors = array();
		$username = trim($_POST['username']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$confirmation = trim($_POST['confirmation']);
		
		if(!empty($username) && !empty($email) && !empty($password) && !empty($confirmation)){
			$errors = $UsersCtrl->validate_user($username,$email,$password,$confirmation);
		} else {
			$errors['empty'] = '1 or more fields empty';
		}
		
	}
	
	$Views->add_head();
	$Views->do_header('message');
?>

	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
		<h1>Sign Up</h1>
		<input type="hidden" name="auth_token" />
		<input type="hidden" name="checkmark" value="&#x2713;" />
		<input type="text" name="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>">
		<input type="text" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>"/>
		<input type="password" name="password" placeholder="Password" />
		<input type="password" name="confirmation" placeholder="Confirm" />
		<button type="submit" name="register">Submit</button>
	</form>
	
	<?php
		echo print_r($errors);
		if(@$errors){
			foreach($errors as $error=>$status){
				echo '<li>'.$status.'</li>';
			}
		}
	?>
<?php
	$Views->do_footer();
?>
