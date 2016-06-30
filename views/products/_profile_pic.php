<div class="userProfilePic">
	<h2 id="usernameHead"><?php echo $product['name']; ?></h2>
	<img id="ajaxUploadImg" src="<?php echo __LOCATION__ . '/assets/images/gears.gif'; ?>" alt="loading..." />
	<?php
		$picLink = __LOCATION__ .'/assets/images/'. __KEYWORD_PLURAL_S .'/'.$product['pic'];
	?>
	<img id="userPicImg" src="<?php echo $picLink; ?>" alt="<?php echo $product['name'].' '. __KEYWORD_CAP_W . ' ' . __KEYWORD_CAP_S; ?>" />
</div>