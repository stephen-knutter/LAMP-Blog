<?php
	require dirname(dirname(__DIR__)) . '/bv_inc.php';
	require dirname(dirname(__DIR__)) . '/controllers/products_controller.php';
	require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

	$ProdCtrl = new ProductsCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;

	$product = $_GET['id'];
	$product = $ProdCtrl->getProduct($product);

	$Views->addHead('product',$product);
	$Views->doHeader('message');
	$Views->doUserMenu('message');
?>
	<div id="profileWrap" class="clearfix">
		<!-- FOLLOW/UNFOLLOW & MESSAGE BUTTONS -->
		<?php
			$ProdCtrl->generateRelationButtons($product['id'],$product['slug']);
		?>
		<div id="userProfileBasic">
			<?php
				include '_profile_pic.php';
				//INFO BAR
				$ProdCtrl->generateUserCountBar($product['id'],$product['slug'],'feed');
				//BIG BUTTON
				include '_big_button.php';
			?>
			<!-- PRODUCT DESCRIPTION -->
			<div class="prodDesc">
				<h3>Bio</h3>
				<div class="prodBioWrap">
					<?php
						include '_product_bio.php';
					?>
				</div>
			</div>
			<!-- SIMILAR PRODUCTS -->
			<?php
				$similarProds = $ProdCtrl->getSimilarProds($product['tags'],$product['name']);;
				if($similarProds){
					include '_similar_products.php';
				}
			?>
			<!-- TOP POSTERS -->
			<?php
				$ProdCtrl->doTopPosters();
			?>
		</div>

		<div id="rightInfoPane">
			<div class="userFeedHead"><h3>Tell Your Buds</h3></div>
			<div id="userFeed">
					<div id="userFeedWrap" class="clearfix">
						<?php
							$Views->generatePostForm($product['id'],$product['slug'],'product');
						?>
					</div>
			</div>
			<div class="dropPane" data-pane="product-<?php echo $product['id']; ?>" id="start-15">
				<?php
					$ProdCtrl->generateFeed('product',$product['id']);
				?>
			</div>
		</div>

		<div id="recentPane">
			<h3 class="latestHead">Latest</h3>
			<?php
				$ProdCtrl->doRecent();
			?>
		</div>
	</div>
<?php
	$Views->doFooter();
?>
