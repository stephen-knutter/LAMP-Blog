<div id="forgotForm">
	<div id="forgotHead">
		<h3>Forgot Password</h3>
	</div>
	<form method="post" action="<?php echo __LOCATION__ . '/forgot'; ?>">
		<input type="hidden" name="authenticity" value="<?php echo $_SESSION['authenticity']; ?>">
		<input type="text" class="forgotInput" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>"/>
		<input type="submit" class="forgotButton" name="forgot" value="Reset Password" />
		<span class="or">- OR -</span>
		<a class="signUpLink" href="<?php echo __LOCATION__ . '/login';?>">Sign In Here!</a>
	</form>
</div>