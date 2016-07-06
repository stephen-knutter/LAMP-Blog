<?php 
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	#FOR PRODUCTION ONLY
	if(__MODE__ == 'PRODUCTION'){
		$UsersCtrl->checkUrl();
	}
	
	$user = $_GET['id'];
	$user = $UsersCtrl->getUser($user);
	
	$Views->addHead('profile',$user);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<div id="editWrap">
	<h3 id="editProfileHead">Edit Profile</h3>
	<div class="userProfileBasic">
		<?php
			if($_SESSION['logged_in_photo'] == 'no-profile.png'){
				$imgLink = __LOCATION__ . '/assets/images/no-profile.png';
			} else {
				$imgLink = __LOCATION__ . '/assets/user-images/'.$user['id'].'/'.$user['profile_pic'];
			}
			echo '<img id="ajaxUploadImg" src="'. __LOCATION__ .'/assets/images/gears.gif" />';
			echo '<img id="userPicImg" src="'.$imgLink.'" />';
		?>
		<form id="changePhotoForm" action="<?php echo __LOCATION__ . '/ajax/ajax_change_profile_pic.php'; ?>" method="post" enctype="multipart/form-data">
			<input type="file" id="changeFileButton" name="photo" />
			<img class="newPic" src="<?php echo __LOCATION__ . '/assets/images/camera-icon.png'; ?>">
		</form>
	</div>
	<div class="editBox clearfix">
		<form id="editUsernameForm" action="<?php echo __LOCATION__ . '/ajax/ajax_change_username.php'?>" method="post">
			<div class="editTextWrap">
				<input type="text" id="new_username" name="new_username" value="<?php echo $user['username']; ?>" placeholder="Username.."/>
			</div>
			<div class="editButtonWrap">
				<input type="submit" id="newNameButton" value="Save" />
			</div>
		</form>
	</div>
	<div class="editBox clearfix">
		<form id="editEmailForm" action="<?php echo __LOCATION__ . '/ajax/ajax_change_email.php'; ?>" method="post">
			<div class="editTextWrap">
				<input type="text" id="new_email" name="new_email" value="<?php echo $user['email']; ?>" placeholder="Email.." />
			</div>
			<div class="editButtonWrap">
				<input type="submit" id="newEmailButton" value="Save" />
			</div>
		</form>
	</div>
	<div class="editBox clearfix">
		<form id="changePassForm" action="<?php echo __LOCATION__ . '/ajax/ajax_change_password.php'?>" method="post">
			<div class="oldPassWrap">
				<input type="password" id="old_pass" name="old_pass" placeholder="Old password.." />
			</div>
			<div class="newPassWrap">
				<input type="password" id="new_pass" name="new_pass" placeholder="New password.." />
			</div>
			<div class="confirmWrap">
				<input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm password.." />
			</div>
			<div class="editButtonWrap">
				<input type="submit" id="changePass" value="Save" />
			</div> 
		</form>
	</div>
</div>
<?php
	$Views->doFooter();
?>