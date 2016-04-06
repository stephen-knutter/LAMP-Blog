<div id="signInHead">
	<h3>Sign In</h3>
</div>
<div id="signInForm">
	<form method="post" action="<?php echo __LOCATION__ . '/login'; ?>">
		<input type="text" class="signInInput" name="email" placeholder="email" />
		<input type="password" class="signInInput" name="pass" placeholder="password" />
		<input type="submit" class="signInButton" name="sign-in" value="Sign In" />
		<a href="<?php echo __LOCATION__ . '/forgot'; ?>" class="signUpLink">Forgot?</a>
		<span class="or">- OR -</span>
		<a class="signUpLink" href="<?php echo __LOCATION__ . '/signup'; ?>">Sign Up Here!</a>
	</form>
</div>