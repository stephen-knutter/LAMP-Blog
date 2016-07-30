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
      $ProdCtrl->generateUserCountBar($product['id'],$product['slug'],'follower');
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
    <?php
      $userFollow = $ProdCtrl->getProdFollowers($product['id']);
      if(empty($userFollow)){
        $followerCount = 0;
      } else {
        $followerCount = $ProdCtrl->getProdFollowersCount($product['id']);
      }
    ?>
    <div class="followersFeedHead">
        <h3><?php  echo 'Followers [ '. $followerCount .' ]'; ?></h3>
    </div>
    <div class="dropPane" data-pane="none-<?php echo $product['id']; ?>" id="start-0">
      <div class="followingWrap">
        <?php
          if(!empty($userFollow)){
            $controller = 'product';
            include(dirname(__DIR__) . '/shared/_followers.inc.php');
          }
        ?>
      </div>
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
