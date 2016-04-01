<?php
	#include('../application_views.php');
	#include('../../controllers/users_controller.php');
	
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
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
<div id="signInWrap">
	<?php
		#echo print_r(@$errors);
		if(@$errors){
			foreach($errors as $error=>$status){
				echo '<p class="error">'.$status.'</p>';
			}
		}
	?>
	<div id="signInForm">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
			<div id="signInHead">
				<h3>Sign Up</h3>
			</div>
			<input type="hidden" name="auth_token" />
			<input type="hidden" name="checkmark" value="&#x2713;" />
			<input type="text" class="signInInput" name="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>">
			<input type="text" class="signInInput" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>"/>
			<input type="password" class="signInInput" name="password" placeholder="Password" />
			<input type="password" class="signInInput" name="confirmation" placeholder="Confirm" />
			<button type="submit" class="signInButton" name="register">Submit</button>
			<span class="or">- OR -</span>
			<a class="signUpLink" href="https://www.budvibes.com/sign-in">Sign In Here!</a>
		</form>
	</div>
</div>
<?php
	$Views->do_footer();
?>
