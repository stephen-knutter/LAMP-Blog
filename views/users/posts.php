<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/users_controller.php';
	require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
	
	$UsersCtrl = new UsersCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	#FOR PRODUCTION ONLY
	if(__MODE__ == 'PRODUCTION'){
		$UsersCtrl->checkUrl();
	}
	
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
					/*INFO BAR*/
					$UsersCtrl->generateUserCountBar($user['id'],$user['slug'],'post');
				?>
				
				<!-- TOP STRAINS -->
				<?php
					$UsersCtrl->doTopStrains();
				?>
				
				<!-- TOP POSTERS -->
				<?php
					$UsersCtrl->doTopPosters();
				?>
				
			</div>
			
			<div id="rightInfoPane">
				<div class="postFeedHead">
				   <h3><?php echo $user['username'].'\'s posts'; ?></h3>
				</div>
				<div class="dropPane clearfix" style="clear: left;" data-pane="posts-<?php echo $user['id']; ?>" id="start-15">
				<?php
					$UsersCtrl->generateFeed('posts',$user['id']);
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
	