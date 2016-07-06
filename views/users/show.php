<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	$user = $_GET['id'];
	$user = $UsersCtrl->getUser($user);
	
	$Views->addHead('profile',$user);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
<!-- LEFT PANE -->
		<div id="profileWrap" class="clearfix">
			<!-- FOLLOW/UNFOLLOW & MESSAGE BUTTONS -->
			<?php
				$UsersCtrl->generateRelationButtons($user['id'],$user['slug']);
			?>
			
			<div id="userProfileBasic">
				<?php
					if(@$_SESSION['logged_in_user'] == $user['slug']){
						$form = true;
					} else {
						$form = false;
					}
					include '_profile_pic.php';
					//INFO BAR
					$UsersCtrl->generateUserCountBar($user['id'],$user['slug'],'feed');
				?>
				
				<!-- TOP STRAINS -->
				<?php
					$UsersCtrl->doTopStrains();
				?>
		
				<!--NEARBY MAP  !!! -- MOVING TO A BUTTON ON HOME PAGE -- !!!
				<div id="nearbyMap" class="39.739236|-104.990251">
					<div id="nearbyHead">
						<h3>Near You</h3>
					</div>
					<div id="nearby-map"></div>
					<div class="nearbyStoreWrap"></div>
				</div>
				-->	
				
				<!-- TOP POSTERS -->
				<?php
					$UsersCtrl->doTopPosters();
				?>
				
			</div>
			
			<div id="rightInfoPane">

				<div class="userFeedHead"><h3>Tell Your Buds</h3></div>
				<div id="userFeed">
					<div id="userFeedWrap" class="clearfix">
						<?php
							$Views->generatePostForm($user['id'],$user['slug'],'post');
						?>
					</div>
				</div>
				
				<div class="dropPane" data-pane="feed-<?php echo $user['id']; ?>" id="start-15">
					<?php
						$UsersCtrl->generateFeed('feed',$user['id']);
					?>
				</div>
			</div>
			
			<div id="recentPane">
				<h3 class="latestHead">Latest</h3>
				<?php
					$UsersCtrl->doRecent();
				?>
			</div>
			
		</div>
<?php
	$Views->doFooter();
?>