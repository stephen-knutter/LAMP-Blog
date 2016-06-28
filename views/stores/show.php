<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/stores_controller.php';
	require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
	
	$StoresCtrl = new StoresCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	#FOR PRODUCTION ONLY
	if(__MODE__ == 'PRODUCTION'){
		$UsersCtrl->checkUrl();
	}
	
	$sessionId = (int)$_SESSION['logged_in_id'];
	$store = $_GET['id'];
	$storeState = $_GET['state'];
	$storeRegion = $_GET['reg'];
	
	$store = $StoresCtrl->getStore($store);
	
	$Views->addHead('store',$store);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
	<div id="profileWrap" class="clearfix">
		<?php
			$StoresCtrl->generateRelationButtons($store['user_id'],$store['slug']);
		?>
		<div id="userProfileBasic">
			<?php
				if(@$_SESSION['logged_in_user'] == $store['slug']){
					$form = true;
				} else {
					$form = false;
				}
				$barType = 'feed';
				
				include '_profile_pic.php';
				
				$StoresCtrl->generateStoreCountBar($store['user_id'],
												   $store['id'],
				                                   $store['slug'],
												   $store['store_state'],
												   $store['store_reg'],
												   'feed');
				if($store['type'] == 'mdel' || $store['type'] == 'rdel'){
					echo '<div id="deliveryHead">
							<h3>*DELIVERY ONLY*</h3>
						 </div>';
				} else {
					echo '<div id="nearbyHead">
							<h3>Location</h3>
						 </div>';
				}								   
			?>
			<!-- MAP -->
			<div id="storeMapWrap" class="<?php echo $store['lat']."|".$store['lng']; ?>" >
				<div style="width: 405px; height: 420px;" id="nearby-map"></div>
			</div>
			<!-- TIMES -->
			<?php
				$storeTimes = $StoresCtrl->getStoreTime($store['id']);
				include '_store_times.php';
			?>
			<!-- TOP STRAINS -->
			<?php
				$StoresCtrl->doTopStrains();
			?>
			<!-- TOP POSTERS -->
			<?php
				$StoresCtrl->doTopPosters();
			?>
		</div>
		
		<!-- RIGHT STORE PANE -->
		<div id="rightInfoPane">
			<div class="userFeedHead">
				<h3>Tell Your Buds</h3>
			</div>
			<div id="userFeed">
				<div id="userFeedWrap" class="clearfix">
					<?php
						$Views->generatePostForm($store['user_id'],$store['slug'],'store',$sessionId);
					?>
				</div>
			</div>
			<div class="dropPane" data-pane="feed-<?php echo $store['user_id']; ?>" id="start-15">
				<?php
					$StoresCtrl->generateFeed('feed',$store['user_id']);
				?>
			</div>
		</div>
		
		<div id="recentPane">
			<h3 class="latestHead">Latest</h3>
			<?php
				$StoresCtrl->doRecent();
			?>
		</div>
	</div>
<?php
	if(__MODE__ == 'DEVELOPMENT'){
		echo '<pre class="devBox">'.print_r($store).'</pre>';
	}
	$Views->doFooter();
?>