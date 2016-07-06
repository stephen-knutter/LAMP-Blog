<div id="store-<?php echo $store['id'].'|'.$store['type']; ?>" class="storeInfoWrap userProfilePic">
	<h2 id="usernameHead" class="storeName"><?php echo $store['username']; ?></h2>
	<img id="ajaxUploadImg" src="<?php echo __LOCATION__ . '/assets/images/gears.gif'; ?>" alt="loading..." />
	<?php
		if($store['profile_pic'] == 'no-profile.png' || !$store['profile_pic']){
	?>
		<?php
			if($store['type'] == 'mdel' || $store['type'] == 'rdel'){
				$picture = 'no-delivery.png';
			} else {
				$picture = 'no-store.png';
			}
		?>
		<img id="userPicImg" src="<?php echo __LOCATION__ . '/assets/images/'.$picture; ?>"  
		alt="<?php $store['username'] . ' weed dispensary'; ?>" />
	<?php
		} else {
	?>
		<img id="userPicImg" src="<?php echo __LOCATION__ . '/assets/user-images/'.$store['user_id'].'/'.$store['profile_pic']; ?>" 
		alt="<?php $store['username'] . ' weed dispensary'; ?>"/>
	<?php
		}
	?>
	<p id="storeAddress"><?php echo $store['address']; ?></p>
	<p id="storePhone"><?php echo $store['phone']; ?></p>
	<p id="storeWebsite"><?php echo $store['website']; ?></p>
	<p id="storeRating"><?php echo $store['votes'] > 0 ? round($store['value']/$store['votes'],2) : 0 ?></p>
	<p id="storeCashType"><?php echo $store['cash_type']; ?></p>
	<?php
		if($form){
	?>
		<form id="changePhotoForm" action="change-photo.php" method="post" enctype="multipart/form-data">
			<input type="file" id="changeFileButton" name="photo" />
			<img class="newPic" src="<?php echo __LOCATION__ . '/assets/images/camera-icon.png'?>" 
			alt="photo upload" />
		</form>
	<?php
		}
	?>
</div>