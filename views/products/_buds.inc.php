<?php
	$i=0;
	foreach($userBuds as $bud){
		$budId = $bud['id'];
		$budName = $bud['name'];
		$linkName = $Helper->createUrl($budName);
		$budLink = __LOCATION__ . '/strains/'.$linkName;
		$budPic = $bud['pic'];
		$budPicLink = __LOCATION__ . '/assets/images/strains/180-'.$budPic;
		if($i == 3){
			$i = 0;
		}
		if($i != 1){
			$margin = '';
		} else {
			$margin = 'margin-left: 10px; margin-right: 10px';
		}
		echo '<div class="prodInfoWrap clearfix" style="'.$margin.'">';
		echo 	'<h4 class="prodInfoHead"><a href="'.$budLink.'" >'.$budName.'</a></h4>';
		echo 	'<div class="prodPic">';
		echo 		'<img src="'.$budPicLink.'" alt="'.$budName.' weed strain">';
		echo	'</div>';
				$recentPics = $UsersCtrl->getRecentBudPics($budId);
				echo '<div class="strainGallery">';
				if($recentPics){
					foreach($recentPics as $pic){
						$recentPic = $pic['pic'];
						$recentCommId = $pic['comm_id'];
						$recentPicLink = __LOCATION__ . '/user-images/'
						                 .$recentCommId.'/small-'.$recentPic;
						echo 	'<img src="'.$recentPicLink.'" alt="'.$budName.'\'s recent post">';
					}
				}
		echo        '</div>';
		echo '</div>';
		$i++;
	}