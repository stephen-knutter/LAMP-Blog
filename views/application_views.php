<?php
	
class ApplicationViews{
		
		private $Controller;
		private $Helper;
		
		public function __construct(){
			$this->Controller = new ApplicationCtrl;
			$this->Helper = new ApplicationHelper;
		}
		
		/*****
			#VIEWS TEMPLATE FUNCTIONS#
			 @addHead();
			 @doHeader();
			 @doUserMenu();
			 @doFooter();
			 @generateProfilePic();
			 @generateFeed();
			 @
		*****/
		
		#DISPLAY HEADER
		public function addHead($title='Blog',$meta_desc="",$alt="",$url=""){
			$html = '<!DOCTYPE html>';
			$html .= '<html>';
			$html .= '<head>';
			$html .= 	'<title>'.$title.'</title>';
			$html .= 	'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
			$html .= 	'<meta name="description" content="'.$meta_desc.'">';
			$html .= 	'<link rel="alternate" media="only screen and (max-width: 640px)" href="'.$alt.'">';
			$html .=	'<link rel="canonical" href="'.$url.'">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/mac-front.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/search.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/profile.css">';
			$html .=	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/sign.css">';
			$html .=	'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
			$html .= 	'<link rel="icon" href="https://www.budvibes.com/images/tab-pic.png">';
			$html .= 	'<script src="https://maps.googleapis.com/maps/api/js?v=3.14&sensor=false"></script>';
			$html .= 	'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/infobubble.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/search.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/front-script.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/profile.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/global-fns.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/relation.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/endless-scroll.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/tooltip.js"></script>';
			$html .= 	'<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
			$html .= '</head>';
			$html .= '<body>';
			
			echo $html;
		}
		
		#DISPLAY FOOTER
		public function doFooter(){
			$html = '</body>';
			$html .= '</html>';
			
			echo $html;
		}
		
		#HEAD BANNER
		public function doHeader($type='',$link=''){
			$html = '<div id="header" class="clearfix">';
			$html .= 	'<div id="headLogo">';
			$html .= 		'<a href="/"><img id="headLogo" src="'. __LOCATION__ .'/assets/images/plant.png" alt="budvibes logo"></a>';
			$html .= 	'</div>';
			$html .= 	'<div id="searchBar">';
			$html .=		'<input type="text" name="search" id="search" autocomplete="off">';
			$html .= 	'</div>';
						if(isset($_SESSION['logged_in_id'])){
							$username = $this->Controller->remove_whitespace($_SESSION['logged_in_user']);
							if($_SESSION['store']){
								$url = __LOCATION__ .'/'.$_SESSION['store_state'].'/'.$_SESSION['store_reg'].'/'.$username;
							} else {
								$url = __LOCATION__ .'/'.$username;
							}
							
							$html .= '<div id="headMenu">';
							/* HEAD MENU WITH IMAGES(LOGGED IN)
							$html .=	'<li><a href="'.$url.'"><img src="'. __LOCATION__ .'/assets/images/home-icon.png" alt="home"></a></li>';
							$html .=	'<li><a id="forumIcon"><img src="'. __LOCATION__ .'/assets/images/forum-icon.png" alt="budvibes forum topics"></a></li>';
							$html .= 	'<li><a id="libIcon"><img src="'. __LOCATION__ .'/assets/images/leaf-head-icon.png" alt="budvibes marijuana strains"></a></li>';
							$html .= 	'<li><a id="locationIcon"><img src="'. __LOCATION__ .'/assets/images/location-icon-small.png" alt="budvibes marijuana dispensary maps"></a></li>';
							$html .= 	'<li><a id="userIcon"><img src="'. __LOCATION__ .'/assets/images/user-icon.png" alt="'.$_SESSION['logged_in_user'].'"></a></li>';
							*/
							$html .=	'<li><a class="homeLink" href="'.$url.'"><i class="fa fa-home"></i></a></li>';
							$html .=	'<li><a id="forumIcon"><i class="fa fa-comment"></i></a></li>';
							$html .= 	'<li><a id="libIcon"><i class="fa fa-leaf"></i></a></li>';
							$html .= 	'<li><a id="locationIcon"><i class="fa fa-map-marker"></i></a></li>';
							$html .= 	'<li><a id="userIcon"><i class="fa fa-user-secret"></i></a></li>';
							if($type == 'message'){
								$html .= '<li><a id="msgIcon"><i class="fa fa-envelope"></i></a></li>';
							} else {
								$html .= '<li><a id="menuIcon"><i class="fa fa-bars"></i></a></li>';
							}
							
							$html .= '</div>';
						} else {
							$html .= '<div id="headMenu">';
							$html .=	'<li><a href="/"><img src="'. __LOCATION__ .'/assets/images/home-icon.png" alt="home"></a></li>';
							$html .=	'<li><a id="forumIcon"><img src="'. __LOCATION__ .'/assets/images/forum-icon.png" alt="budvibes forum topics"></a></li>';
							$html .= 	'<li><a id="libIcon"><img src="'. __LOCATION__ .'/assets/images/leaf-head-icon.png" alt="budvibes marijuana strains"></a></li>';
							$html .= 	'<li><a id="locationIcon"><img src="'. __LOCATION__ .'/assets/images/location-icon-small.png" alt="budvibes marijuana dispensary maps"></a></li>';
							$html .= 	'<li><a id="userIcon"><img src="'. __LOCATION__ .'/assets/images/user-icon.png" alt="budvibes sign in"></a></li>';
							
							if($type == 'message'){
								$html .= '<li><a id="msgIcon"><img src="'. __LOCATION__ .'/assets/images/message-user-icon.png" alt="budvibes sign up"></a></li>';
							} else {
								$html .= '<li><a id="menuIcon"><img src="'. __LOCATION__ .'/assets/images/menu-icon.png" alt="marijuana dispensary listings"></a></li>';
							}
							
							$html .= '</div>';
						}
			$html .= '</div>';
			
			echo $html;
		}
		
