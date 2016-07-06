<div class="similarWrap">
	<h3>Similar</h3>
	<?php
	foreach($similarProds as $prod){
		$simName = $prod['name'];
		$simSlug = $prod['slug'];
		$simPic = $prod['pic'];
		$simTags = $prod['tags'];
		$simImgLink = __LOCATION__ . '/assets/images/' . __KEYWORD_PLURAL_S . '/75-'.$simPic;
		$simImgAlt = $simName . ' ' . __KEYWORD_CAP_W . ' ' . __KEYWORD_CAP_S;
		$simHref = __LOCATION__ . '/' . __KEYWORD_PLURAL_S . '/' . $simSlug;
		echo '<div class="similarStrain clearfix">';
		echo 	'<div class="similarPic">';
		echo 		'<img class="similarImg" src="'.$simImgLink.'" alt="'.$simImgAlt.'">';
		echo 	'</div>';
		echo 	'<div class="similarInfo">';
		echo 		'<p class="similarName"><a href="'.$simHref.'">'.$simName.'</a></p>';
		echo 		'<p class="strainUses">'.$simTags.'</p>';
		echo 	'</div>';
		echo '</div>';
	}
	?>
</div>