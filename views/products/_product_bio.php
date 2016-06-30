<?php 
	$otherPrice = $product['avg_price'] + 10;
	$otherPercent = $product['avg_thc'] + 2;
	$keywordString = '';
	$keywordArray = explode(',',$product['tags']);
	foreach($keywordArray as $key=>$value){
		$keywordString .= ' <span class="keyword">'.$value.'</span>';
	}
?>
<p><span class="headExpl">Type:</span></p>
<span class="descrip"><?php echo $product['type']; ?> <span class="small">:</span> <?php echo $product['split']; ?></span>
	
<p><span class="headExpl">Avg Price:</span> </p>
<span class="descrip"><span class="small">$</span><?php echo $product['avg_price']; ?> - <span class="small">$</span><?php echo $otherPrice; ?>.00 per 1/8<span class="small">th</span></span>
	
<p><span class="headExpl">Avg THC:</span> </p>
<span class="descrip"><?php echo $product['avg_thc']; ?><span class="small">%</span> - <?php echo $otherPercent;?><span class="small">%</span></span>
	
<p><span class="headExpl">Description: </span> </p>
<span class="descrip"><?php echo $product['descrip']; ?></span>
	
<p><span class="headExpl">Uses:</span> </p>
<span class="descrip"><?php echo $product['tags']; ?></span>