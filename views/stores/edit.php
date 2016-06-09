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
	
	$Views->addHead('store-edit',$store);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
	<div id="profileWrap" class="clearfix">
		<div id="userProfileBasic">
			<div id="posterHeadText">
				<h3>Edit Basic</h3>
			</div>
			<?php
				$form = true;
				include '_profile_pic.php';
			?>
			<div id="editWrap">
				<!-- CHANGE USERNAME -->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-username-store.php"  id="editUsernameStoreForm">
						<div class="editTextWrap"><input type="text" id="new_username" name="new_username" value="<?php  echo $store['username']; ?>" placeholder="Username" /></div>
						<div class="editButtonWrap"><input type="submit" id="newNameButton" value="Save" autocomplete="off"/></div>
					</form>
				</div>
				<!-- CHANGE EMAIL -->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-email-store.php"  id="editEmailStoreForm">
						<div class="editTextWrap"><input type="text" id="new_email" name="new_email" value="<?php  echo $store['email']; ?>" placeholder="Email"></div>
						<div class="editButtonWrap"><input type="submit" id="newEmailButton" value="Save" autocomplete="off"/></div>
					</form>
				</div>
				<!--CHANGE PASSWORD -->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-password.php" id="changePassForm">
						<div class="oldPassWrap"><input type="password" id="old_pass" name="old_pass" placeholder="Old Password.."/></div>
						<div class="newPassWrap"><input type="password" id="new_pass" name="new_pass" placeholder="New Password.."/></div>
						<div class="confirmWrap"><input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm.."/></div>
						<div class="editButtonWrap"><input type="submit" id="changePass" value="Save" /></div>
					</form>
				</div>
				<!-- CHANGE WEBSITE -->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-website.php" id="editWebsiteStoreForm">
						<?php
							$site = $store['website'];
							$site = preg_replace('/http:\/\//', '', $site);
						?>
						<div class="editTextWrap"><span class="webFront">http://</span><input id="new_website" type="text" value="<?php if($store['website'] != 'N/A') echo $site?>" autocomplete="off" /></div>
						<div class="editButtonWrap"><input type="submit" id="changeWebsiteButton" value="Save" /></div>
					</form>
				</div>
				<!-- CHANGE PHONE NUMBER -->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-phonenumber.php" id="editPhoneStoreForm">
						<div class="editTextWrap"><input type="text" id="new_phone" name="new_phone" value="<?php  echo $store['phone']; ?>" placeholder="xxx-xxx-xxxx" autocomplete="off"></div>
						<div class="editButtonWrap"><input type="submit" id="newPhoneButton" value="Save" /></div>
					</form>
				</div>
				<!--CHANGE STORE TYPE-->
				<?php
					if($store['store_state'] == 'colorado' || 
					   $store['store_state'] == 'oregon' || 
					   $store['store_state'] == 'washington'){
				?>
					<div class="editBox clearfix">
						<form method="post" action="../../../change-store-type.php" id="changeStoreTypeForm">
							<?php
								if($store['type'] == 'rec'){
									$storeType = 'Recreational';
								} else {
									$storeType = 'Medical';
								}
							?>
							<div class="editTextWrap"><span class="priceBox storeType"><?php echo $storeType; ?></span></div>
							<div class="editButtonWrap"><input type="submit" id="changeStoreButton" value="Save"></div>
						</form>
					</div>

					<div class="storeSelect toggleTip">
						<div class="padBody">
							<span class="medrecType">Recreational</span>
							<span class="medrecType">Medical</span>
						</div>
					</div>
				<?php
				}
				?>
				<!--CHANGE STORE CASH-->
				<div class="editBox clearfix">
					<form method="post" action="../../../change-store-cash.php" id="changeCashTypeForm">
						<?php
							if($store['cash_type'] == 'd'){
								$cashType = 'Debit';
							} else {
								$cashType = 'ATM';
							}
						?>
						<div class="editTextWrap"><span class="priceBox cashType"><?php echo $cashType; ?></span></div>
						<div class="editButtonWrap"><input type="submit" id="changeCashButton" value="Save"></div>
					</form>
				</div>
				
				<div class="cashSelect toggleTip">
					<div class="padBody">
						<span class="newcashType">ATM</span>
						<span class="newcashType">Debit</span>
					</div>
				</div>
			</div>
			<div id="editTimeWrap">
				<!-- EDIT TIMES -->
				<div class="editTimesWrap clearfix">
					<?php
						$storeTimes = $StoresCtrl->getStoreTime($store['id']);
						include '_store_times_edit.php';
					?>
					<div class="storeHour toggleTip">
						<div class="topPad"></div>
						<div class="padBody Scrollable">
						<?php
							for($i=0; $i < 13; $i++){
								echo '<div class="hAmount" id="hour-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
							}
						?>
						<div class="hAmount" id="hour-00"><span class="selectedType">closed</span></div>
						</div>
						<div class="bottomPad"></div>
					</div>

					<div class="storeMin toggleTip">
						<div class="topPad"></div>
						<div class="padBody Scrollable">
						<?php
							for($i=0; $i < 60; $i++){
								if($i < 10){
									echo '<div class="mAmount" id="hour-0'.$i.'"><span class="selectedType">0'.$i.'</span></div>';
								} else {
									echo '<div class="mAmount" id="hour-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
								}
							}
						?>
						</div>
						<div class="bottomPad"></div>
					</div>

					<div class="ampmType toggleTip">
						<div class="padBody">
							<span class="amType">am</span>
							<span class="pmType">pm</span>
						</div>
					</div>
					<input type="submit" name="change_time" id="changeTime" class="updateMenuItem" value="Update" />
				</div>
			</div>
			
			<!-- SPECIALS -->
			<div class="specialsHead">
				<h3>Specials</h3>
			</div>
			<div class="specialsWrap">
				<div id="special" class="clearfix">
					<?php
						$Views->generatePostForm($store['user_id'],$store['slug'],'edit');
					?>
					</div>
					<div class="expDate">
						<span class="expHead">EXPIRATION:</span>
						<span class="dateBox" id="expMonth">MM</span><span class="dateBox" id="expDay">DD</span><span class="dateBox" id="expYear">YYYY</span>
					</div>
			</div>
			<div class="specialMonth toggleTip">
				<div class="topPad"></div>
				<div class="padBody Scrollable">
					<?php
						for($i=1; $i < 13; $i++){
							if($i < 10){
								echo '<div class="mm" id="hour-0'.$i.'"><span class="selectedType">0'.$i.'</span></div>';
							} else {
								echo '<div class="mm" id="hour-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
							}
						}
					?>
				</div>
				<div class="bottomPad"></div>
			</div>
			<div class="specialDay toggleTip">
				<div class="topPad"></div>
				<div class="padBody Scrollable">
					<?php
						for($i=1; $i < 32; $i++){
							if($i < 10){
								echo '<div class="dd" id="hour-0'.$i.'"><span class="selectedType">0'.$i.'</span></div>';
							} else {
								echo '<div class="dd" id="hour-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
							}
						}
					?>
				</div>
				<div class="bottomPad"></div>
			</div>
			<div class="specialYear toggleTip">
				<div class="topPad"></div>
				<div class="padBody Scrollable">
					<?php
						for($i=2015; $i < 2018; $i++){
							if($i < 10){
								echo '<div class="yyyy" id="hour-0'.$i.'"><span class="selectedType">0'.$i.'</span></div>';
							} else {
								echo '<div class="yyyy" id="hour-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
							}
						}
					?>
				</div>
				<div class="bottomPad"></div>
			</div>
			<!-- CURRENT SPECIAL LISTING -->
			<div class="curSpecialWrap">
				<?php
					$special = $StoresCtrl->getCurrentSpecial($store['id']);
					if($special){
						$exp = $special['expiration'];
						@$curExp = date("F j", $exp);
						$pic = $special['photo'];
						$userId = $special['user_id'];
						$username = $special['username'];
						$descrip = $special['description'];
						if($pic == 'budvibes-special.png'){
							$imgLink = __LOCATION__ . '/assets/images/budvibes-special.png';
						} else {
							$imgLink = __LOCATION__ . '/assets/user-images/'.$userId.'/'.$pic;
						}
						include '_store_special.php';
					}
				?>
			</div>
		</div>
		
		<!-- RIGHT STORE PANE -->
		<div id="rightInfoPane">
			<div class="userFeedHead">
				<h3>Menu</h3>
			</div>
			<!-- FEED/RATINGS -->
			<div class="dropPane" data-pane="none-0" id="start-0">
				<div class="defaultType" id="iType">
					<span id="curType">Indica</span>
				</div>
				<br/>
				<div class="menuFormWrap clearfix">
					<form action="add-menu-item.php" method="post" id="addItemForm">
						<input type="hidden" name="prod_type" id="prod_type" value="Indica" />
						<input type="hidden" name="prod_id" id="prod_id" value=0>
						<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off" /><br/>
						<span class="priceBox" id="price_gram">G $<span class="selectDollar">00</span></span>
						<span class="priceBox" id="price_eigth">1/8 $<span class="selectDollar">00</span></span>
						<span class="priceBox" id="price_fourth">1/4 $<span class="selectDollar">00</span></span>
						<span class="priceBox" id="price_half">1/2 $<span class="selectDollar">00</span></span>
						<span class="priceBox" id="price_ounce">Oz $<span class="selectDollar">00</span></span>
						<span class="priceBox menuType">med</span><br/>
						<input type="submit" id="addMenuItem" value="Add New" />
					</form>
					
					<div class="prodType toggleTip">
						<div class="iType menuItem" id="Indica"><span class="selectedType">Indica</span></div>
						<div class="sType menuItem" id="Sativa"><span class="selectedType">Sativa</span></div>
						<div class="hType menuItem" id="Hybrid"><span class="selectedType">Hybrid</span></div>
						<div class="eType menuItem" id="Edible"><span class="selectedType">Edible</span></div>
						<div class="dType menuItem" id="Drink"><span class="selectedType">Drink</span></div>
						<div class="wType menuItem" id="Wax"><span class="selectedType">Wax</span></div>
						<div class="tType menuItem" id="Tincture"><span class="selectedType">Tincture</span></div>
						<div class="oType menuItem" id="Ointment"><span class="selectedType">Ointment</span></div>
						<div class="otherType menuItem" id="Other"><span class="selectedType">Other</span></div>
					</div>

					<div class="dollarPrice toggleTip">
						<div class="topPad"></div>
						<div class="padBody Scrollable">
						<?php
							for($i=0; $i < 1000; $i++){
								echo '<div class="dAmount" id="dollar-'.$i.'"><span class="selectedType">'.$i.'</span></div>';
							}
						?>
						</div>
						<div class="bottomPad"></div>
					</div>

					<div class="menuFor toggleTip">
						<div class="tAmount" id="med"><span class="selectedType">med</span></div>
						<div class="tAmount" id="rec"><span class="selectedType">rec</span></div>
						<div class="tAmount" id="both"><span class="selectedType">both</span></div>
					</div>
				</div>
				
				<!-- MENU ITEMS -->
				<div class="curMenuItems">
					<h3 class="smokesHead">Indicas</h3>
					<?php
						$items = $StoresCtrl->getStoreInd($store['id']);
						if($items){
							include '_menu_list_grams.php';
						}
					?>
				
					<h3 class="smokesHead">Sativas</h3>
					<?php
						$items = $StoresCtrl->getStoreSat($store['id']);
						if($items){
							include '_menu_list_grams.php';
						}
					?>
				
					<h3 class="smokesHead">Hybrids</h3>
					<?php
						$items = $StoresCtrl->getStoreHyb($store['id']);
						if($items){
							include '_menu_list_grams.php';
						}
					?>
					
					<h3 class="edibleHead">Edibles</h3>
					<?php
						$items = $StoresCtrl->getStoreEdb($store['id']);
						if($items){
							include '_menu_list_each.php';
						}
					?>
					
					<h3 class="drinksHead">Drinks</h3>
					<?php
						$items = $StoresCtrl->getStoreDrk($store['id']);
						if($items){
							include '_menu_list_each.php';
						}
					?>
					
					<h3 class="waxesHead">Waxes</h3>
					<?php
						$items = $StoresCtrl->getStoreWax($store['id']);
						if($items){
							include '_menu_list_wax.php';
						}
					?>
					
					<h3 class="tincturesHead">Tinctures</h3>
					<?php
						$items = $StoresCtrl->getStoreTin($store['id']);
						if($items){
							include '_menu_list_each.php';
						}
					?>
					
					<h3 class="ointmentsHead">Ointments</h3>
					<?php
						$items = $StoresCtrl->getStoreOnt($store['id']);
						if($items){
							include '_menu_list_each.php';
						}
					?>
					
					<h3 class="othersHead">Other</h3>
					<?php
						$items = $StoresCtrl->getStoreOth($store['id']);
						if($items){
							include '_menu_list_each.php';
						}
					?>
				</div>
				
			</div>
		</div>
		
		<div id="recentPane"></div>
	</div>
<?php
	$Views->doFooter();
?>