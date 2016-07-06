<?php
	foreach($menItems as $item){
		$prodId = $item['prod_id'];
		$name = $item['name'];
		$usedFor = $item['used_for'];
		$g = $item['gram'];
		$e = $item['eighth'];
		$f = $item['fourth'];
		$h = $item['half'];
		$o = $item['ounce'];
		$prod = false;
		
		if($prodId != 0){
			$curProduct = $StoresCtrl->getBasicProdInfo($prodId);
			if($curProduct){
				$prod = true;
				$prodPic = $curProduct['pic'];
				$prodName = $curProduct['name'];
				$prodLinkName = $Helper->createUrl($curProduct['name']);
				$prodLink = __LOCATION__ . '/strains/'.$prodLinkName;
				$prodPicLink = __LOCATION__ . '/assets/images/strains/60-'.$prodPic;
			}
		}
		
		echo '<div class="menuPageItem clearfix">';
			if($prod){
				echo '<span class="menuName">
						<a href="'.$prodLink.'" class="grab-prod">'.$prodName.'</a>
					</span>';
			} else {
				echo	'<span class="menuName">'.$name.'</span>';
			}
	?>
				<div class="priceWrap clearfix">
					<span class="menuPrice"><?php echo $g; ?><span class="itemDesrip">&nbsp;G</span></span>
					<span class="menuPrice"><?php echo $e; ?><span class="itemDesrip">&nbsp;1/8</span></span>
					<span class="menuPrice"><?php echo $f; ?><span class="itemDesrip">&nbsp;1/4</span></span>
					<span class="menuPrice"><?php echo $h; ?><span class="itemDesrip">&nbsp;1/2</span></span>
					<span class="menuPrice"><?php echo $o; ?><span class="itemDesrip">&nbsp;Oz</span></span>
				</div>
				<span class="textFor"><?php echo $usedFor; ?></span>
	<?php
				if($prod){
					echo '<div class="strainMenuPic">
							<a href="'.$prodLink.'"><img src="'.$prodPicLink.'" alt="'.$prodName.' weed strain"/></a>
						  </div>';
				}
		echo '</div>';
	}
	?>