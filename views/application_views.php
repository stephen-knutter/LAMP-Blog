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
			 @generateFollowingButtons();
			 @
		*****/
		
		#DISPLAY HEADER
		public function addHead($type,$item){
			switch($type){
				case 'profile':
					$title = $item['username'] . __URLTITLE__;
					$url = __LOCATION__ . '/' . $item['slug'];
					$alt = __MOBILELOCATION__ . '/' . $item['slug'];
					$meta = $item['username'] . '&#39;s feed';
				break;
				case 'store':
					if($item['type'] == 'rec'){
						$tail = __RECTAIL__;
					} else {
						$tail = __MEDTAIL__;
					}
					$title = $item['username'] . ' | ' . $tail;
					$uri = '/dispensary/' 
					      . $item['store_state'] . '/' 
						  . $item['store_reg'] . '/'
						  . $item['slug'];
					$url = __LOCATION__ . $uri;
					$alt = __MOBILELOCATION__ . $uri;
					if($item['votes'] > 0){
						$rating = round($item['value']/$item['votes'],2);
						$votes = $item['votes'];
					} else {
						$rating = 0;
						$votes = 0;
					}
					$meta = $item['username'] 
					        . ' located at ' 
							.$item['address']
							.' call at ' 
							.$item['phone'] 
							.' is rated '
							.$rating 
							.' out of 5 stars';
				break;
				case 'custom':
					$title = $item['title'];
					$url = __LOCATION__ . $item['location'];
					$alt = __MOBILELOCATION__ . $item['location'];
					$meta = $item['meta'];
				break;
				default:
					$title = __URLTITLE__;
					$url = __LOCATION__;
					$alt = __MOBILELOCATION__;
					$meta = __WELCOME__;
				break;
			}
			
			$html = '<!DOCTYPE html>';
			$html .= '<html>';
			$html .= '<head>';
			$html .= 	'<title>'.$title.'</title>';
			$html .= 	'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
			$html .= 	'<meta name="description" content="'.$meta.'">';
			$html .= 	'<link rel="alternate" media="only screen and (max-width: 640px)" href="'.$alt.'">';
			$html .=	'<link rel="canonical" href="'.$url.'">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/mac-front.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/search.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/profile.css">';
			$html .=	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/sign.css">';
			$html .=	'<link rel="stylesheet" type="text/css" href="'.__LOCATION__ .'/assets/css/store-page.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/lightbox.css">';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/jquery.Jcrop.min.css">';
			$html .=	'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
			$html .= 	'<link rel="icon" href="'. __LOCATION__ .'/assets/images/tab-pic.png">';
			$html .= 	'<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.14&sensor=false"></script>';
			$html .= 	'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/config.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/global-fns.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/profile.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/infobubble.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/chat.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/search.js"></script>';
			if($type == 'store'){
				$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/store-profile.js"></script>';
			}
			if($type == 'store-edit'){
				$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/store-edit.js"></script>';
				$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/search-menu.js"></script>';
			}
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/jquery.form.min.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/jquery.Jcrop.min.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/geoPosition.js"></script>';
			$html .=	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/geoPositionSimulator.js"></script>';
			if($type == 'front'){
				$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/front-script.js"></script>';
			}
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/relation.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/endless-scroll.js"></script>';
			$html .= 	'<script type="text/javascript" src="'. __LOCATION__ .'/assets/javascripts/tooltip.js"></script>';
			
			$html .= 	'<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
			$html .= 	'<script src="https://vjs.zencdn.net/4.12/video.js"></script>';
			$html .= 	'<link rel="stylesheet" type="text/css" href="'. __LOCATION__ .'/assets/css/tube.css" />';
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
								$url = __LOCATION__ .'/dispensary/'.$_SESSION['store_state'].'/'.$_SESSION['store_reg'].'/'.$username;
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
							/*
							$html .=	'<li><a href="/"><img src="'. __LOCATION__ .'/assets/images/home-icon.png" alt="home"></a></li>';
							$html .=	'<li><a id="forumIcon"><img src="'. __LOCATION__ .'/assets/images/forum-icon.png" alt="budvibes forum topics"></a></li>';
							$html .= 	'<li><a id="libIcon"><img src="'. __LOCATION__ .'/assets/images/leaf-head-icon.png" alt="budvibes marijuana strains"></a></li>';
							$html .= 	'<li><a id="locationIcon"><img src="'. __LOCATION__ .'/assets/images/location-icon-small.png" alt="budvibes marijuana dispensary maps"></a></li>';
							$html .= 	'<li><a id="userIcon"><img src="'. __LOCATION__ .'/assets/images/user-icon.png" alt="budvibes sign in"></a></li>';
							*/
							$html .=	'<li><a href="/"><i class="fa fa-home"></i></a></li>';
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
						}
			$html .= '</div>';
			
			echo $html;
		}
		
		public function generateFollowingButtons($id,$user){
		?>
			<!-- FOLLOW USER PAGE-->
				<div class="message-user" id="message-<?php echo $id; ?>" data-chat="<?php echo $id; ?>">
					<img src="<?php echo __LOCATION__  . '/assets/images/message-icon.png' ?>" alt="message" title="message <?php echo $user; ?>"/>
				</div>
				<div class="follow-message" id="typeUser">	
					<span id="relationLink" class="unfollow-<?php echo $id; ?> unfollowText">Unfollow</span>
				</div>
		<?php
		}
		
		public function generateEditButtons($id,$user){
		?>
			<!-- EDIT BUTTON USER PAGE -->
				<div class="follow-message" id="typeUser">
					<?php
						if(@$_SESSION['store']){
							$linkName = $this->Controller->remove_whitespace(@$_SESSION['logged_in_user']);
							$linkState = $this->Controller->remove_whitespace(@$_SESSION['store_state']);
							$linkRegion = $this->Controller->remove_whitespace(@$_SESSION['store_reg']);
							$storeUrl = __LOCATION__ . '/dispensary/'.$linkState.'/'.$linkRegion.'/'.$linkName;
					?>
						<span id="editLink" class="follow-<?php echo $id; ?> editText"><a href="<?php echo $storeUrl.'/edit'; ?>">Edit</a></span>
					<?php
						} else {
							$linkName = $this->Controller->remove_whitespace(@$_SESSION['logged_in_user']);
							$userUrl = __LOCATION__ . '/'.$linkName.'/edit';
					?>
						<span id="editLink" class="follow-<?php echo $id; ?> editText"><a href="<?php echo $userUrl; ?>">Edit</a></span>
					<?php
						}
					?>
					<img class="userEditIcon" src="<?php echo __LOCATION__ . '/assets/images/rss-icon.png' ?>" />
				</div>
		<?php	
		}
		
		public function generateFollowerButtons($id,$user){
			?>
			<!-- FOLLOW BUTTON FOR NON LOGGED IN USERS ON USER PAGE-->
			<div class="message-user" id="message-<?php echo $id; ?>" data-chat="<?php echo $id; ?>">
				<img src="<?php echo __LOCATION__ . '/assets/images/message-icon.png' ?>" alt="message" title="message <?php echo $user; ?>"/>
			</div>
			<div class="follow-message" id="typeUser">
				<span id="relationLink" class="follow-<?php echo $id; ?> followText">Follow</span>
			</div>
			<?php
		}
		
		public function generateUserCountBar($url,$feedclass,$postclass,$photoclass,
											 $budclass,$followerclass,$followingclass,$videoclass,
											 $feedCount,$postCount,$photoCount,$videoCount,
											 $followerCount,$budCount,$followingCount){
			echo '<div class="infoBarWrap clearfix">';
			echo 	'<a href="'.$url.'"> <div class="feedInfo '.$feedclass.' clearfix">
						<span class="infoNumber">'.$feedCount.'</span>
						<span class="infoDesc">feed</span>
					</div></a>';
			echo 	'<a href="'.$url.'/posts"><div class="postInfo '.$postclass.' clearfix">
						<span class="infoNumber">'.$postCount.'</span>
						<span class="infoDesc">posts</span>
					</div></a>';
			echo 	'<a href="'.$url.'/photos"><div class="photoInfo '.$photoclass.' clearfix">
						<span class="infoNumber">'.$photoCount.'</span>
						<span class="infoDesc">photos</span>
					</div></a>';
			echo 	'<a href="'.$url.'/videos"><div class="followingInfo '.$videoclass.' clearfix">
						<span class="infoNumber">'.$videoCount.'</span>
						<span class="infoDesc">video</span>
					</div></a>';
			echo 	'<a href="'.$url.'/followers"><div class="followersInfo '.$followerclass.' clearfix">
						<span class="infoNumber">'.$followerCount.'</span>
						<span class="infoDesc">followers</span>
					</div></a>';
			echo    '<a href="'.$url.'/buds"><div class="budInfo '.$budclass.' clearfix">
						<span class="infoNumber">'.$budCount.'</span>
						<span class="infoDesc">buds</span>
					</div></a>';
			echo 	'<a href="'.$url.'/following"><div class="followingInfo '.$followingclass.' clearfix">
						<span class="infoNumber">'.$followingCount.'</span>
						<span class="infoDesc">following</span>
					</div></a>';
			echo '</div>';								  
		}
		
		public function generateStoreCountBar($url,$feedclass,$postclass,$photoclass,
											 $menuclass,$followerclass,$followingclass,$videoclass,
											 $feedCount,$postCount,$photoCount,$videoCount,
											 $followerCount,$menuCount,$followingCount){
			echo '<div class="infoBarWrap clearfix">';
			echo 	'<a href="'.$url.'"> <div class="feedInfo '.$feedclass.' clearfix">
						<span class="infoNumber">'.$feedCount.'</span>
						<span class="infoDesc">feed</span>
					</div></a>';
			echo 	'<a href="'.$url.'/posts"><div class="postInfo '.$postclass.' clearfix">
						<span class="infoNumber">'.$postCount.'</span>
						<span class="infoDesc">posts</span>
					</div></a>';
			echo 	'<a href="'.$url.'/photos"><div class="photoInfo '.$photoclass.' clearfix">
						<span class="infoNumber">'.$photoCount.'</span>
						<span class="infoDesc">photos</span>
					</div></a>';
			echo 	'<a href="'.$url.'/videos"><div class="followingInfo '.$videoclass.' clearfix">
						<span class="infoNumber">'.$videoCount.'</span>
						<span class="infoDesc">video</span>
					</div></a>';
			echo 	'<a href="'.$url.'/followers"><div class="followersInfo '.$followerclass.' clearfix">
						<span class="infoNumber">'.$followerCount.'</span>
						<span class="infoDesc">followers</span>
					</div></a>';
			echo    '<a href="'.$url.'/menu"><div class="budInfo '.$menuclass.' clearfix">
						<span class="infoNumber">'.$menuCount.'</span>
						<span class="infoDesc">menu</span>
					</div></a>';
			echo 	'<a href="'.$url.'/following"><div class="followingInfo '.$followingclass.' clearfix">
						<span class="infoNumber">'.$followingCount.'</span>
						<span class="infoDesc">following</span>
					</div></a>';
			echo '</div>';
		}
		
		public function doTopStrains($topStrains){
			echo '<div class="recentStrainsWrap clearfix">';
			echo 	'<div id="strainHeadText">';
			echo		'<h3>Top Strains</h3>';
			echo 	'</div>';
			echo 	'<div class="topStrainsPics clearfix">';
					foreach($topStrains as $row){
						if(strlen($row['name']) > 20){
							$name = substr($row['name'],0,20);
							$name = $name.'...';
						} else {
							$name = $row['name'];
						}
						$linkName = $this->Controller->remove_whitespace($row['name']);
						echo '<a href="'. __LOCATION__ .'/strains/'.$linkName.'">';
						echo '<div class="topStrainWrap">';
						echo '<div class="topStrainImg"><img style="height: 110px; width: 110px;" src="'. __LOCATION__ .'/assets/images/strains/110-'.$row['pic'].'" alt="'.$row['name'].' weed strain"></div>';
						echo '<div class="topStrainName">'.$name.'</div>';
						echo '</div>';
						echo '</a>';
					}
					echo '<a href="'. __LOCATION__ .'/strains/">';
					echo '<div class="topStrainWrap">';
					echo '<div class="topStrainImg"><img style="height: 110px; width: 110px;" src="'. __LOCATION__ .'/assets/images/explore.png" alt="More Weed"></div>';
					echo '<div class="topStrainName">More...</div>';
					echo '</div>';
					echo '</a>';
			echo	'</div>';
			echo 	'</div>';
		}
		
		public function doTopPosters($topPosters){
			echo '<div class="recentStrainsWrap topPosters clearfix">';
			echo	'<div id="posterHeadText">';
			echo		'<h3>Top Posters</h3>';
			echo 	'</div>';
			echo	'<div class="topStrainsPics clearfix">';
					//$topPosters = $this->UserModel->getTopPosters();
					foreach($topPosters as $row){
						if(strlen($row['username']) > 20){
							$name = substr($row['username'],0,20);
							$name = $name.'...';
						} else {
							$name = $row['username'];
						}
						$linkName = $this->Controller->remove_whitespace($row['username']);
						if($row['type'] == 'store'){
							echo '<a href="'. __LOCATION__ .'/dispensary/'.$row['store_state'].'/'.$row['store_reg'].'/'.$linkName.'">';
						} else {
							echo '<a href="'. __LOCATION__ .'/'.$linkName.'">';
						}
						if($row['profile_pic'] == 'no-profile.png'){
							$profilePic = __LOCATION__ . "/assets/images/top-no-profile.png";
						} else {
							$profilePic = __LOCATION__ . "/assets/user-images/".$row['id']."/top-".$row['profile_pic'];
						}
						echo '<div class="topStrainWrap">';
						echo '<div class="topStrainImg"><img style="height: 110px; width: 110px;" src="'.$profilePic.'" alt="'.$row['username'].'\'s profile"></div>';
						echo '<div class="topStrainName">'.$name.'</div>';
						echo '</div>';
						echo '</a>';
					}
					echo '</div>';
	
			//do_footer($type,$store);
	
			echo  '</div>';	
		}
		
		#ADD HEAD MENU ITEMS TO DOM
		public function doUserMenu($type=''){
			?>
			<!-- LOGIN MENU -->
			<?php if(!isset($_SESSION['logged_in_user'])) { ?>
				<div id="logMenu" class="logIn logMenu">
					<h3><img src="<?php echo __LOCATION__ . '/assets/images/log-in-head.png'; ?>" alt="budvibes sign in"></h3>
					<div id="logWrap">
					<form action="<?php echo __LOCATION__ . '/login';?>" method="post" id="loginForm">
						<input type="hidden" id="back_uri" name="back_uri" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
						<input type="text" class="loginInput" name="email" id="loginText" placeholder="Email" />
						<input type="password" class="loginInput" name="pass" id="loginPass"  placeholder="Password">
						<input type="submit" class="loginSubmit" name="sign-in" id="submitLogin" value="Log In" />
					</form>
					<div id="signUp"><a href="<?php echo __LOCATION__ . '/signup'; ?>" id="signUpLink">Sign Up?</a></div>
					</div>
				</div>
		
				<div id="logMenu" class="signUp logMenu">
					<h3><img src="<?php echo __LOCATION__ . '/assets/images/sign-up-head.png'; ?>" alt="budvibes sign up"></h3>
					<div id="logWrap">
						<form action="<?php echo __LOCATION__ . '/signup';?>" method="post" id="signUpForm">
							<input type="hidden" id="back_uri" name="back_uri" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
							<input type="text" class="signInput" name="username" placeholder="Username">
							<input type="text" class="signInput" name="email" placeholder="Email">
							<input type="password" class="signInput" name="password" placeholder="Password">
							<input type="password" class="signInput" name="confirmation" placeholder="Confirm Password">
							<input type="submit" class="signSubmit" name="register" value="Sign Up">
						</form>
						<div id="signUp">
							<a href="<?php echo __LOCATION__ . '/login' ?>" id="signInLink">&#8592; Log In</a>
						</div>
					</div>
				</div>
		<!-- LOGGED IN USER MENU -->
		<?php
		  } else {
		?>
			<div id="logMenu" class="userMenu logMenu">
				<?php
					if($_SESSION['logged_in_photo'] == 'no-profile.png' || !$_SESSION['logged_in_photo']){
						$imgPath = __LOCATION__ . '/assets/images/thumb-no-profile.png';
					} else {
						$imgPath = __LOCATION__ . '/assets/user-images/'.$_SESSION['logged_in_id'].'/thumb-'.$_SESSION['logged_in_photo'];
					}
				?>
				<img id="userTopThumb" style="height: 60px; width: 60px;" src="<?php echo $imgPath; ?>" alt="<?php echo $_SESSION['logged_in_user']; ?>">
				<?php
					$linkName = $this->Controller->remove_whitespace($_SESSION['logged_in_user']);
					if($_SESSION['store']){
						$url = __LOCATION__ .'/'.$_SESSION['store_state'].'/'.$_SESSION['store_reg'].'/'.$linkName;
				?>
					<h3><a href="<?php echo $url; ?>"><?php echo $_SESSION['logged_in_user']; ?></a></h3>
				<?php
					} else {
						$url = __LOCATION__ .'/'. $linkName;
				?>
					<h3><a href="<?php echo $url; ?>"><?php echo $_SESSION['logged_in_user']; ?></a></h3>
				<?php
					}
				?>
				<div id="logOut"><a href="<?php echo __LOCATION__ . '/logout'; ?>">Log Out</a></div>
			</div>
		<?php
		  }
		?>
		<!-- LOCATION MENU -->
		<div id="locationMenu">
			<h3><img src="<?php echo __LOCATION__ . '/assets/images/map-head.png'; ?>" alt="weed map locations"></h3>
			<div id="mapsBody">
				<div id="mapFeed" class="Scrollable">
					<ul>
						<li><a href="<?php echo __LOCATION__ . '/colorado/denver'; ?>">Colorado</a></li>
						<li><a href="<?php echo __LOCATION__ . '/washington/seattle'; ?>">Washington</a></li>
						<li><a href="<?php echo __LOCATION__ . '/oregon/portland'; ?>">Oregon</a></li>
						<li><a href="<?php echo __LOCATION__ . '/california/los-angeles'; ?>">California</a></li>
						<li><a href="<?php echo __LOCATION__ . '/michigan/detroit'; ?>">Michigan</a></li>
						<li><a href="<?php echo __LOCATION__ . '/arizona/phoenix'; ?>">Arizona</a></li>
						<li><a href="<?php echo __LOCATION__ . '/nevada/las-vegas'; ?>">Nevada</a></li>
						<li><a href="<?php echo __LOCATION__ . '/spain/barcelona'; ?>">Spain</a></li>
						<li><a href="<?php echo __LOCATION__ . '/netherlands/amsterdam'; ?>">Amsterdam, NL</a></li>
						<li><a href="<?php echo __LOCATION__ . '/british-columbia/vancouver'; ?>">Vancouver, BC</a></li>
						<li><a href="<?php echo __LOCATION__ . '/new-mexico/albuquerque'; ?>">New Mexico</a></li>
						<li><a href="<?php echo __LOCATION__ . '/maine/augusta'; ?>">Maine</a></li>
						<li><a href="<?php echo __LOCATION__ . '/connecticut/hartford'; ?>">Connecticut</a></li>
						<li><a href="<?php echo __LOCATION__ . '/montana/bozeman'; ?>">Montana</a></li>
						<li><a href="<?php echo __LOCATION__ . '/district-of-columbia/washington'; ?>">District of Columbia</a></li>
						<li><a href="<?php echo __LOCATION__ . '/illinois/chicago'; ?>">Illinois</a></li>
						<li><a href="<?php echo __LOCATION__ . '/new-jersey/newark'; ?>">New Jersey</a></li>
						<li><a href="<?php echo __LOCATION__ . '/minnesota/minneapolis'; ?>">Minnesota</a></li>
						<li><a href="<?php echo __LOCATION__ . '/massachusetts/boston'; ?>">Massachusetts</a></li>
						<li><a href="<?php echo __LOCATION__ . '/alaska/anchorage'; ?>">Alaska</a></li>
						<li><a href="<?php echo __LOCATION__ . '/delaware/wilmington'; ?>">Delaware</a></li>
					</ul>
				</div>
			</div>
			<div id="mapsHeadFoot"></div>
		</div>
		<!-- LISTING / MESSAGES -->
		<?php
			if($type == 'message'){
		?>
		<?php
			if(!isset($_SESSION['logged_in_id'])){
				echo '<div class="inboxCountTroller">1</div>';
			} else {
				$msgCount = $this->Controller->getMessageCount($_SESSION['logged_in_id']);
				if($msgCount > 0){
					echo '<div class="inboxCount">'.$msgCount.'</div>';
				}
			}
		
			if(isset($_SESSION['logged_in_id'])){
		?>
			<div id="msgWrap">
				<div id="listingHead"><img src="<?php echo __LOCATION__ . './assets/images/message-head-icon.png'; ?>" alt="<?php echo $_SESSION['logged_in_user'].'\'s messages'?>"></div>
				<div id="messages">
					<?php
						$userMessages = $this->Controller->getUserMessages($_SESSION['logged_in_id']);
						$totalUsers = $userMessages['count'];
						$messageThread = $userMessages['thread']; 
						if($totalUsers > 0){
							$data = array();
							$i=0;
							foreach($messageThread as $message){
								$messageUserOne = $message['user_one'];
								$messageUserTwo = $message['user_two'];
								if($messageUserOne == $_SESSION['logged_in_id']){
									$msg = $this->Controller->getFirstUserMessage($messageUserTwo); 
								} else {
									$msg = $this->Controller->getSecondUserMessage($messageUserOne); 
								}
								$data[$i]['chat_id'] = $msg['id'];
								$data[$i]['parent'] = $msg['parent'];
								$data[$i]['status'] = $msg['status'];
								$data[$i]['user_one'] = $msg['user_one'];
								$data[$i]['user_two'] = $msg['user_two'];
								$data[$i]['message_type'] = $msg['message_type'];
								$data[$i]['created_at'] = $msg['created_at'];
								$data[$i]['user_id'] = $msg['user_id'];
								$data[$i]['username'] = $msg['username'];
								$data[$i]['profile_pic'] = $msg['profile_pic'];
								$i++;
							}
							function cmp($a,$b){
								return strcmp($a["created_at"],$b["created_at"]);
							}
							usort($data, "cmp");
							$totalData = count($data);
							$i=0;
							while($i < $totalData){
								if($msg['profile_pic'] == 'no-profile.png'){
									$thumbPic = __LOCATION__ . '/assets/images/thumb-no-profile.png';
								} else {
									$thumbPic = __LOCATION__  . '/assets/user-images/'.$data[$i]['user_id'].'/thumbsmall-'.$data[$i]['profile_pic'];
								}
								echo '<div class="headMsgWrap clearfix '.$data[$i]['status'].'" data-chat="'.$data[$i]['user_id'].'">';
								echo	 '<div class="headMsgImg"><img src="'.$thumbPic.'"></div>';
								if($msg['message_type'] == 'mt'){
									$msgHead = '<span class="headMsgName">'.$data[$i]['username'].'</span> sent a text message';
								} else {
									$msgHead = '<span class="headMsgName">'.$data[$i]['username'].'</span> sent a photo';
								}
								$date = strtotime($data[$i]['created_at']);
								$date = date('M d, Y g:i a', $date);
								echo	 '<div class="headMsgText">';
								if($data[$i]['message_type'] == 'me' || $data[$i]['message_type'] == 'mp'){
									echo		'<span class="chatDesc">'.$msgHead.'</span><br/>';
								} else {
									echo		'<span class="chatText">'.$msgHead.'</span><br/>';
								}
								echo		'<span class="headMsgDate">'.$date.'</span>';
								echo	'</div>';
								echo '</div>';
								$i++;
							}
						}
					?>
				</div>
				<div id="listingFoot">
					<ul id="listingFootList">
						<li><a href="https://www.facebook.com/BudVibescom-691043380996432/?ref=hl" target="_blank">Facebook</a></li>
						<li><a href="https://twitter.com/Bud_Vibes" target="_blank">Twitter</a></li>
						<li><a href="https://plus.google.com/103465185573413976826/about" target="_blank">Google+</a></li>
					</ul>
				</div>
			</div> <!-- END LISTING WRAP -->
			<?php
			 } else {
			?>
				<div id="msgWrap">
					<div class="messages">
						<div id="signInMenu" class="signUpMenu">
							<h3><img src="<?php echo __LOCATION__ . '/assets/images/sign-up-head.png'; ?>" alt="budvibes sign up"></h3>
							<form action="<?php echo __LOCATION__ . '/signup';?>" method="post" id="signUpForm">
								<input type="hidden" id="back_uri" name="back_uri" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
								<input type="text" class="signInput" name="username" placeholder="Username">
								<input type="text" class="signInput" name="email" placeholder="Email">
								<input type="password" class="signInput" name="password" placeholder="Password">
								<input type="password" class="signInput" name="confirmation" placeholder="Confirm Password">
								<input type="submit" class="signSubmit" name="register" value="Sign Up">
							</form>
							<div id="signUp">
								<a href="<?php echo __LOCATION__ . '/login';?>" id="signInLink">&#8592; Log In</a>
							</div>
						</div>
						<div id="listingFoot">
							<ul id="listingFootList">
								<li><a href="https://www.facebook.com/BudVibescom-691043380996432/?ref=hl" target="_blank">Facebook</a></li>
								<li><a href="https://twitter.com/Bud_Vibes" target="_blank">Twitter</a></li>
								<li><a href="https://plus.google.com/103465185573413976826/about" target="_blank">Google+</a></li>
							</ul>
						</div>
					</div>
				</div>
			<?php
			 }
			?>
	  <?php
		} else {
			switch($head){
				case 'washington':
					$headImg = __LOCATION__ . '/assets/images/wash-head-icon.png';
					$alt = 'washington marijuana dispensary listings';
				break;
				case 'colorado':
					$headImg = __LOCATION__ . '/assets/images/dispense-head-icon.png';
					$alt = $region.'&#44;colorado marijuana dispensary listings';
				break;
				case 'oregon':
					$headImg = __LOCATION__ . '/assets/images/org-head-icon.png';
					$alt = 'oregon marijuana dispensary listings';
				break;
				case 'california':
					$headImg = __LOCATION__ . '/assets/images/cal-head-icon.png';
					$alt = $region.'&#44;california marijuana dispensary listings';
				break;
				case 'british-columbia':
					$headImg = __LOCATION__ . '/assets/images/van-head-icon.png';
					$alt = 'british columbia marijuana dispesnary listings';
				break;
				case 'michigan':
					$headImg = __LOCATION__ . '/assets/images/mich-head-icon.png';
					$alt = 'michigan marijuana dispensary listings';
				break;
				case 'arizona':
					$headImg = __LOCATION__ . '/assets/images/arz-head-icon.png';
					$alt = 'arizona marijuana dispensary listings';
				break;
				case 'nevada':
					$headImg = __LOCATION__ . '/assets/images/nev-head-icon.png';
					$alt = 'nevada marijuana dispensary listings';
				break;
				case 'connecticut':
					$headImg = __LOCATION__ . '/assets/images/con-head-icon.png';
					$alt = 'connecticut marijuana dispensary listings';
				break;
				case 'delaware':
					$headImg = __LOCATION__ . '/assets/images/del-head-icon.png';
					$alt = 'delaware marijuana dispensary listings';
				break;
				case 'netherlands':
					$headImg = __LOCATION__ . '/assets/images/nel-head-icon.png';
					$alt = 'amsterdam marijuana dispensary listings';
				break;
				case 'illinois':
					$headImg = __LOCATION__ . '/assets/images/ill-head-icon.png';
					$alt = 'illinois marijuana dispensary listings';
				break;
				case 'alaska':
					$headImg = __LOCATION__ . '/assets/images/ska-head-icon.png';
					$alt = 'alaska marijuana dispensary listings';
				break;
				case 'district-of-columbia':
					$headImg = __LOCATION__ . '/assets/images/dis-head-icon.png';
					$alt = 'vancouver marijuana dispensary listings';
				break;
				case 'maine':
					$headImg = __LOCATION__ . '/assets/images/mne-head-icon.png';
					$alt = 'maine marijuana dispensary listings';
				break;
				case 'massachusetts':
					$headImg = __LOCATION__ . '/assets/images/mass-head-icon.png';
					$alt = 'massachusetts marijuana dispensary listings';
				break;
				case 'minnesota':
					$headImg = __LOCATION__ . '/assets/images/min-head-icon.png';
					$alt = 'minnesota marijuana dispensary listings';
				break;
				case 'montana':
					$headImg = __LOCATION__ . '/assets/images/mon-head-icon.png';
					$alt = 'montana marijuana dispensary listings';
				break;
				case 'new-jersey':
					$headImg = __LOCATION__ . '/assets/images/jer-head-icon.png';
					$alt = 'new jersey marijuana dispensary listings';
				break;
				case 'new-mexico':
					$headImg = __LOCATION__ . '/assets/images/mex-head-icon.png';
					$alt = 'new mexico marijuana dispensary listings';
				break;
				case 'spain':
					$headImg = __LOCATION__ . '/assets/images/spn-head-icon.png';
					$alt = 'spain marijuana dispensary listings';
				break;
			}
		?>
		<div id="listingWrap">
			<div id="listingHead"><img src="<?php echo $headImg; ?>" alt="<?php echo $alt; ?>"></div>
			<div id="listings" class="Scrollable"></div>
			<div id="listingFoot">
				<ul id="listingFootList">
					<li><a href="https://www.facebook.com/BudVibescom-691043380996432/?ref=hl" target="_blank">Facebook</a></li>
					<li><a href="https://twitter.com/Bud_Vibes" target="_blank">Twitter</a></li>
					<li><a href="https://plus.google.com/103465185573413976826/about" target="_blank">Google+</a></li>
				</ul>
			</div>
		</div>
	<?php
	  } 
	?>
		<!-- FORUM -->
		<div id="forumHeadWrap">
			<div id="forumHeadText"><img src="<?php echo __LOCATION__ . '/assets/images/recent-forum.png'; ?>" alt="recent budvibes forum discussions" /></div>
			<div id="forumFeed" class="Scrollable">
				<?php
					//$conn = db_conn();
					//do_recent_forum($conn,3);
				?>
			</div>
			<div id="forumHeadFoot"><a href="<?php echo __LOCATION__ . '/forum/';?>">Go To Forum &#8689;</a></div>
		</div>

		<!-- STRAIN LIBRARY -->
		<div id="libHeadWrap">
			<div id="libHeadText"><img src="<?php echo __LOCATION__ . '/assets/images/recent-strains.png'?>" alt="recently smoked weed strains" /></div>
			<div id="libFeed" class="Scrollable">
				<?php
					//$conn = db_conn();
					//do_recent_smoking($conn,3);
				?>
			</div>
			<div id="libHeadFoot"><a href="<?php echo __LOCATION__ . '/strains/'; ?>">Go To Strains &#8689;</a></div>
		</div>
			<?php
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
			<form action="<?php echo __LOCATION__ . '/ajax/ajax_media_preview.php'; ?>" method="post" enctype="multipart/form-data" class="addPostForm" target="headForm">
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
				<div class="postButtonWrap">
				   <button id="<?php echo $button_id; ?>" class="<?php echo $username; ?>|<?php echo $id; ?>"><?php echo $post_text; ?></button>
				</div>
			</form>
	<?php
		}
	public function generateVideos($items,$ajax=false){
		$i=0;
		$status = array();
		foreach($items as $row){
			$commentId = $row['id'];
			$userId = $row['user_id'];
			$commType = $row['comm_type'];
			$picture = $row['pic'];
			$video = $row['vid'];
			if($commType == 'rvf' ||
			   $commType == 'pvf' ||
			   $commType == 'svf' ||
			   $commType == 'rvv' ||
			   $commType == 'pvv' ||
			   $commType == 'svv'){
				   $photoLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.$picture;
				   $videoLink = __LOCATION__ . '/assets/user-images/'.$userId.'/feed-'.$video;
				   $status[$i]['photo'] = $photoLink;
				   $status[$i]['video'] = $videoLink;
				   $status[$i]['vidtype'] = 'user';
			   }
		 $i++;
		}
		if(!empty($status)){
			$status['code'] = 200;
			$status['type'] = 'videos';
			return json_encode($status);
			exit();
		} else {
			return false;
			exit();
		}
	}
	
	public function generatePhotos($items,$ajax=false){
		$i=0;
		$photoSize=283;
		$status = array();
		foreach($items as $row){
			$commentId = $row['id'];
			$userId = $row['user_id'];
			$commType = $row['comm_type'];
			$picture = $row['pic'];
			$userdir = '../assets/user-images/'.$userId.'/';
			$userphoto = $userdir.$picture;
			$feedpic = $userdir.'feed-'.$picture;
			if($picture && $picture != 'NULL'){
				if(file_exists($feedpic)){
					$ratio = $this->Helper->calculateImageRatio($feedpic);
					$picLink = __LOCATION__ . '/assets/user-images/'.$userId.'/feed-'.$picture;
				    $status[$i]['photo'] = $picLink;
				} else if(file_exists($userphoto)){
					$ratio = $this->Helper->calculateImageRatio($userphoto);
					$picLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.$picture;
					$status[$i]['photo'] = $picLink;
				}
				$newHeight = round($photoSize/$ratio);
			    $status[$i]['iheight'] = $newHeight;
			    $status[$i]['comm_type'] = $commType;
			}
		    $i++;
		}
		if(!empty($status)){
			$status['code'] = 200;
			$status['type'] = 'photos';
			return json_encode($status);
			exit();
		} else {
			return false;
			exit();
		}
	}
		
	public function generateFeed($items,$feedType,$ajax=false){
		$status = array();
		$status['message'] = '';
		foreach($items as $row){
			$commentId = $row['id'];
			$date = $row['created_at'];
			$profilePic = $row['profile_pic'];
			$userId = $row['user_id'];
			$username = $row['username'];
			$userCommId = $row['user_comm_id'];
			$commId = $row['comm_id'];
			$nullRow = @$row['NULL'];
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
			
			if($ajax){
				$status['message'] .= "<div class='commWrap'>";
					                 //ITEM HEAD
				$status['message'] .= $this->generateFeedHead($commentId,$date,$profilePic,$userId,
											$username,$userCommId,$commId,$nullRow,
											$commType,$type,$storeState,$storeRegion,
											$origId,$rating);
					                //ITEM BODY
				$status['message'] .= $this->generateFeedBody($commentId,$commType,$comment,$origId,
										   $username,$picture,$video,$rating,$tags);
											
					                //ITEM REPLIES/FOOTER
				$status['message'] .= $this->generateFeedReplies($feedType,$commType,$commentId,$rating);
				$status['message'] .= "</div>";
				//return json_encode($status);
			} else {
				echo "<div class='commWrap'>";
					//ITEM HEAD
					echo $this->generateFeedHead($commentId,$date,$profilePic,$userId,
											$username,$userCommId,$commId,$nullRow,
											$commType,$type,$storeState,$storeRegion,
											$origId,$rating);
					//ITEM BODY
					echo $this->generateFeedBody($commentId,$commType,$comment,$origId,
										    $username,$picture,$video,$rating,$tags);
											
					//ITEM REPLIES/FOOTER
					echo $this->generateFeedReplies($feedType,$commType,$commentId,$rating);
				echo "</div>";
			}
		}
		
		if($ajax){
		    $status['code'] = 200;
		    $status['type'] = $feedType;
			return json_encode($status);
		}
	}
	
	public function generateFeedReplies($feedType,$commType,$commentId,$rating){
		$footer = '';
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
		
		$footer .= "<div class='repliesWrapper'>";
		$footer .=	"<div class='repliesHead clearfix'>";
		$footer .=		"<div class='replyCount'>";
		$footer .=			"<b><span class='fa fa-reply'></span><span class='replyNum'>".$replyNum."</span> <span class='replyPluralize'>".$replyText." </span><span class='addLink'>+Reply</span></b>";
		$footer .=		"</div>";
		if($commType == 'rf' || $commType == 'rt' || $commType == 'rp' || $commType == 'rvf' || 			  
			$commType == 'rvv' || $commType == 'rll' || $commType == 'rlf' || 
			$commType == 'rllv' || $commType == 'rlvf'){
			$footer .= 	"<div class='shareCount'>";
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
			$footer .= 		$stars;
			$footer .= 	"</div>";
		} else {
			if($commType == 'shsf' || 
			   $commType == 'shst' || 
			   $commType == 'shsp' || 
			   $commType == 'shsll' || 
			   $commType == 'shslf' || 
			   $commType == 'shsmk' || 
			   $commType == 'shsvf' || 
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
			
			$footer .=		"<div class='shareCount'>";
			$footer .=			"<b><span class='fa fa-retweet'></span>".$shareCount."&nbsp;".$share."&nbsp;<span class='shareLink' id='share-".$commentId."'>+Share</span></b>";
			$footer .=		"</div>";
		}
		$footer .=	"</div>";
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
		$footer .= 	"<div class='replyForm clearfix'>";
		$footer .= 	"<form action='../../add-post-photo.php' enctype='multipart/form-data' method='post' class='addPostForm' target='frame-".$commentId."'>";
		$footer .= 	"<input type='hidden' name='post_type' class='post_type' value='".$postVal."'>";
		$footer .= 	'<div class="replyThumb"><img src="'.$userThumb.'"></div>';
		$footer .= 	"<textarea class='userReplyBox' placeholder='Reply..'></textarea>";
		$footer .=		"<div class='replyPhotoButtonWrap'>";
		$footer .=			"<span class='fa fa-camera'></span>";
		$footer .=			"<input class='userReplyFile photoFileButton' type='file' name='post_photo' />";
		$footer .=		"</div>";
		$footer .=		"<div class='replyButtonWrap'><button class='replyButton' id='replyto-".$commentId."'>Reply</button></div>";
		$footer .= 		"<div class='tagPane replyPane'></div>";
		$footer .=		"<iframe class='curFrame' name='frame-".$commentId."' src=''></iframe>";
		$footer .=	"</form>";
		$footer .= 	"</div>";
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
				$footer .= 	"<div class='replyWrap'>";
				$footer .= 		"<div class='replyHead clearfix'>";
				$footer .=			"<div class='repliesHeadImg'>";
				$footer .=				"<img src='".$replyPic."' alt='".$replyUsername."'>";
				$footer .=			"</div>";
				$linkName = $this->Controller->remove_whitespace($replyUsername);
				if($replyUserType == 'user'){
					$replyLink = __LOCATION__ .'/'. $linkName;
				} else {
					$replyLink = __LOCATION__ .'/'. $replyState.'/'.$replyRegion.'/'.$linkName;
				}
			
				$footer .= 			"<div class='repliesHeadName'>";
				$footer .= 				"<p><a class='grab' href='".$replyLink."'>".$replyUsername."</a><br/><span class='explain'>Replied &bull; ".$date."</span></p>";
				if($replyComment != 'NULL' || !($replyComment)){
					$footer .= 	"<p class='replyText'>".$replyComment."</p>";
				} else {
					$footer .= 	"<p class='replyText'></p>";
				}
			
				if($replyPicture != 'NULL' || !$replyPicture || (empty($replyPicture))){
					$footer .= 			"<div class='replyImageCont'><img class='replyImage' src='". __LOCATION__ ."/assets/user-images/".$replyUserId."/feed-".$replyPicture."' alt='".$replyUsername."'></div>";
				}
				$footer .= 			"</div>";
				$footer .= 		"</div>";	
				$footer .= 	"</div>";
			}
		}
		$footer .= "</div>";
		//echo "</div>";
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
			$footer .= '<div class="replyBarNum">
					       <span class="countReplies"><b>'.$replyCount.'</b>&nbsp;'.$reply.'&nbsp;&bull;&nbsp;</span><span class="countShares"><b>0</b> shares<span/>
				        </div>';
		$footer .= '</div>';
		}
		return $footer;
	}
	
	public function generateFeedBody($commentId,$commType,$comment,$origId,$username,
									 $picture,$video,$rating,$tags){
		$body = '';
		$body .= "<div class='commBody'>";
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
				$body .= '<div class="commPostPic" id="test'.$commentId.'">';
				$body .= 	'<div class="linkWrap">';
				if(!preg_match('#<iframe#',$comment) && $picture != 'NULL'){
					$body .= 	'<a href="'.$permalink.'" target="_blank"><img src="'. __LOCATION__ .'/assets/user-images/'.$origId.'/feed-'.$picture.'" alt="'.$username.'\'s link"></a>';
				}
				if($comment != 'NULL'){
					$body .= $comment;
				}
				$body .= 	'</div>';
				$body .= '</div>';
			} else if($commType == 'rllv' || $commType == 'pllv' || $commType == 'sllv' || 
				$commType == 'rlfv' || $commType == 'plfv' || $commType == 'slfv' || 
				$commType == 'shrllv' || $commType == 'shpllv' || $commType == 'shsllv' || 
				$commType == 'shrlfv' || $commType == 'shplfv' || $commType == 'shslfv'){
					$body .= '<div class="commPostPic" id="test'.$commentId.'">';
					$body .= 	'<div class="linkWrap">';
					$body .= 		$video;
					if($comment != 'NULL'){
						$body .= $comment;
					}
					$body .= 	'</div>';
					$body .= '</div>';
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
						$body .= "<div class='commPostVideo'>";
						$body .=	"<video id='video_".$commentId."' class='video-js vjs-default-skin' controls ";
						$body .=	 " preload='auto' width='100%' height='auto' style='position: relative; display: block; margin: 0 auto; padding: 0;' data-setup='{}' 
									poster='". __LOCATION__ ."/assets/user-images/".$origId."/".$picture."'>";
						$body .=	 "<source src='". __LOCATION__ ."/assets/user-images/".$origId."/feed-".$video."'>";
						$body .=	 "<p class='vjs-no-js'>To view this video please enable JavaScript, and consider upgrading to a web browser that <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a></p>";
						$body .= 	"</video>";
						$body .= 	"<script type='text/javascript'>videojs('video_".$commentId."',{},function(){})</script>";
						$body .= "</div>";
					}
				} else if($picture && $picture != 'NULL' && $video == 'NULL'){
					if($commType == 'sf' || $commType == 'st' || $commType == 'sp' || 
					$commType == 'svf' || $commType == 'svv' || 
					$commType == 'sllv' || $commType == 'slfv'){
						$imgLink = __LOCATION__ .'/strainpost/'. $commentId;
					} else {
						$imgLink = __LOCATION__ .'/userpost/'. $commentId;
					}
					$body .=	"<div class='commPostPic' id='testid".$commentId."'>";
					$body .=		"<a href='".$imgLink."' target='_blank'><img src='". __LOCATION__ ."/assets/user-images/".$origId."/feed-".$picture."' alt='".$username."&#39;s post' /></a>";
					$body .=	"</div>";
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
				
						$body .= '<div class="breadcrumbTrail">
								<p class="firstCrumb"><a href="'. __LOCATION__ .'/forum/general/'.$blockLink.'">'.$blockName.'</a></p>
								<p class="secondCrumb"><a href="'. __LOCATION__ .'/forum/general/'.$blockLink.'/'.$threadLink.'">'.$threadName.'</a></p>
							</div>';
					}
					if($commType == 'fg' || $commType == 'shfg'){
						$postClass = 'forumPostText';
					} else {
						$postClass = 'commPostText';
					}
					$body .=		"<div class='".$postClass."'>";
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

								$body .= "<div class='replyWithQuoteHead'>
										<p class='repliedFrom'><i class='original'>originally from &nbsp;</i><a class='grab' href='".$userLink."'>".$replyUser."</a></p>
									</div>";

							if($replyComment != 'NULL'){
								$body .= "<div class='replyWithQuoteWrap'>".$replyComment."</div>";
							}
						}
					}

					if($commType == 'fg' || $commType == 'shfg'){
						$forumMessage = $this->Controller->getForumContent($rating);
						if($forumMessage != 'NULL'){
							$body .= "<p>".$forumMessage."</p>";
						}
					} else {
						$body .=	"<p>".$comment."</p>";
					}

					$body .= "</div>";
				}
			}
			if($tags && $tags != 'NULL'){
				$userTags = explode(",", $tags);
				$body .= '<div class="userTags">';
					foreach($userTags as $key=>$tag){
						$tagLink = $this->Controller->remove_whitespace($tag);
						$body .= '<span class="usertag"><a href="'. __LOCATION__ .'/tags/'.$tagLink.'">'.$tag.'</a></span>';
					}		
				$body .= '</div>';
			}
			$body .=	"</div>";
			return $body;
	}
		
	public function generateFeedHead($commentId,$date,$profilePic,$userId,$username,
									 $userCommId,$commId,$nullRow,$commType,
									 $type,$storeState,$storeRegion,$origId,
									 $rating){
		$head = '';
		$linkName = $this->Controller->remove_whitespace($username);
		$date = strtotime($date);
		$date = date("M j, Y, g:i a", $date);
		if($profilePic != 'no-profile.png'){
			$picLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.'thumb-'.$profilePic;
		} else {
			$picLink = __LOCATION__ . '/assets/images/thumb-'.$profilePic;
		}
			
		$head .= "<div class='commHead clearfix'>";
		$head .= 	"<div class='commHeadImg'>";
		$head .=		"<img src='".$picLink."' alt='".$username."' />";
		$head .=	"</div>";
		$head .= 	"<div class='commHeadName'>";
				if( ($userCommId == $commId && !($nullRow) ) || $commType == 'fg' 
				|| $commType == 'shfg' || $commType == 'smk' || $commType == 'shsmk' || 
				$commType == 'shsf' || $commType == 'shst' || $commType == 'shsp' || 
				$commType == 'shsll' || $commType == 'shslf' || $commType == 'shsvv' || 
				$commType == 'shsvf' || $commType == 'shsllv' || $commType == 'shslfv'){
					if($type == 'user'){
						$link = __LOCATION__ .'/'. $linkName;
					} else {
						$link = __LOCATION__ .'/dispensary/'. $storeState.'/'.$storeRegion.'/'.$linkName;
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
						$head .= "<p class='singcomm'>";
						$head .=	"<a class='grab user-icon' href='".$link."'>".$username."</a>";
						$head .=	"<span class='explain'>&nbsp;shared&nbsp;</span><span class='sharefrom'><a href='". __LOCATION__ ."/".$sharedLinkname."'>".$sharedFrom."</a>'s ".$infoText."</span> ";
						$head .=		"<br/><span class='explain'>".$date."</span>&nbsp;";
						if($commType == 'shsvv' || $commType == 'shsvf' || 
						$commType == 'shst' || $commType == 'shsf' || $commType == 'shsp' || 
						$commType == 'shsll' || $commType == 'shslf' || 
						$commType == 'shsllv' || $commType == 'shslfv'){
							//$strain_link = remove_whitespace($row_result['user_name']);
							//echo		"<a class='grab-prod prod-icon about-text' href='https://www.budvibes.com/strains/".$strain_link."'>".$row_result['user_name']."</a>";
						}
						$head .= "</p>";
					} else {
						$head .=	"<p class='singcomm'><a class='grab user-icon' href='".$link."'>".$username."</a><br/><span class='explain'>posted &bull; ".$date." </span></p>";			
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
							$secondStoreRegion = $secondUser['store_reg'];
							$secondStoreState = $secondUser['store_state'];
							$wallLink = __LOCATION__ .'/dispensary/'. $secondStoreState.'/'.$secondStoreRegion.'/'.$linkName;
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
						$link = __LOCATION__ .'/dispenary/'. $storeState.'/'.$storeRegion.'/'.$linkName;
					}
					if($commType == 'rf' || $commType == 'rt' || $commType == 'rp' || $commType == 'rvf' || 
						$commType == 'rvv' || $commType == 'rll' || $commType == 'rlf' || $commType == 'rllv' || 
						$commType == 'rlfv'){
						$head .=	"<p class='singcomm'><a class='grab' href='".$link."'>".$username."</a><span class='explain'>&nbsp;rated &bull; ".$date."</span></p>
						<p><a class='grab ".$iconClass."' href='".$wallLink."'>".$secondUsername."</a><span> ".$rating." of 5 stars</span></p>";
					} else {
						$head .=	"<p class='singcomm'><a class='grab' href='".$link."'>".$username."</a><span class='explain'>&nbsp;posted &bull; ".$date." </span></p>
						<p><span>to </span><a class='".$grab." ".$iconClass."' href='".$wallLink."'>".$secondUsername."</a><span>'s feed</span></p>";
					}
				}
	$head .=	"</div>";//END CommHeadName
			if(isset($_SESSION['logged_in_id']) && $_SESSION['logged_in_id'] == $userId){
				$head .= '<span class="editLink"><i class="fa fa-angle-down"></i></span>';
				$head .= '<div class="delete"><span id="post|'.$commentId.'|'.$commType.'">Delete this post</span></div>';
			}
	$head .= "</div>";//END commHead
	return $head;
  }
  
  public function generateRecentPosts($posts){
	  foreach($posts as $post){
		  $type = $post['type'];
		  $username = $post['username'];
		  $linkName = $this->Controller->remove_whitespace($username);
		  $storeState = $post['store_state'];
		  $storeRegion = $post['store_reg'];
		  $profilePic = $post['profile_pic'];
		  $commType = $post['comm_type'];
		  $commId = $post['comm_id'];
		  $origId = $post['orig_id'];
		  $pic = $post['pic'];
		//$extension = strtolower(strrchr($row_recent['pic'], '.'));
		if($type  == 'store'){
			$link = __LOCATION__ . '/' .$storeState.'/'.$storeRegion.'/'.$linkName;
		} else {
			$link = __LOCATION__ . '/' .$linkName;
		}
		if($profilePic == 'no-profile.png'){
			$thumbImg = __LOCATION__ . '/assets/images/thumb-no-profile.png';
		} else {
			$thumbImg = __LOCATION__  . '/assets/user-images/'.$commId.'/thumb-'.$profilePic;
		}
		echo '<div class="recentWrap">';
		echo '<div class="recentHead clearfix">';
		echo 	'<div class="recentThumbWrap">';
		echo 		'<img class="recentThumb" src="'.$thumbImg.'" alt="'.$username.'">';
		echo 	'</div>';
		echo 	'<div class="recentNameWrap">';
		echo 		'<h5><a href="'.$link.'">'.$username.'</a></h5>';
		echo 	'</div>';
		echo '</div>';
		$picLink = __LOCATION__ . '/assets/user-images/'.$origId.'/'.$pic;
		$picLinkSmall = __LOCATION__ . '/assets/user-images/'.$origId.'/mobile-'.$pic;
			if($commType == 'rvf' || 
			   $commType == 'pvf' || 
			   $commType == 'svf' || 
			   $commType == 'rvv' || 
			   $commType == 'pvv' || 
			   $commType == 'svv'){
				echo 	'<img class="recentPic" src="'.$picLink.'">';
			} else if($commType == 'rlf' || 
					  $commType == 'plf' || 
					  $commType == 'slf' || 
					  $commType == 'rll' || 
					  $commType == 'pll' || 
					  $commType == 'sll' || 
					  $commType == 'shrlf' || 
					  $commType == 'shplf' || 
					  $commType == 'shslf' || 
					  $commType == 'shrll' || 
					  $commType == 'shpll' || 
					  $commType == 'shsll'){
				  echo '<img class="recentPic" src="'.$picLinkSmall.'" alt="'.$username.'\'s recent post">';
			} else {
				  echo '<img class="recentPic" src="'.$picLinkSmall.'" alt="'.$username.'\'s post photo">';
			}
		echo '</div>';
	  }
  }
  
  public function generateToolTip($curUser,$curUserPics,$relationStatus){
	$i=1;
	$sessionId = $_SESSION['logged_in_id'];
	$username = $curUser['username'];
	$userId = $curUser['id'];
	$userProfilePic = $curUser['profile_pic'];
	$storeState = $curUser['store_state'];
	$storeRegion = $curUser['store_reg'];
	$type = $curUser['type'];
	$nameLink = $this->Helper->createUrl($username);
	if($type == 'store'){
		$userLink = __LOCATION__ . '/' . $storeState . '/' . $storeRegion . '/' . $nameLink;
		$actionType = 'typeStore-'.$userId;
	} else {
		$userLink = __LOCATION__ . '/' . $nameLink;
		$actionType = 'typeUser-'.$userId;
	}
	if($userProfilePic == 'no-profile.png'){
		$picLink = __LOCATION__ . '/assets/images/relation-'.$userProfilePic;
	} else {
		$picLink = __LOCATION__ . '/assets/user-images/'.$userId.'/relation-'.$userProfilePic;
	}
	echo '<div class="userToolTip">';
	echo 	'<div class="relationImg clearfix">';
	echo 		'<img src="'.$picLink.'" alt="'.$username.'">';
	echo 	'</div>';
	echo 	'<div class="relationBody">';
	echo 		'<p><a href="'.$userLink.'">'.$username.'</a></p>';
	echo 		'<div class="midSection">';
				if($curUserPics){
					echo '<div class="relationPics">';
							foreach($curUserPics as $pic){
								$curUserPic = $pic['pic'];
								$smallPicLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.$curUserPic;
								echo '<img src="'.$smallPicLink.'">';
							}
					echo '</div>';
				}
	echo		'</div>';
	echo 	'</div>';
	echo '<div class="userRelationBar clearfix">';
			if($relationStatus){
				echo '<div class="relationButtonWrap" id="'.$actionType.'">';
				echo 	'<span class="unfollow-'.$userId.' relationButton">';
				echo 		'&minus; Unfollow';
				echo	'</span>';
				echo '</div>';
			} else if($sessionId == $userId){
				echo '<div class="whiteBox relationButtonWrap">';
				echo 	'<span class="whiteButton">+</span>';
				echo '</div>';
			} else {
				echo '<div class="relationButtonWrap" id="'.$actionType.'">';
				echo 	'<span class="follow-'.$userId.' relationButton">';
				echo 		'&#43; Follow';
				echo	'</span>';
				echo '</div>';
			}
	echo '</div>';
	echo '</div>';
	
  }
  
  public function appendStoreTime($day,$open,$close){
	 echo '<li class="storeTime">
			<span class="dayTime">'.$day.'</span>
			<span class="openTime">'.$open.'</span>
			&nbsp;-&nbsp;
			<span class="closeTime">'.$close.'</span>
		   </li>';
  }
  
  public function appendStoreTimeEdit($day,$oHour,$cHour,$oMin,$cMin,
									  $oMeridian,$cMeridian,$liClass){
	  echo '<li class="'.$liClass.'">
				<span class="dayTimeEdit">'.$day.'</span>
				<span class="openTimeEdit">
					<span class="oHour">'.$oHour.'</span>:<span class="oMin">'.$oMin.'</span><span class="oampm">'.$oMeridian.'</span>
				</span>
				&nbsp;-&nbsp;
				<span class="closeTimeEdit">
					<span class="cHour">'.$cHour.'</span>:<span class="cMin">'.$cMin.'</span><span class="campm">'.$cMeridian.'</span>
				</span>
			</li>';
  }
  
  
  public function generateMenuItemList($items){
	  $output = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
	  $output .= '<response>';
					foreach($items as $item){
						$id = $item['id'];
						$name = $item['name'];
						$linkName = $this->Helper->createUrl($name);
						$pic = $item['pic'];
						$tags = $item['tags'];
						if(strlen($tags) > 40){
							$tags = substr($tags,0,37);
							$tags .= '...';
						}
						
						$output .= '<product ';
						$output .= 'id="'.$id.'" ';
						$output .= 'name="'.$name.'" ';
						$output .= 'link_name="'.$linkName.'" ';
						$output .= 'pic="'.$pic.'" ';
						$output .= 'tags="'.$tags.'" ';
						$output .= '/>';
						
					}
	  $output .= '</response>';
	  return $output;
  }
  
} //END ApplicationViews Class
?>