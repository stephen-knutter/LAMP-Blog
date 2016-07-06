<?php
	$i=1;
	$sessionId = @$_SESSION['logged_in_id'];
	foreach($userFollow as $follower){
		  $username = $follower['username'];
		  $userId = $follower['user_id'];
		  $profilePic = $follower['profile_pic'];
		  $storeState = $follower['store_state'];
		  $storeRegion = $follower['store_reg'];
		  $type = $follower['type'];
		  $slug = $follower['slug'];
		  //PROFILE LINK
		  if($type == 'store'){
			  $userLink = __LOCATION__ . '/dispensary/' . $storeState . '/' . $storeRegion . '/' . $slug;
			  $actionType = 'typeStore-'.$userId; 
		  } else {
			  $userLink = __LOCATION__ . '/' . $slug;
			  $actionType = 'typeUser-'.$userId;
		  }
		  //PIC LINK
		  if($profilePic == 'no-profile.png'){
			  $picLink = __LOCATION__ . '/assets/images/' . $profilePic;
		  } else {
			  $picLink = __LOCATION__ . '/assets/user-images/' . $userId . '/' . $profilePic;
		  }
		  //RIGHT OR LEFT?
		  if($i % 2){
			  echo '<div class="userrelation">';
		  } else {
			  echo '<div class="userrelation" style="margin-left: 4px; float: right;">';
		  }
			echo '<div class="relationImg">';
			echo 	'<img src="'.$picLink.'" alt="'.$username.'">';
			echo '</div>';
			echo '<div class="midSection clearfix">';
			echo 	'<div class="relationBody">';
			echo 		'<p><a href="'.$userLink.'">'.$username.'</a></p>';
			echo 	'</div>';
			echo '<div class="relationPics">';
					if($controller == 'user'){
						$pics = $UsersCtrl->getRecentUserPics($userId);
					} else if($controller == 'store'){
						$pics = $StoresCtrl->getRecentUserPics($userId);
					}
					if($pics){
						foreach($pics as $pic){
							$picCommId = $pic['comm_id'];
							$picUsername = $pic['username'];
							$picPhoto = $pic['pic'];
							$picPhotoLink = __LOCATION__ . '/assets/user-images/'.$picCommId.'/small-'.$picPhoto;
							echo '<img src="'.$picPhotoLink.'" alt="'.$picUsername.'\'s recent post">';
						}
					}
			echo '</div>';
			echo '</div>';
			echo '<div class="userRelationBar clearfix">';	
				 if($controller == 'user'){
					 $relation = $UsersCtrl->checkUserRelation($userId);
				 } else {
					 $relation = $StoresCtrl->checkUserRelation($userId); 
				 }
				 if($relation){
			echo	'<div class="relationButtonWrap" id="'.$actionType.'">';
			echo 		'<span class="unfollow-'.$userId.' relationButton">&minus; Unfollow</span>';
			echo    '</div>';	 
				 } else if($sessionId == $userId) {
			echo	'<div class="whiteBox">';
			echo		'<span class="whiteButton">+</span>';
			echo	'</div>';	 
				 } else {
			echo	'<div class="relationButtonWrap" id="'.$type.'">';
			echo 		'<span class="follow-'.$userId.' relationButton">&#43; Follow</span>';
			echo    '</div>';
				 }
			echo '</div>';
		  echo '</div>';
		  $i++;
	}