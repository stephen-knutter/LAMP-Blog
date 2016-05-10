<?php
	include 'bv_inc.php';
	
	$Helper = new ApplicationHelper;
	
	echo __FFMPEG__ . '<br/>';
	
	$video = __LOCATION__ . '/assets/user-images/1/feed-video-budvibes-1-2ea0859b.mp4';
	$newPic = $Helper->addVideoPic($video,1);
	
	if($newPic){
		echo $newPic;
	} else {
		echo 'No Pic <br/>';
		echo '<pre>'. print_r($newPic) .'</pre>';
	}