<?php
	foreach($menItems as $item){
		$name = $item['name'];
		$usedFor = $item['used_for'];
		$e = $item['single_price'];
		
	?>
			<div class="menuPageItem clearfix">
				<span class="menuName"><?php echo $name; ?></span>
				<div class="priceWrap clearfix">
					<span class="singlePrice"><?php echo $e; ?><span class="itemDesrip">&nbsp;each</span></span>
				</div>
				<span class="textFor"><?php echo $usedFor; ?></span>
			</div>
	<?php
	}
	?>