		#ADD HEAD MENU ITEMS TO DOM
		public function doUserMenu($type=''){
			?>
				
			<?php
		}
		
		#DISPLAY PROFILE PICTURE
		public function generateProfilePic($id,$name,$pic,$form,$type=''){
			echo '<div class="userProfilePic">';
			echo '<h2 id="usernameHead">'.$name.'</h2>';
			echo 	'<img id="ajaxUploadImg" src="'. __LOCATION__ .'/assets/images/gears.gif" alt="loading..."/>';
			if($pic == 'no-profile.png'){
				echo 	'<img id="userPicImg" src="'. __LOCATION__ .'/assets/images/no-profile.png" alt="'.$name.'\'s profile" />';
			} else if($type == 'product') {
				echo 	'<img id="userPicImg" src="'. __LOCATION__ .'/assets/images/strains/'.$pic.'" alt="'.$name.' weed strain">';		
			} else {
				echo 	'<img id="userPicImg" src="'. __LOCATION__ .'/assets/user-images/'.$id.'/'.$pic.'" alt="'.$name.'\'s profile">';
			}
			if($form){
				echo 	'<form id="changePhotoForm" action="change-photo.php" method="post" enctype="multipart/form-data" >';
				echo		'<input type="file" id="changeFileButton" name="photo" />';
				echo		'<img class="newPic" src="'. __LOCATION__ .'/assets/images/camera-icon.png" alt="photo upload" />';
				echo    '</form>';
			}
			echo '</div>';
		}
		
