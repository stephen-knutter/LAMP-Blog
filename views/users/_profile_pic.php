<div class="userProfilePic">
	<h2 id="usernameHead"><?php echo $user['username']; ?></h2>
	<img id="ajaxUploadImg" src="<?php echo __LOCATION__ . '/assets/images/gears.gif'; ?>" alt="loading..." />
	<?php
		if($user['profile_pic'] == 'no-profile.png' || !$user['profile_pic']){
			$picLink = __LOCATION__ .'/assets/images/no-profile.png';
		} else {
			$picLink = __LOCATION__ .'/assets/user-images/'.$user['id'].'/'.$user['profile_pic'];
		}
	?>
	<img id="userPicImg" src="<?php echo $picLink; ?>" alt="<?php echo $user['username'].'\'s profile'; ?>" />
	<?php
		if($form){
	?>
		<form id="changePhotoForm" action="change-photo.php" method="post" enctype="multipart/form-data">
			<input type="file" id="changeFileButton" name="photo" />
			<img class="newPic" src="<?php echo __LOCATION__ . '/assets/images/camera-icon.png'?>" alt="photo upload" />
		</form>
	<?php
		}
	?>
</div>
