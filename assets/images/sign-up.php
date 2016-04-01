<?php
	require_once('./check-url.php');
	require('./mac-fns.php');
	include_once('./db-fns.php');
	session_start();
	
	
	if(isset($_SESSION['logged_in_user'])){
		header('Location: https://www.budvibes.com/'.$_SESSION['logged_in_user']);
		exit();
	}
	
if(isset($_POST['signup'])){	
	$conn = db_conn();
	@$username = trim($_POST['username']);
	@$email = $conn->real_escape_string(trim($_POST['email']));
	@$pass = $conn->real_escape_string(trim($_POST['pass']));
	@$pass2 = $conn->real_escape_string(trim($_POST['confirmpass']));
	/*
	if(!($referer = $_POST['back_uri'])){
		$referer = '/';
	}
	*/
	if(!empty($username) && !empty($email) && !empty($pass) && !empty($pass2)){
		$errors = '';
		if(check_username($username)){
			//$username = preg_replace('/\-/',' ',$username); //OLD CHECK UNIQUE USERNAME
			$new_url = create_url($username);
			$username = htmlentities($conn->real_escape_string($username));
			
			//$query_username = "SELECT username FROM users WHERE username='".$username."'"; //OLD CHECK USERNAME QUERY
			$query_username = "SELECT url_name FROM users WHERE url_name='".$new_url."'";
			
			$result_username = $conn->query($query_username);
			if($result_username->num_rows == 1){
				$errors .= '<p class="error">username currently registered</p>';
			}
		} else {
			$errors .= '<p class="error">username must (between 5 & 30 characters)</p>';
		}
		
		if(check_email($email)){
			$query_email = "SELECT email FROM users WHERE email='".$email."'";
			$result_email = $conn->query($query_email);
			if($result_email->num_rows == 1){
				/*$_SESSION['sign_up_error']['email'] = 'Email has been registered';*/
				$errors .= '<p class="error">Email currently registered</p>';
			}
		} else {
			/*$_SESSION['sign_up_error']['email'] = 'Invalid username';*/
			$errors .= '<p class="error">Invalid email</p>';
		}
		
		if(check_password($pass)){
			if($pass != $pass2){
				/*$_SESSION['sign_up_error']['password'] = 'Passwords do not match';*/
				$errors .= '<p class="error">Passwords do not match</p>';
			}
		} else {
			/*$_SESSION['sign_up_error']['password'] = 'Invalid password(between 5 & 50)';*/
			$errors .= '<p class="error">Invalid password</p>';
		}
		
		if(empty($errors)){
			$token = create_token();
			$link = obfuscate_link($token);
			$query_insert = "INSERT INTO users
			VALUES('null', '".$username."', '".$new_url."', 'no-profile.png', '".$email."', 'user', 0, 'NULL', 'NULL', 'n', sha1('".$pass."'), '".$token."', NOW(), NOW())";
			if($conn->query($query_insert)){
				$new_id = $conn->insert_id;
				$newdir = './user-images/'.$new_id.'/';
				mkdir($newdir, 0755);
				$query_select = "SELECT user_id, store_id, username, profile_pic, type, store_reg, store_state, verified FROM users WHERE user_id='".$new_id."'";
				$result = $conn->query($query_select);
				$row = $result->fetch_assoc();
				$session_name = $row['username'];
				$_SESSION['logged_in_id'] = $row['user_id'];
				$_SESSION['logged_in_user'] = $session_name;
				$_SESSION['logged_in_photo'] = $row['profile_pic'];
				$_SESSION['store_id'] = $row['store_id'];
				$_SESSION['store_reg'] = $row['store_reg'];
				$_SESSION['store_state'] = $row['store_state'];
				$_SESSION['user_verified'] = false;
				if($row['type'] == 'store'){
					$_SESSION['store'] = true;
				} else {
					$_SESSION['store'] = false;
				}
				$cook_id = obfuscate_link($row['user_id']);
				$cook_store_id = obfuscate_link($row['store_id']);
				setcookie('logged_in_id', $cook_id, time() + (60 * 60 * 24 * 30));
				setcookie('store_id', $cook_store_id, time() + (60 * 60 * 24 * 30));
				setcookie('store_reg', $row['store_reg'], time() + (60 * 60 * 24 * 30));
				setcookie('store_state', $row['store_state'], time() + (60 * 60 * 24 * 30));
				setcookie('logged_in_user', $session_name, time() + (60 * 60 * 24 * 30));
				setcookie('logged_in_photo', $row['profile_pic'], time() + (60 * 60 * 24 * 30)); // 30 Days
				setcookie('verified', 'n', time() + (60 * 60 * 24 * 30));
				setcookie('store', $_SESSION['store'], time() + (60 * 60 * 24 * 30));
				/*mkdir('./user-images/'.$conn->insert_id.'/');*/
				$subject = 'Complete Sign Up - Budvibes';
				$link_name = remove_whitespace($row['username']);
				$url = "https://www.budvibes.com/complete-signup.php?link=".$link."&user=".$link_name;
				$from = "no-reply@budvibes.com";
				header('Location: https://www.budvibes.com/'.$link_name);
				send_message($subject, $email, $url, $from);
				exit();
			} else {
				/*$_SESSION['sign_up_error']['internal'] = 'Internal error';*/
				$errors .= '<p class="error">Internal error</p>';
			}
		}
	} else {
		/*$_SESSION['sign_up_error']['blank'] = 'One or more fields blank';*/
		$errors .= '<p class="error">One or more fields blank</p>';
	}
}
/*header('Location: '.$referer);*/
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		generate_title('Sign Up | Budvibes', 1);
	?>
	<?php
		add_head('sign', 'https://www.budvibes.com/sign-up', 'sign up for budvibes!', 'https://m.budvibes.com/sign-up')
	?>
</head>
<body>
	<!-- HEADER -->
	<?php
		do_header('message');
	?>

	<?
		//SEARCH BOX, MAPS, USER MENU, LISTINGS
		do_user_menu('../','message');
	?>
	
	<div id="signInWrap">
		<div id="signInHead">
			<h3>Sign Up</h3>
		</div>
		<?php
			if($errors != ''){
				echo $errors;
			}
		?>
		<div id="signInForm">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="text" class="signInInput" name="email" placeholder="email" value="<?php if(isset($email)) echo $email; ?>"/>
				<input type="text"  class="signInInput" name="username" placeholder="username" maxlength="29" value="<?php if(isset($username)) echo htmlentities($username); ?>"/>
				<input type="password" class="signInInput" name="pass" placeholder="password" />
				<input type="password" class="signInInput" name="confirmpass" placeholder="confirm password" />
				<input type="submit" class="signInButton" name="signup" value="Sign Up" />
				<span class="or">- OR -</span>
				<a class="signUpLink" href="https://www.budvibes.com/sign-in">Sign In Here!</a>
			</form>
		</div>
	</div>
</body>
</html>