		public function generatePostForm($id,$username,$type){
			if($type == 'product'){
				$input_type = 'product';
			} else {
				$input_type = 'user';
			}
	
			if($type == 'edit'){
				$placeholder = 'Special Offer..';
				$post_text = 'Add';
			} else {
				$placeholder = 'Comment..';
				$post_text = 'Post';
			}
	?>
			<form action="../../../add-post-photo.php" method="post" enctype="multipart/form-data" class="addPostForm" target="headForm">
				<input type="hidden" name="post_type" class="post_type" value="<?php echo $input_type; ?>" />
				<textarea id="userFeedBox" placeholder="<?php echo $placeholder; ?>"></textarea>
				<div class="tagPane"></div>
				<?php
					if($type != 'edit'){
				?>
					<div contenteditable='true' class='userTagPane' placeholder='#Enter tag press enter'></div>
					<div class="submittedTags"></div>
				<?php
					}
				?>
				<iframe name="headForm" id="headForm"></iframe>
				<div id="cameraHover">Add a photo!</div>
				<div id="photoFileButtonWrap" class="buttonToggle clearfix">
					<!-- <img class="cameraPic" src="https://www.budvibes.com/images/camerawhite-32.png" /> --><!--camera-icon.png -->
					<span class="fa fa-camera"></span>
					<input type="file" name="post_photo" class="photoFileButton" />
				</div>
	
				<?php
					if($type != 'edit'){
				?>
				<div id="videoFileButtonWrap" class="buttonToggle clearfix">
					<!-- <img class="videoPic" src="https://www.budvibes.com/images/videowhite-32.png" /> --><!-- videowhite-32.png -->
					<span class="fa fa-video-camera"></span>
					<input type="file" name="post_video" class="videoFileButton" />
				</div>
				<?php
					}
				?>
	
				<?php
					if($type != 'edit'){
				?>
				<div id="linkFileButtonWrap" class="buttonToggle clearfix">
					<!-- <img class="linkPic" src="https://www.budvibes.com/images/linkwhite-32.png" /> --><!-- linkwhite-32.png -->
					<span class="fa fa-link"></span>
					<input type="text" name="add_link" class="linkFileButton" />
				</div>
				<?php
					}
				?>
	
				<?php
					if($type == 'store'){
				?>
						<div id="addRating">
							<span>Add Rating+</span>
						</div>
						<div class="stars">
							<label for="rating-1">
								<input id="rating-1" name="rating" type="radio" value="1" />
							</label>
							<label for="rating-2">
								<input id="rating-2" name="rating" type="radio" value="2" />
							</label>
							<label for="rating-3">
								<input id="rating-3" name="rating" type="radio" value="3" />
							</label>
							<label for="rating-4">
								<input id="rating-4" name="rating" type="radio" value="4" />
							</label>
							<label for="rating-5">
								<input id="rating-5" name="rating" type="radio" value="5" />
							</label>
							<label for="rating-6">
								<input id="rating-6" name="rating" type="radio" value="6" />
							</label>
							<label for="rating-7">
								<input id="rating-7" name="rating" type="radio" value="7" />
							</label>
							<label for="rating-8">
								<input id="rating-8" name="rating" type="radio" value="8" />
							</label>
							<label for="rating-9">
								<input id="rating-9" name="rating" type="radio" value="9" />
							</label>
							<label for="rating-10">
								<input id="rating-10" name="rating" type="radio" value="10" />
							</label>
						</div>
				<?php
					}
				?>
				<?php
					if($type == 'edit'){
						$button_id = 'addSpecialButton';
					} else {
						$button_id = 'addPostButton';
					}
				?>
				<div class="postButtonWrap"><button id="<?php echo $button_id; ?>" class="<?php echo $username; ?>-<?php echo $id; ?>"><?php echo $post_text; ?></button></div>
			</form>
	<?php
		}
		
	public function generateFeed($items,$feedType){
		foreach($items as $row){
			$commentId = $row['id'];
			$date = $row['created_at'];
			$profilePic = $row['profile_pic'];
			$userId = $row['user_id'];
			$username = $row['username'];
			$userCommId = $row['user_comm_id'];
			$commId = $row['comm_id'];
			$nullRow = $row['NULL'];
			$commType = $row['comm_type'];
			$type = $row['type'];
			$storeState = $row['store_state'];
			$storeRegion = $row['store_reg'];
			$origId = $row['orig_id'];
			$rating = $row['rating'];
			$comment = $row['comment'];
			$picture = $row['pic'];
			$video = $row['vid'];
			$tags = $row['tags'];
				
			echo "<div class='commWrap'>";
					//ITEM HEAD
					$this->generateFeedHead($commentId,$date,$profilePic,$userId,
											$username,$userCommId,$commId,$nullRow,
											$commType,$type,$storeState,$storeRegion,
											$origId,$rating);
					//ITEM BODY
					$this->generateFeedBody($commentId,$commType,$comment,$origId,
										    $username,$picture,$video,$rating,$tags);
											
					//ITEM REPLIES/FOOTER
					$this->generateFeedReplies($feedType,$commType,$commentId,$rating);
			echo "</div>";
		}
	}
	
