<?php
	foreach($items as $item){
		$itemId = $item['id'];
		$itemName = $item['name'];
		$storeId = $item['store_id'];
		$prodId = $item['prod_id'];
		$prodLabel = $item['prod_label'];
		$g = $item['gram'];
		$e = $item['eighth'];
		$f = $item['fourth'];
		$h = $item['half'];
		$o = $item['ounce'];
		$usedFor = $item['used_for'];
?>
<div class="editItemWrap clearfix">
	<input type="hidden" name="menu_id" class="menu_id" value="<?php echo $itemId; ?>" />
	<input type="hidden" name="store_id" class="store_id" value="<?php echo $storeId; ?>" />
	<input type="hidden" name="prod_id" class="prod_id" value="<?php echo $prodId; ?>" />
	<input type="hidden" name="prod_label" class="prod_label" value="<?php echo $prodLabel; ?>" />
	<input type="text" name="item_name" class="item_name" value="<?php echo $itemName; ?>" placeholder="Item Name" autocomplete="off"/><br/>
	<span class="priceBox price_gram">G $<span class="selectDollar"><?php echo $g; ?></span></span>
	<span class="priceBox price_eigth">1/8 $<span class="selectDollar"><?php echo $e; ?></span></span>
	<span class="priceBox price_fourth">1/4 $<span class="selectDollar"><?php echo $f; ?></span></span>
	<span class="priceBox price_half">1/2 $<span class="selectDollar"><?php echo $h; ?></span></span>
	<span class="priceBox price_ounce">Oz $<span class="selectDollar"><?php echo $o; ?></span></span>
	<span class="priceBox menuType"><?php echo $usedFor; ?></span><br/>
	<input type="submit" name="update" class="updateMenuItem" value="Update" />
	<input type="submit" name="delete" class="deleteMenuItem" value="Delete" />
</div>
<?php
}
?>