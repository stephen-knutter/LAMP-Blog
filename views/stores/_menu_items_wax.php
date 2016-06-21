<?php
	foreach($menItems as $item){
		$name = $item['name'];
		$usedFor = $item['used_for'];
		$g = $item['gram'];
		$h = $item['half'];
		
	?>
			<div class="menuPageItem clearfix">
				<span class="menuName"><?php echo $name; ?></span>
				<div class="priceWrap clearfix">
					<span class="waxPrice"><?php echo $g; ?><span class="itemDesrip">&nbsp;.5G</span></span>
					<span class="waxPrice"><?php echo $h; ?><span class="itemDesrip">&nbsp;1/2</span></span>
				</div>
				<span class="textFor"><?php echo $usedFor; ?></span>
			</div>
	<?php
	}
	?>