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
												   'menu');
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
			<div class="budFeedHead">
				<h3><?php echo $store['username']; ?>'s Menu</h3>
			</div>
			<div class="dropPane" style="width: 596px;" data-pane="none-0" id="start-0">
				<?php
					$menuCheck = $StoresCtrl->checkMenuCount($store['id']);
					if(!$menuCheck){
						$storeAvaliable = $StoresCtrl->getStoreAvaliable($store['id']);
						if($storeAvaliable){
							foreach($storeAvaliable as $prod){
								$smoke = $prod['smk'] == 'y' ? true : false;
								$tincture = $prod['tin'] == 'y' ? true : false;
								$other = $prod['other'] == 'y' ? true : false;
								$ointment = $prod['oin'] == 'y' ? true : false;
								$wax = $prod['wax'] == 'y' ? true : false;
								$edible = $prod['edb'] == 'y' ? true : false;
								$drink = $prod['drk'] == 'y' ? true : false;
							}
						}
					}
				?>
				<div class="menuWrap">
					<div class="smoke">
						<div class="menuHead">
							<span class="menuHeadText smokeSat">Sativa</span>
							<?php
								include '_menu_head_grams.php';
							?>
						</div>
						<?php
							if($smoke){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
								
							}
						?>
						
						<div class="menuHead">
							<span class="menuHeadText smokeInd">Indica</span>
							<?php
								include '_menu_head_grams.php';
							?>
						</div>
						<?php
							if($smoke){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
						<div class="menuHead">
							<span class="menuHeadText smokeHyb">Hybrid</span>
							<?php
								include '_menu_head_grams.php';
							?>
						</div>
						<?php
							if($smoke){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="wax">
						<div class="menuHead">
							<span class="menuHeadText waxIcon">Wax</span>
							<?php
								include '_menu_head_wax.php';
							?>
						</div>
						<?php
							if($wax){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="edible">
						<div class="menuHead">
							<span class="menuHeadText edibleIcon">Edible</span>
							<?php
								include '_menu_head_each.php';
							?>
						</div>
						<?php
							if($edible){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="drink">
						<div class="menuHead">
							<span class="menuHeadText drinkIcon">Drink</span>
							<?php
								include '_menu_head_each.php';
							?>
						</div>
						<?php
							if($drink){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="tin">
						<div class="menuHead">
							<span class="menuHeadText dropperIcon">Tincture</span>
							<?php
								include '_menu_head_each.php';
							?>
						</div>
						<?php
							if($tincture){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="oin">
						<div class="menuHead">
							<span class="menuHeadText ointIcon">Ointment</span>
							<?php
								include '_menu_head_each.php';
							?>
						</div>
						<?php
							if($ointment){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
				
					<div class="other">
						<div class="menuHead">
							<span class="menuHeadText otherIcon">Other</span>
							<?php
								include '_menu_head_each.php';
							?>
						</div>
						<?php
							if($other){
								echo '<div class="shareMsg">Available</div>';
							} else if($menuCheck){
									
							}
						?>
					</div>
					
				</div>
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
	$Views->doFooter();
?>