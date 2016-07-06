<div id="signInForm">
	<div id="signInHead">
		<h3>Sign Up</h3>
	</div>
	<form action="<?php echo __LOCATION__ . '/signup'; ?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="auth_token" />
		<input type="hidden" name="checkmark" value="&#x2713;" />
		<input type="text" class="signInInput" name="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>">
		<input type="text" class="signInInput" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>"/>
		<input type="password" class="signInInput" name="password" placeholder="Password" />
		<input type="password" class="signInInput" name="confirmation" placeholder="Confirm" />
		<button type="submit" class="signInButton" name="register">Submit</button>
		<span class="or">- OR -</span>
		<a class="signUpLink" href="<?php echo __LOCATION__ . '/login';?>">Sign In Here!</a>
	</form>
</div>