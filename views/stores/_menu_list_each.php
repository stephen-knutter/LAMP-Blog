<?php
	foreach($items as $item){
		$itemId = $item['id'];
		$itemName = $item['name'];
		$storeId = $item['store_id'];
		$prodId = $item['prod_id'];
		$prodLabel = $item['prod_label'];
		$singlePrice = $item['single_price'];
		$usedFor = $item['used_for'];
?>
<div class="editItemWrap clearfix">
	<input type="hidden" name="menu_id" class="menu_id" value="<?php echo $itemId; ?>" />
	<input type="hidden" name="store_id" class="store_id" value="<?php echo $storeId; ?>" />
	<input type="hidden" name="prod_id" class="prod_id" value="<?php echo $prodId; ?>" />
	<input type="hidden" name="prod_label" class="prod_label" value="<?php echo $prodLabel; ?>" />
	<input type="text" name="item_name" class="item_name" value="<?php echo $itemName; ?>" placeholder="Item Name" autocomplete="off"/><br/>
	<span class="priceBox price_each">Each $<span class="selectDollar"><?php echo $singlePrice; ?></span></span>
	<span class="priceBox menuType"><?php echo $usedFor; ?></span><br/>
	<input type="submit" name="update" class="updateMenuItem" value="Update" />
	<input type="submit" name="delete" class="deleteMenuItem" value="Delete" />
</div>
<?php
}
?>