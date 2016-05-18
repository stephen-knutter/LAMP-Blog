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
				$Views->generateProfilePic($user['id'], $user['username'], $user['profile_pic'],$form);
					
				/*INFO BAR*/
				$UsersCtrl->generateUserCountBar($user['id'],$user['slug'],'bud');
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
			<div class="budFeedHead">
				  <h3><?php  echo $user['username']; ?>'s Buds</h3>
			</div>
			<?php
				$userBuds = $UsersCtrl->getUserBuds($user['id']);
			?>
			<div class="dropPane" data-pane="none-<?php echo $user['id']; ?>" id="start-0">
					<?php
						if(!empty($userBuds)){
							include dirname(__DIR__) . '/products/_buds.inc.php';
						}
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