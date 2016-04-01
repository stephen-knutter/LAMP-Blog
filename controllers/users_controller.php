<?php
	require dirname(__DIR__) . '/models/User.php';
	
	class UsersCtrl extends ApplicationCtrl{
		private $username;
		private $email;
		private $password;
		private $confirmation;
		private $UserModel;
		private $Helper;
		private $Views;
		private $errors = array();
		
		public function __construct(){
			$this->UserModel = new User;
			$this->Helper = new ApplicationHelper;
			$this->Views = new ApplicationViews;
		}
		
		/*****
			# NEW USER FUNCTIONS #
			
			 @validate_user();
			 @validate_username();
			 @validate_email();
			 @validate_password();
			 @generateFeed();
		*****/
		public function validate_user($username,$email,$password,$confirmation){
			$this->username = $username;
			$this->email = $email;
			$this->password = $password;
			$this->confirmation = $confirmation;
			
			$name_check = $this->validate_username();
			$name_success = $name_check['success'];
			if(!$name_success){
				$this->errors['username'] = $name_check['username'];
			}
			
			$email_check = $this->validate_email();
			$email_success = $email_check['success'];
			if(!$email_success){
				$this->errors['email'] = $email_check['email'];
			}
			
			$pass_check = $this->validate_password();
			$pass_success = $pass_check['success'];
			if(!$pass_success){
				$this->errors['password'] = $pass_check['password'];
			}
			
			if(empty($this->errors)){
				$user = $this->UserModel->add_user($this->username, $this->email, $this->password);
				if($user){
					#SESSIONS AND REDIRECT
					$this->Helper->log_in($user);
					$this->UserModel->finish_signup($user);
					$this->errors['success'] = 'Success';
					return $this->errors;
					exit();
				} else {
					$this->errors['internal'] = 'Internal error';
					return $this->errors;
				}
			} else {
				return $this->errors;
			}
		}
		
		private function validate_username(){
			return $this->UserModel->validate_username($this->username);
		}
		private function validate_email(){
			return $this->UserModel->validate_email($this->email);
		}
		private function validate_password(){
			return $this->UserModel->validate_password($this->password, $this->confirmation);
		}
		
		/*****
			###USER SHOW FUNCTIONS###
			
			 @get_user();
			 @generate_relation_buttons();
			 @generate_user_count_bar();
			 @do_top_strains()
			 @do_top_posters();
			 @generate_post_form()
			 @generate_feed()
			 @do_recent();
			 
		*****/
		public function getUser($user){
			$user = $this->UserModel->get_user($user);
			if(!empty($user)){
				return $user;
			} else {
				header('Location: ' . __LOCATION__);
			}
		}
		
		public function generateRelationButtons($id,$user) {
		
		?>
		
		<?php
			$relation = $this->UserModel->getRelation($id);
			
			if($relation == 1){
		?>
			<!-- FOLLOW USER PAGE-->
				<div class="message-user" id="message-<?php echo $id; ?>" data-chat="<?php echo $id; ?>">
					<img src="<?php echo __LOCATION__  . '/assets/images/message-icon.png' ?>" alt="message" title="message <?php echo $user; ?>"/>
				</div>
				<div class="follow-message" id="typeUser">	
					<span id="relationLink" class="unfollow-<?php echo $id; ?> unfollowText">Unfollow</span>
				</div>
		<?php			

			} else if($_SESSION['logged_in_id'] == $id) {
		?>
				<!-- EDIT BUTTON USER PAGE -->
				<div class="follow-message" id="typeUser">
					<?php
						if($_SESSION['store']){
							$link_name = $this->remove_whitespace($_SESSION['logged_in_user']);
							$link_state = $this->remove_whitespace($_SESSION['store_state']);
							$link_region = $this->remove_whitespace($_SESSION['store_reg']);
							$store_url = __LOCATION__ . '/'.$link_state.'/'.$link_region.'/'.$link_name;
					?>
						<span id="editLink" class="follow-<?php echo $id; ?> editText"><a href="<?php echo $store_url.'/edit'; ?>">Edit</a></span>
					<?php
						} else {
							$link_name = $this->remove_whitespace($_SESSION['logged_in_user']);
							$user_url = __LOCATION__ . '/'.$link_name.'/edit';
					?>
						<span id="editLink" class="follow-<?php echo $id; ?> editText"><a href="<?php echo $user_url; ?>">Edit</a></span>
					<?php
						}
					?>
					<img class="userEditIcon" src="<?php echo __LOCATION__ . '/assets/images/rss-icon.png' ?>" />
				</div>
		<?php			
			} else {
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
		?>
	<?php
		}
		
		public function generateUserCountBar($id,$user,$type){
			$url = __LOCATION__ .'/'. $user;
			$feedclass = '';
			$postclass = '';
			$photoclass = '';
			$budclass = '';
			$followerclass = '';
			$followingclass = '';
			$videoclass = '';
			switch($type){
				case 'feed':
					$feedclass = 'selected';
				break;
				case 'post':
					$postclass = 'selected';
				break;
				case 'photo':
					$photoclass = 'selected';
				break;
				case 'bud':
					$budclass = 'selected';
				break;
				case 'follower':
					$followerclass = 'selected';
				break;
				case 'following':
					$followingclass = 'selected';
				break;
				case 'video':
					$videoclass = 'selected';
				break;
			}
	
			echo '<div class="infoBarWrap clearfix">';
			echo 	'<a href="'.$url.'"> <div class="feedInfo '.$feedclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalFeed($id).'</span>
						<span class="infoDesc">feed</span>
					</div></a>';
			echo 	'<a href="'.$url.'/posts"><div class="postInfo '.$postclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalPosts($id).'</span>
						<span class="infoDesc">posts</span>
					</div></a>';
			echo 	'<a href="'.$url.'/photos"><div class="photoInfo '.$photoclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalPhotos($id).'</span>
						<span class="infoDesc">photos</span>
					</div></a>';
			echo 	'<a href="'.$url.'/videos"><div class="followingInfo '.$videoclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalVideos($id).'</span>
						<span class="infoDesc">video</span>
					</div></a>';
			echo 	'<a href="'.$url.'/followers"><div class="followersInfo '.$followerclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalFollowers($id).'</span>
						<span class="infoDesc">followers</span>
					</div></a>';
			echo    '<a href="'.$url.'/buds"><div class="budInfo '.$budclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalBuds($id).'</span>
						<span class="infoDesc">buds</span>
					</div></a>';
			echo 	'<a href="'.$url.'/following"><div class="followingInfo '.$followingclass.' clearfix">
						<span class="infoNumber">'.$this->UserModel->generateTotalFollowing($id).'</span>
						<span class="infoDesc">following</span>
					</div></a>';
			echo '</div>';	
		}
		
		public function doTopStrains(){
			echo '<div class="recentStrainsWrap clearfix">';
			echo 	'<div id="strainHeadText">';
			echo		'<h3>Top Strains</h3>';
			echo 	'</div>';
			echo 	'<div class="topStrainsPics clearfix">';
					$topStrains = $this->UserModel->getTopStrains();
					foreach($topStrains as $row){
						if(strlen($row['name']) > 20){
							$name = substr($row['name'],0,20);
							$name = $name.'...';
						} else {
							$name = $row['name'];
						}
						$linkName = $this->remove_whitespace($row['name']);
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
		
		public function doTopPosters(){
			echo '<div class="recentStrainsWrap topPosters clearfix">';
			echo	'<div id="posterHeadText">';
			echo		'<h3>Top Posters</h3>';
			echo 	'</div>';
			echo	'<div class="topStrainsPics clearfix">';
					$topPosters = $this->UserModel->getTopPosters();
					foreach($topPosters as $row){
						if(strlen($row['username']) > 20){
							$name = substr($row_top['username'],0,20);
							$name = $name.'...';
						} else {
							$name = $row['username'];
						}
						$linkName = $this->remove_whitespace($row['username']);
						if($row['type'] == 'store'){
							echo '<a href="'. __LOCATION__ .'/'.$row['store_state'].'/'.$row['store_reg'].'/'.$linkName.'">';
						} else {
							echo '<a href="'. __LOCATION__ .'/'.$linkName.'">';
						}
						if($row['profile_pic'] == 'no-profile.png'){
							$profilePic = __LOCATION__ . "/assets/images/top-no-profile.png";
						} else {
							$profilePic = __LOCATION__ . "/assets/user-images/".$row['user_id']."/top-".$row['profile_pic'];
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
		
		public function generateFeed($feedType,$id,$alt=''){
			switch($feedType){
				case 'feed':
					$results = $this->UserModel->getUserFeed($id);
					$this->Views->generateFeed($results,$feedType);
				break;
				case 'posts':
					$this->Model->doPostsFeed();
				break;
				case 'ajax-posts':
					$this->Model->doAjaxPostsFeed();
				break;
				case 'product':
					$this->Model->doProductFeed();
				break;
				case 'ajax-strainfeed':
					$this->Model->doAjaxStrainFeed();
				break;
				case 'tags':
					$this->Model->doTagsFeed();
				break;
				case 'ajax-search':
					$this->Model->doAjaxSearchFeed();
				break;
				case 'forums':
					$this->Model->doForumFeed();
				break;
				case 'ajax-forums':
					$this->Model->doAjaxForumFeed();
				break;
				case 'ajax-feed':
					$this->Model->doAjaxFeed();
				break;
				case 'ajax-front':
					$this->Model->doAjaxFrontFeed();
				break;
				case 'map':
					$this->Model->doMapFeed();
				break;
				case 'user-post':
					$this->Model->doUserPostFeed();
				break;
				case 'strain-post':
					$this->Model->doStrainPostFeed();
				break;
			}
		}
		
		public function do_recent(){
			
		}
	}//END UserCtrl Class
?>