	public function generateFeedReplies($feedType,$commType,$commentId,$rating){
		if($feedType != 'map' && $feedType != 'ajax-front'){
		//ADD REPLY FORM AND REPLIES
		if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
			$commType == 'svf' || $commType == 'svv' ||
			$commType == 'sll' || $commType == 'slf' || $commType == 'smk' || 
			$commType == 'shsll' || $commType == 'shslf' || $commType == 'shsmk' ||
			$commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' ||
			$commType == 'shsvv' || $commType == 'shsvf' || $commType == 'sllv' || 
			$commType == 'slfv' || $commType == 'shsllv' || $commType == 'shslfv'){
				$replies = $this->Controller->getProdReplies($commentId);
		} else {
				$replies = $this->Controller->getUserReplies($commentId);
		}
		
		$replyItems = $replies['items'];
		$replyNum = $replies['num'];
		
		if($replyNum == 1){
			$replyText = 'Reply';
		} else {
			$replyText = 'Replies';
		}
		
		echo "<div class='repliesWrapper'>";
		echo	"<div class='repliesHead clearfix'>";
		echo		"<div class='replyCount'>";
		echo			"<b><span class='fa fa-reply'></span><span class='replyNum'>".$replyNum."</span> <span class='replyPluralize'>".$replyText." </span><span class='addLink'>+Reply</span></b>";
		echo		"</div>";
		if($commType == 'rf' || $commType == 'rt' || $commType == 'rp' || $commType == 'rvf' || 			  
			$commType == 'rvv' || $commType == 'rll' || $commType == 'rlf' || 
			$commType == 'rllv' || $commType == 'rlvf'){
			echo 	"<div class='shareCount'>";
			if($rating > 0.1 && $rating <= .9){
				$stars = "<div class='half_star' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if ($rating > .9 && $rating <= 1.4){
				$stars = "<div class='one_star' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if ($rating > 1.4 && $rating <= 1.99){
				$stars = "<div class='one_half' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if ($rating > 1.99 && $rating <= 2.4){
				$stars	= "<div class='two_star' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if ($rating > 2.4 && $rating <= 2.99){
				$stars = "<div class='two_half' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if ($rating > 2.99 && $rating <= 3.4){
				$stars = "<div class='three_star' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if($rating > 3.4 && $rating  <= 3.99){
				$stars = "<div class='three_half' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if($rating > 3.99 && $rating <= 4.4){
				$stars = "<div class='four_star' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			} else if($rating > 4.4 && $rating <= 4.99){
				$stars = "<div class='four_half' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px; '></div>";
			} else if($rating == 5){
				$stars = "<div class='five_star' style='width: 100px; height: 20px; float: right; margin-left: 20px; position: relative; top: 1px;'></div>";
			} else {
				$stars = "<div class='no_stars' style='width: 100px; height: 20px; float: right; margin-left: 5px; position: relative; top: 1px;'></div>";
			}
			echo 		$stars;
			echo 	"</div>";
		} else {
			if($commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' || 
			   $commType == 'shsll' || $commType == 'shslf' || $commType == 'shsmk' || $commType == 'shsvf' || 
			   $commType == 'shsvv'){
				$shareCount = $this->Controller->generateProductShareCount($commentId);
			} else {
				$shareCount = $this->Controller->generateUserShareCount($commentId);
			}
			
			if($shareCount == 1){
				$share = 'Share';
			} else {
				$share = 'Shares';
			}
			
			echo		"<div class='shareCount'>";
			echo			"<b><span class='fa fa-retweet'></span>".$shareCount."&nbsp;".$share."&nbsp;<span class='shareLink' id='share-".$commentId."'>+Share</span></b>";
			echo		"</div>";
		}
		echo	"</div>";
		if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
			$commType == 'svf' || $commType == 'svv' ||
			$commType == 'sll' || $commType == 'slf' || $commType == 'smk' || 
			$commType == 'shsll' || $commType == 'shslf' || $commType == 'shsmk' ||
			$commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' || 
			$commType == 'shsvv' || $commType == 'shsvf' || $commType == 'sllv' || 
			$commType == 'slfv' || $commType == 'shsllv' || $commType == 'shslfv'){
			$postVal = 'product';
		} else {
			$postVal = 'user';
		}
		if(isset($_SESSION['logged_in_id']) && $_SESSION['logged_in_photo'] != 'no-profile.png'){
			$userThumb = __LOCATION__ . "/assets/user-images/".$_SESSION['logged_in_id']."/thumb-".$_SESSION['logged_in_photo'];
		} else {
			$userThumb = __LOCATION__ . "/assets/images/no-profile.png";
		}
		echo 	"<div class='replyForm clearfix'>";
		echo 	"<form action='../../add-post-photo.php' enctype='multipart/form-data' method='post' class='addPostForm' target='frame-".$commentId."'>";
		echo 	"<input type='hidden' name='post_type' class='post_type' value='".$postVal."'>";
		echo 	'<div class="replyThumb"><img src="'.$userThumb.'"></div>';
		echo 	"<textarea class='userReplyBox' placeholder='Reply..'></textarea>";
		echo		"<div class='replyPhotoButtonWrap'>";
		echo			"<span class='fa fa-camera'></span>";
		echo			"<input class='userReplyFile photoFileButton' type='file' name='post_photo' />";
		echo		"</div>";
		echo		"<div class='replyButtonWrap'><button class='replyButton' id='replyto-".$commentId."'>Reply</button></div>";
		echo 		"<div class='tagPane replyPane'></div>";
		echo		"<iframe class='curFrame' name='frame-".$commentId."' src=''></iframe>";
		echo	"</form>";
		echo 	"</div>";
		if($replyNum > 0){
			$i=0;
			foreach($replyItems as $reply){
				$i++;
				$replyProfilePic = $reply['profile_pic'];
				$replyUserId = $reply['user_id'];
				$replyUsername = $reply['username'];
				$replyUserType = $reply['type'];
				$replyState = $reply['store_state'];
				$replyRegion = $reply['store_reg'];
				$replyComment = $reply['reply'];
				$replyPicture = $reply['pic'];
				$date = strtotime($reply['created_at']);
				$date = date("M j, Y, g:i a", $date);
				if($replyProfilePic != 'no-profile.png'){
					$replyPic = __LOCATION__ . '/assets/user-images/'.$replyUserId.'/thumbsmall-'.$replyProfilePic;
				} else {
					$replyPic = __LOCATION__ . '/assets/images/thumbsmall-'.$replyProfilePic;
				}
				echo 	"<div class='replyWrap'>";
				echo 		"<div class='replyHead clearfix'>";
				echo			"<div class='repliesHeadImg'>";
				echo				"<img src='".$replyPic."' alt='".$replyUsername."'>";
				echo			"</div>";
				$linkName = $this->Controller->remove_whitespace($replyUsername);
				if($replyUserType == 'user'){
					$replyLink = __LOCATION__ .'/'. $linkName;
				} else {
					$replyLink = __LOCATION__ .'/'. $replyState.'/'.$replyRegion.'/'.$linkName;
				}
			
				echo 			"<div class='repliesHeadName'>";
				echo 				"<p><a class='grab' href='".$replyLink."'>".$replyUsername."</a><br/><span class='explain'>Replied &bull; ".$date."</span></p>";
				if($replyComment != 'NULL' || !($replyComment)){
					echo 	"<p class='replyText'>".$replyComment."</p>";
				} else {
					echo 	"<p class='replyText'></p>";
				}
			
				if($replyPicture != 'NULL' || !$replyPicture || (empty($replyPicture))){
					echo 			"<div class='replyImageCont'><img class='replyImage' src='". __LOCATION__ ."/assets/user-images/".$replyUserId."/feed-".$replyPicture."' alt='".$replyUsername."'></div>";
				}
				echo 			"</div>";
				echo 		"</div>";	
				echo 	"</div>";
			}
		}
		echo "</div>";
		echo "</div>";
	} else {
		//IF BELOW MAP FEED ONLY GRAB SHARE AND REPLY TOTALS
			if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
			$commType == 'sll' || $commType == 'slf' || $commType == 'svv' || 
			$commType == 'svf' || $commType == 'sllv' || $commType == 'slfv'){
				$replyCount = $this->Controller->getProductReplyCount($commentId);
			} else {
				$replyCount = $this->Controller->getUserReplyCount($commentId);
			}
			if($replyCount == 1){
				$reply = 'reply';
			} else {
				$reply = 'replies';
			}
			echo '<div class="replyBarNum">
					<span class="countReplies"><b>'.$replyCount.'</b>&nbsp;'.$reply.'&nbsp;&bull;&nbsp;</span><span class="countShares"><b>0</b> shares<span/>
				</div>';
		echo '</div>';
		}
	}
	
	public function generateFeedBody($commentId,$commType,$comment,$origId,$username,
									 $picture,$video,$rating,$tags){
		echo "<div class='commBody'>";
			if($commType == 'rll' || $commType == 'pll' || $commType == 'sll' 
			|| $commType == 'rlf' || $commType == 'plf' || $commType == 'slf' || 	
			$commType == 'shrll' ||		$commType == 'shpll' || $commType == 'shsll' 
			|| $commType == 'shrlf' || $commType == 'shplf' || $commType == 'shslf'){
				if($commType == 'sll' || $commType == 'slf' || 
				$commType == 'shsll' || $commType == 'shslf'){
					$permalink = __LOCATION__ .'/strainpost/' .$commentId;
				} else {
					$permalink = __LOCATION__ .'/userpost/'.$commentId;
				}
				echo '<div class="commPostPic" id="test'.$commentId.'">';
				echo 	'<div class="linkWrap">';
				if(!preg_match('#<iframe#',$comment) && $picture != 'NULL'){
					echo 	'<a href="'.$permalink.'" target="_blank"><img src="'. __LOCATION__ .'/assets/user-images/'.$origId.'/feed-'.$picture.'" alt="'.$username.'\'s link"></a>';
				}
				if($comment != 'NULL'){
					echo $comment;
				}
				echo 	'</div>';
				echo '</div>';
			} else if($commType == 'rllv' || $commType == 'pllv' || $commType == 'sllv' || 
				$commType == 'rlfv' || $commType == 'plfv' || $commType == 'slfv' || 
				$commType == 'shrllv' || $commType == 'shpllv' || $commType == 'shsllv' || 
				$commType == 'shrlfv' || $commType == 'shplfv' || $commType == 'shslfv'){
					echo '<div class="commPostPic" id="test'.$commentId.'">';
					echo 	'<div class="linkWrap">';
					echo 		$video;
					if($comment != 'NULL'){
						echo $comment;
					}
					echo 	'</div>';
					echo '</div>';
			} else if($commType == 'smk' || $commType == 'shsmk'){
				//$linkName = $this->Controller->remove_whitespace($row_result['user_name']);
				//echo '<span class="nowSmokingFeed">is now smoking <a class="grab-prod" href="https://www.budvibes.com/strains/'.$link_name.'">'.$row_result['user_name'].'</a></span>';
			} else {
				if($video && $video != 'NULL'){
					if($commType == 'rvf' || $commType == 'pvf' || $commType == 'svf' || 
					$commType == 'rvv' || $commType == 'pvv' || $commType == 'svv' 
					|| $commType == 'shpvf' || $commType == 'shsvf' || $commType == 'shpvv' || 
					$commType == 'shsvv'){
						$micro =  uniqid();
						echo "<div class='commPostVideo'>";
						echo	"<video id='video_".$commentId."' class='video-js vjs-default-skin' controls ";
						echo	 " preload='auto' width='100%' height='auto' style='position: relative; display: block; margin: 0 auto; padding: 0;' data-setup='{}' 
									poster='". __LOCATION__ ."/assets/user-images/".$origId."/".$picture."'>";
						echo	 "<source src='". __LOCATION__ ."/assets/user-images/".$origId."/".$video."'>";
						echo	 "<p class='vjs-no-js'>To view this video please enable JavaScript, and consider upgrading to a web browser that <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a></p>";
						echo 	"</video>";
						echo 	"<script type='text/javascript'>videojs('video_".$commentId."',{},function(){})</script>";
						echo "</div>";
					}
				} else if($picture && $picture != 'NULL' && $video == 'NULL'){
					if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
					$commType == 'svf' || $commType == 'svv' || 
					$commType == 'sllv' || $commType == 'slfv'){
						$imgLink = __LOCATION__ .'/strainpost/'. $commentId;
					} else {
						$imgLink = __LOCATION__ .'/userpost/'. $commentId;
					}
					echo	"<div class='commPostPic' id='testid".$commentId."'>";
					echo		"<a href='".$imgLink."' target='_blank'><img src='". __LOCATION__ ."/assets/user-images/".$origId."/feed-".$picture."' alt='".$username."&#39;s post' /></a>";
					echo	"</div>";
				}
				if($comment && $comment != 'NULL'){
					if($commType == 'fg' || $commType == 'shfg'){
						//GET THREAD
						$forumThread = $this->Controller->getForumThread($origId);
						$threadName = $forumThread['title'];
						$threadLink = $this->Controller->remove_whitespace($threadName);
						$parentId = $forumThread['parent'];
						//GET BLOCK WITHIN FORUM
						$blockName = $this->Controller->getForumBlock($parentId);
						$blockLink = $this->Controller->remove_whitespace($blockName);
				
						echo '<div class="breadcrumbTrail">
								<p class="firstCrumb"><a href="'. __LOCATION__ .'/forum/general/'.$blockLink.'">'.$blockName.'</a></p>
								<p class="secondCrumb"><a href="'. __LOCATION__ .'/forum/general/'.$blockLink.'/'.$threadLink.'">'.$threadName.'</a></p>
							</div>';
					}
					if($commType == 'fg' || $commType == 'shfg'){
						$postClass = 'forumPostText';
					} else {
						$postClass = 'commPostText';
					}
					echo		"<div class='".$postClass."'>";
					//FORUM REPLY W/ QUOTE
					if($commType == 'fg' || $commType == 'shfg'){
						$forumReply = $this->Controller->getForumReply($rating);
						
						if(!empty($forumReply)){
							$replyUser = $forumReply['username'];
							$replyComment = $forumReply['comment'];
							$linkName = $this->Controller->remove_whitespace($replyUser);
							if($forumReply['type'] == 'user'){
								$userLink = __LOCATION__ .'/'. $link_name;
							} else {
								$replyState = $forumReply['store_state'];
								$replyRegion = $forumReply['store_reg'];
								$userLink = __LOCATION__ .'/'. $replyState.'/'.$replyRegion.'/'.$linkName;
							}

								echo "<div class='replyWithQuoteHead'>
										<p class='repliedFrom'><i class='original'>originally from &nbsp;</i><a class='grab' href='".$userLink."'>".$replyUser."</a></p>
									</div>";

							if($replyComment != 'NULL'){
								echo "<div class='replyWithQuoteWrap'>".$replyComment."</div>";
							}
						}
					}

					if($commType == 'fg' || $commType == 'shfg'){
						$forumMessage = $this->Controller->getForumContent($rating);
						if($forumMessage != 'NULL'){
							echo "<p>".$forumMessage."</p>";
						}
					} else {
						echo	"<p>".$comment."</p>";
					}

					echo "</div>";
				}
			}
			if($tags && $tags != 'NULL'){
				$userTags = explode(",", $tags);
				echo '<div class="userTags">';
					foreach($userTags as $key=>$tag){
						$tagLink = $this->Controller->remove_whitespace($tag);
						echo '<span class="usertag"><a href="'. __LOCATION__ .'/tags/'.$tagLink.'">'.$tag.'</a></span>';
					}		
				echo '</div>';
			}
			echo	"</div>";
	}
		
	public function generateFeedHead($commentId,$date,$profilePic,$userId,$username,
									 $userCommId,$commId,$nullRow,$commType,
									 $type,$storeState,$storeRegion,$origId,
									 $rating){
		$linkName = $this->Controller->remove_whitespace($username);
		$date = strtotime($date);
		$date = date("M j, Y, g:i a", $date);
		if($profilePic != 'no-profile.png'){
			$picLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.'thumb-'.$profilePic;
		} else {
			$picLink = __LOCATION__ . '/assets/images/thumb-'.$profilePic;
		}
			
		echo "<div class='commHead clearfix'>";
		echo 	"<div class='commHeadImg'>";
		echo		"<img src='".$picLink."' alt='".$username."' />";
		echo	"</div>";
		echo 	"<div class='commHeadName'>";
				if($userCommId == $commId && !($nullRow) || $commType == 'fg' 
				|| $commType == 'shfg' || $commType == 'smk' || $commType == 'shsmk' || 
				$commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' || 
				$commType == 'shsll' || $commType == 'shslf' || $commType == 'shsvv' || 
				$commType == 'shsvf' || $commType == 'shsllv' || $commType == 'shslfv'){
					if($type == 'user'){
						$link = __LOCATION__ .'/'. $linkName;
					} else {
						$link = __LOCATION__ .'/'. $storeState.'/'.$storeRegion.'/'.$linkName;
					}
								
					//SINGLE POST
					if($commType == 'shpll' || $commType == 'shplf' || $commType == 'shpp' || 
					$commType == 'shpf' || $commType == 'shpt' || $commType == 'shpvf' || 
					$commType == 'shpvv' || $commType == 'shsmk' || $commType == 'shfg' || 
					$commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' || 
					$commType == 'shsll' || $commType == 'shslf' || $commType == 'shsvv' || 
					$commType == 'shsvf' || $commType == 'shsllv' || $commType == 'shslfv' || 
					$commType == 'shpllv'){
						switch($commType){
							case 'shpll':
							case 'shplf':
							case 'shsll':
							case 'shslf':
							case 'shsllv':
							case 'shslfv':
							case 'shpllv':
								$infoText = 'link';
							break;
							case 'shpp':
							case 'shsp':
								$infoText = 'photo';
							break;
							case 'shpf':
							case 'shsf':
							case 'shpt':
							case 'shst':
								$infoText = 'post';
							break;
							case 'shpvf':
							case 'shsvf':
							case 'shpvv':
							case 'shsvv':
								$infoText = 'video';
							break;
							case 'shsmk':
								$infoText = 'smoke';
							break;
							case 'shfg':
								$infoText = 'forum post';
							break;
						}
						if($commType == 'shfg'){
							$sharedFrom = $this->Controller->getSharedUsername($userCommId);
						} else {
							$sharedFrom = $this->Controller->getSharedUsername($origId);
						}
						$sharedLinkname = $this->Controller->remove_whitespace($shared_from);
						echo "<p class='singcomm'>";
						echo	"<a class='grab user-icon' href='".$link."'>".$username."</a>";
						echo	"<span class='explain'>&nbsp;shared&nbsp;</span><span class='sharefrom'><a href='". __LOCATION__ ."/".$sharedLinkname."'>".$sharedFrom."</a>'s ".$infoText."</span> ";
						echo		"<br/><span class='explain'>".$date."</span>&nbsp;";
						if($commType == 'shsvv' || $commType == 'shsvf' || 
						$commType == 'shst' || $commType == 'shsf' || $commType == 'shsp' || 
						$commType == 'shsll' || $commType == 'shslf' || 
						$commType == 'shsllv' || $commType == 'shslfv'){
							//$strain_link = remove_whitespace($row_result['user_name']);
							//echo		"<a class='grab-prod prod-icon about-text' href='https://www.budvibes.com/strains/".$strain_link."'>".$row_result['user_name']."</a>";
						}
						echo "</p>";
					} else {
						echo	"<p class='singcomm'><a class='grab user-icon' href='".$link."'>".$username."</a><br/><span class='explain'>&nbsp;posted &bull; ".$date." </span></p>";			
					}
				} else {
					if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
					$commType == 'svf' || $commType == 'svv' || $commType == 'sll' 
					|| $commType == 'slf' || $commType == 'sllv' || $commType == 'slfv'){
						/*!!!!! ADDN'L QUERY FOR STRAIN !!!!!!*/
						$secondUsername = $this->Controller->getStrainNameHead($userCommId);
						$linkName = $this->Controller->remove_whitespace($secondUsername);
						$wallLink = __LOCATION__ .'/strains/'. $linkName;
						$iconClass = 'prod-icon';
					} else {
						/*!!!!! ADDN'L QUERY FOR SECOND USER !!!!!!*/
						$secondUser = $this->Controller->getSecondUsernameHead($userCommId);
						$secondUsername = $secondUser['username'];
						$linkName = $this->Controller->remove_whitespace($secondUsername);
						if($type == 'user'){
							$wallLink = __LOCATION__ .'/'. $linkName;
							$iconClass = 'user-icon';
						} else {
							$secondStoreRegion = $user['store_reg'];
							$secondStoreState = $user['store_state'];
							$wallLink = __LOCATION__ .'/'. $secondStoreState.'/'.$secondStoreRegion.'/'.$linkName;
							$iconClass = 'store-icon';
						}
					}

					if($nullRow || $commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
					$commType == 'svf' || $commType == 'svv' || $commType == 'sll' 
					|| $commType == 'slf' || $commType == 'sllv' || $commType == 'slfv'){
						$grab = 'grab-prod';
					} else {
						$grab = 'grab';
					}
		
					$linkName = $this->Controller->remove_whitespace($username);
					if($type == 'user'){
						$link = __LOCATION__ .'/'. $linkName;
					} else {
						$link = __LOCATION__ .'/'. $storeState.'/'.$storeRegion.'/'.$linkName;
					}
					if($commType == 'rf' || $commType == 'rt' || $commType == 'rp' || $commType == 'rvf' || 
						$commType == 'rvv' || $commType == 'rll' || $commType == 'rlf' || $commType == 'rllv' || 
						$commType == 'rlfv'){
						echo	"<p class='singcomm'><a class='grab' href='".$link."'>".$username."</a><span class='explain'>&nbsp;rated &bull; ".$date."</span></p>
						<p><a class='grab ".$iconClass."' href='".$wallLink."'>".$secondUsername."</a><span> ".$rating." of 5 stars</span></p>";
					} else {
						echo	"<p class='singcomm'><a class='grab' href='".$link."'>".$username."</a><span class='explain'>&nbsp;posted &bull; ".$date." </span></p>
						<p><span>to </span><a class='".$grab." ".$iconClass."' href='".$wallLink."'>".$secondUsername."</a><span>'s feed</span></p>";
					}
				}
	echo	"</div>";//END CommHeadName
			if(isset($_SESSION['logged_in_id']) && $_SESSION['logged_in_id'] == $userId){
				echo '<span class="editLink"><i class="fa fa-angle-down"></i></span>';
				echo '<div class="delete"><span id="post|'.$commentId.'|'.$commType.'">Delete this post</span></div>';
			}
	echo "</div>";//END commHead
  }
} //END ApplicationViews Class
?>