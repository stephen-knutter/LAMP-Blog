/*EDIT STORE PAGE*/

/*EDIT BASIC*/
$(function(){
	var toggles = $(".toggleTip");
	//EDIT BASIC CLICKS
	var storeClick = true;
	var cashClick = true;
	//EDIT MENU CLICKS
	var bodyClick = true;
	var dollarClick = true;
	var menClick = true;
	//EDIT TIME CLICKS
	var hourClick = true;
	var minClick = true;
	var ampmClick = true;
	//EDIT SPECIAL CLICKS
	var monthClick = true;
	var dayClick = true;
	var yearClick = true;
	
	$button = null;
	var formWrap = $(".menuFormWrap");
	var editForm = formWrap.find("form");
	
	/*CHANGE STORE TYPE*/
	//STORE TYPE SELECTOR
	$("body").on("click", ".storeType", function(){
		toggles.css("display", "none");
		//bodyClick = true;
		storeClick = storeClick ? false : true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		$button = $(this);
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!storeClick){
			$(".storeSelect").css({
				"top": offTop  - 110 +"px",
				"left": offLeft - 195 +"px",
				"display" : "block"
			});
		} else {
			$(".storeSelect").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".medrecType", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.html();
		$button.html(newAmount);
		$amtButton.parent("div.padBody").parent("div.storeSelect").css("display","none");
		cashClick = true;
		storeClick = true;
	});
	
	$("body").on("submit", "#changeStoreTypeForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#changeStoreButton");
		var buttonVal = button.val();
		var newTypeContain = $form.find("div.editTextWrap").find("span.storeType")
		var newType = newTypeContain.html();
		var url = $form.attr("action");
		var url = url+"?type="+newType;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			url: url,
			success: function(result){
				if(result == 0){
					$form.before("<p class='error'>internal error</p>");
				} else if(result == 1){
					$form.before("<p class='success'>updated successfully</p>");
				} 
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		});
	});
	
	
	//CASH TYPE SELECTOR
	$("body").on("click", ".cashType", function(){
		toggles.css("display", "none");
		//bodyClick = true;
		cashClick = cashClick ? false : true;
		storeClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		$button = $(this);
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!cashClick){
			$(".cashSelect").css({
				"top": offTop  - 110 +"px",
				"left": offLeft - 195 +"px",
				"display" : "block"
			});
		} else {
			$(".cashSelect").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".newcashType", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.html();
		$button.html(newAmount);
		$amtButton.parent("div.padBody").parent("div.cashSelect").css("display","none");
		cashClick = true;
		storeClick = true;
	});
	
	$("body").on("submit", "#changeCashTypeForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#changeCashButton");
		var buttonVal = button.val();
		var newCashContain = $form.find("div.editTextWrap").find("span.cashType")
		var newCash = newCashContain.html();
		var url = $form.attr("action");
		var url = url+"?cash="+newCash;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			url: url,
			success: function(result){
				if(result == 0){
					$form.before("<p class='error'>internal error</p>");
				} else if(result == 1){
					$form.before("<p class='success'>updated successfully</p>");
				} 
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		});
	});
	
	/*CHANGE USERNAME SUBMIT*/
	$("body").on("submit", "#editUsernameStoreForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#newNameButton");
		var buttonVal = button.val();
		var newNameContain = $form.find("div.editTextWrap").find("input#new_username")
		var newName = newNameContain.val();
		var url = $form.attr("action");
		//var url = url+"?username="+newName;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			type: 'POST',
			data: {username: newName},
			url: url,
			success: function(result){
				//result = result.trim();
				if(result == 0){
					$form.before("<p class='error'>log in to edit</p>");
				} else if(result == 2){
					$form.before("<p class='error'>username already taken</p>");
				} else if(result == 3){
					$form.before("<p class='error'>internal Error</p>");
				} else if(result == 4){
					$form.before("<p class='error'>invalid username</p>");
				} else if(result == 5){
					$form.before("<p class='error'>username cannot be blank</p>");
				} else {
					//newNameContain.val(result);
					window.location = result;
					$form.before("<p class='success'>username changed successfully</p>");
				}
				
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		});
	});
	
	/*CHANGE EMAIL SUBMIT*/
	$("body").on("submit", "#editEmailStoreForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#newEmailButton");
		var buttonVal = button.val();
		var newEmailContain = $form.find("div.editTextWrap").find("input#new_email")
		var newEmail = newEmailContain.val();
		var url = $form.attr("action");
		//url = url+"?email="+newEmail;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			type : 'POST',
			data : {email: newEmail},
			url: url,
			success: function(result){
				if(result == 0){
					$form.before("<p class='error'>log in to edit</p>");
				} else if(result == 2){
					$form.before("<p class='error'>email currently registered</p>");
				} else if(result == 3){
					$form.before("<p class='error'>internal Error</p>");
				} else if(result == 4){
					$form.before("<p class='error'>invalid email</p>");
				} else if(result == 5){
					$form.before("<p class='error'>email cannot be blank</p>");
				} else {
					newEmailContain.val(result);
					$form.before("<p class='success'>email changed successfully</p>");
				}
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		})
	});
	
	/*CHANGE WEBSITE SUBMIT*/
	$("body").on("submit", "#editWebsiteStoreForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#changeWebsiteButton");
		var buttonVal = button.val();
		var newWebsiteContain = $form.find("div.editTextWrap").find("input#new_website");
		var newWebsite = newWebsiteContain.val();
		var url = $form.attr("action");
		//url = url+"?website="+newWebsite;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			type: 'POST',
			data: {website: newWebsite},
			url: url,
			success: function(result){
				if(result == 0){
					$form.before("<p class='error'>internal error</p>");
				} else if(result == 1){
					$form.before("<p class='success'>website changed successfully</p>");
					newWebsiteContain.val("");
				} else if(result == 2){
					$form.before("<p class='error'>invalid web address</p>")
				} else {
					newWebsiteContain.val(result);
					$form.before("<p class='success'>website changed successfully</p>");
				}
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		})
	});
	
	/*CHANGE PHONE SUBMIT*/
	$("body").on("submit", "#editPhoneStoreForm", function(event){
		if(!event){
			event = window.event;
		}
		event.preventDefault();
		$form = $(this);
		var buttonWrap = $form.find("div.editButtonWrap");
		var button = buttonWrap.find("input#newPhoneButton");
		var buttonVal = button.val();
		var newPhoneContain = $form.find("div.editTextWrap").find("input#new_phone");
		var newPhone = newPhoneContain.val();
		var url = $form.attr("action");
		//url = url+"?phone="+newPhone;
		
		$.ajax({
			beforeSend: function(){
				$(".error, .uploadError, .success").remove();
				buttonWrap.prepend('<img class="hourglass" style="position: absolute; left: 110px; bottom: 3px; margin: 0 auto;" src="https://www.budvibes.com/images/hourglass.gif" />');
				button.attr("disabled","disabled");
				button.val("");
			},
			type: 'POST',
			data: {phone: newPhone},
			url: url,
			success: function(result){
				if(result == 0){
					$form.before("<p class='error'>internal error</p>");
				} else if(result == 2){
					$form.before("<p class='error'>one or more fields blank</p>");
				} else if(result == 3){
					$form.before("<p class='error'>invalid email</p>")
				} else {
					newPhoneContain.val(result);
					$form.before("<p class='success'>number changed successfully</p>");
				}
			},
			complete: function(){
				buttonWrap.find("img.hourglass").remove();
				button.attr("disabled", false);
				button.val(buttonVal);
			}
		})
	});
	
	
	/*EDIT MENU*/
	
	//MENU TYPE SELECTOR
	$("body").on("click", "#curType", function(){
		toggles.css("display", "none");
		//bodyClick = true;
		bodyClick = bodyClick ? false : true;
		dollarClick = true;
		menClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		$button = $(this);
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!bodyClick){
			$(".prodType").css({
				"top": offTop - 157 +"px",
				"left": offLeft - 526+"px",
				"display" : "block"
			});
		} else {
			$(".prodType").css({
				"display" : "none"
			});
		}
	
	});
	
	var curItem = $("#curType");
	$("body").on("click", ".menuItem", function(){
		$item = $(this);
		var itemId = $item.attr("id");
		strStart = itemId.indexOf("-")+1;
		strEnd = itemId.length;
		var newItem = itemId.slice(strStart,strEnd);
		//var newItem = $item.find("span").html();
		curItem.html(newItem);
		
		switch(newItem){
			case 'Sativa':
			case 'Indica':
			case 'Hybrid':
				editForm.html(
					'<input type="hidden" name="prod_type" id="prod_type" value="'+newItem+'" />'+
					'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
					'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off" /><br/>'+
					'<span class="priceBox" id="price_gram">G $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox" id="price_eigth">1/8 $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox" id="price_fourth">1/4 $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox" id="price_half">1/2 $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox" id="price_ounce">Oz $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox menuType">med</span><br/>'+
					'<input type="submit" id="addMenuItem" value="Add New" />'
				)
			break;
			case 'Wax':
			editForm.html(
				'<input type="hidden" name="prod_type" id="prod_type" value="'+newItem+'" />'+
				'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
				'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name" autocomplete="off" /><br/>'+
				'<span class="priceBox" id="price_half">.5g $<span class="selectDollar">00</span></span>'+
				'<span class="priceBox" id="price_gram">G $<span class="selectDollar">00</span></span>'+
				'<span class="priceBox menuType">med</span><br/>'+
				'<input type="submit" id="addMenuItem" value="Add New" />'
			)
			break;
			case 'Edible':
			case 'Drink':
			case 'Tincture':
			case 'Ointment':
			case 'Other':
				editForm.html(
					'<input type="hidden" name="prod_type" id="prod_type" value="'+newItem+'" />'+
					'<input type="hidden" name="prod_id" id="prod_id" value=0>'+
					'<input type="text" name="item_name" id="item_name" value="" placeholder="Item Name"/><br/>'+
					'<span class="priceBox" id="price_each">Each $<span class="selectDollar">00</span></span>'+
					'<span class="priceBox menuType">med</span><br/>'+
					'<input type="submit" id="addMenuItem" value="Add New" />'
				)
			break;
		}
		
		$item.parent(".prodType").css("display","none");
		bodyClick = true;
		dollarClick = true;
		menClick = true;
	});
	
	//SELECT DOLLAR CLICKER
	$("body").on("click", ".selectDollar", function(){
		toggles.css("display", "none");
		$button = $(this);
		dollarClick = dollarClick ? false : true;
		bodyClick = true;
		menClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		$(".dollarPrice").css({
				"top": offTop - 160 +"px",
				"left": offLeft - 527+"px",
				"display" : "block"
		});
		
		if(!dollarClick){
			$(".dollarPrice").css({
				"top": offTop - 160 + "px",
				"left": offLeft - 527 + "px",
				"display" : "block"
			});
		} else {
			$(".dollarPrice").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".dAmount", function(){
		$amtButton = $(this);
		var amtId = $amtButton.attr("id");
		strStart = amtId.indexOf("-")+1;
		strEnd = amtId.length;
		var newAmount = amtId.slice(strStart,strEnd);
		//var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div.padBody").parent("div.dollarPrice").css("display","none");
		bodyClick = true;
		dollarClick = true;
		menClick = true;
	})
	
	//MENU TYPE
	$("body").on("click", ".menuType", function(){
		toggles.css("display", "none");
		$button = $(this);
		menClick = menClick ? false : true;
		bodyClick = true;
		dollarClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		$(".menuFor").css({
				"top": offTop - 155 + "px",
				"left": offLeft - 527 + "px",
				"display" : "block"
		});
		
		if(!menClick){
			$(".menuFor").css({
				"top": offTop - 155 + "px",
				"left": offLeft - 527 + "px",
				"display" : "block"
			});
		} else {
			$(".menuFor").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".tAmount", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.attr("id");
		//var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div.menuFor").css("display","none");
		bodyClick = true;
		dollarClick = true;
		menClick = true;
	});
	
	//SUBMIT FORM
	$("body").on("submit", "#addItemForm", function(event){
		event.preventDefault();
		$curForm = $(this);
		var statusUpdate = $("div.dropPane");
		var curButton = $("input#addMenuItem");
		var curButtonText = curButton.val();
		var curTypeHead = $("#curType");
		var menuAppend = $(".curMenuItems");
		var addNewFormWrapper = $(".menuFormWrap");
		var data = null;
		//CURRENT PRODUCT BEING ADDED
		var curProd = $curForm.find("input#prod_type").val();
		//DETECT IF SYNCED WITH PRODUCT DB
		var prodSync = $curForm.find("input#prod_id").val();
		//MENU TYPE
		var menuType = $curForm.find(".menuType").html();
		//ITEM NAME
		var itemName = $curForm.find("input#item_name").val();
		switch(curProd){
			case 'Sativa':
			case 'Indica':
			case 'Hybrid':
				var gramPrice = $("#price_gram");
				var gramDollar = gramPrice.find(".selectDollar").html();
				var eigthPrice = $("#price_eigth");
				var eigthDollar = eigthPrice.find(".selectDollar").html();
				var fourthPrice = $("#price_fourth");
				var fourthDollar = fourthPrice.find(".selectDollar").html();
				var halfPrice = $("#price_half");
				var halfDollar = halfPrice.find(".selectDollar").html();
				var ouncePrice = $("#price_ounce");
				var ounceDollar = ouncePrice.find(".selectDollar").html();
				data = {gram_doll: gramDollar, 
				eigth_doll: eigthDollar, 
				fourth_doll: fourthDollar,
				half_doll: halfDollar,
				ounce_doll: ounceDollar,
				prod_type: curProd, prod_id: prodSync, 
				item_name: itemName, menu_type: menuType}
			break;
			case 'Wax':
				var halfPrice = $("#price_half");
				var halfDollar = halfPrice.find(".selectDollar").html();
				var gramPrice = $("#price_gram");
				var gramDollar = gramPrice.find(".selectDollar").html();
				data = {half_doll: halfDollar, gram_doll: gramDollar,
						prod_type: curProd, prod_id: prodSync,
						item_name: itemName, menu_type: menuType}
			break;
			case 'Edible':
			case 'Drink':
			case 'Tincture':
			case 'Wax':
			case 'Ointment':
			case 'Other':
				var eachPrice = $("#price_each");
				var eachDollar = eachPrice.find(".selectDollar").html();
				data = {each_doll: eachDollar, 
				prod_type: curProd, prod_id: prodSync, 
				item_name: itemName, menu_type: menuType}
			break;
		}
		
		url = __LOCATION__ + '/ajax/ajax_store_add_menu_item.php';
		
		$.ajax({
			beforeSend: function(){
				$(".error,.success").remove();
				curButton.attr("disabled","disabled").val('');
			},
			type: 'POST',
			url: url,
			data: data,
			success: function(result){
				if(result){
					$result = $.parseJSON(result);
					iStatus = $result.code;
					switch(iStatus){
						case 401:
						case 500:
						case 501:
							statusUpdate.prepend("<p class='error'>"+$result.status+"</p>");
						break;
						default:
							var itemId = $result.id;
							var storeId = $result.store_id;
							var prodId = $result.prod_id;
							var prodLabel = $result.prod_label;
							var itemName = $result.name;
							var usedFor = $result.used_for;
							switch(prodLabel){
								case 'indica':
								case 'sativa':
								case 'hybrid':
									g = $result.g;
									e = $result.e;
									f = $result.f;
									h = $result.h;
									o = $result.o;
									$curForm.html(doFlwrMenu(prodLabel));
									menuAppend
									 .prepend(doNewFlwrItem(itemId,
									                        storeId,
															prodId,
															prodLabel,
															itemName, 
															usedFor,
															g,e,f,h,o)
											  );
								break;
								case 'wax':
									g = $result.g;
									h = $result.h;
									$curForm.html(doWaxMenu(prodLabel));
									menuAppend
									  .prepend(doNewWaxItem(itemId,
									                        storeId,
															prodId,
															prodLabel,
															itemName, 
															usedFor,
															g,h)
											   );
								break;
								case 'edible':
								case 'drink':
								case 'ointment':
								case 'tincture':
								case 'other':
									e = $result.e;
									$curForm.html(doSingleMenu(prodLabel));
									menuAppend
										.prepend(doNewSingleItem(itemId,
																 storeId,
																 prodId,
																 prodLabel,
																 itemName, 
																 usedFor,
																 e)
												);
								break;
							}
						break;
					}
				} else {
					statusUpdate.prepend("<p class='error'>Internal error</p>");
				}
			},
			complete: function(){
				curButton.attr("disabled",false).val(curButtonText);
			}
		});
		
	});
	
	//UPDATE MENU ITEM HANDLER
	$("body").on("click", ".updateMenuItem", function(){
		$editButton = $(this);
		var buttonText = $editButton.val();
		var curParent = $editButton.parent("div.editItemWrap");
		var curMenType = curParent.find("input.prod_label").val();
		
		var data = null;
		//CURRENT PRODUCT BEING ADDED
		var curProd = curParent.find("input.prod_label").val();
		//DETECT IF SYNCED WITH PRODUCT DB
		var prodSync = curParent.find("input.prod_id").val();
		//ITEM NAME
		var itemName = curParent.find("input.item_name").val();
		//MENU ID
		var itemID = curParent.find("input.menu_id").val();
		//USED FOR
		var menuType = curParent.find("span.menuType").html();
		switch(curMenType){
			case 'indica':
			case 'sativa':
			case 'hybrid':
				var gramPrice = curParent.find(".price_gram");
				var gramDollar = gramPrice.find(".selectDollar").html();
				var eigthPrice = curParent.find(".price_eigth");
				var eigthDollar = eigthPrice.find(".selectDollar").html();
				var fourthPrice = curParent.find(".price_fourth");
				var fourthDollar = fourthPrice.find(".selectDollar").html();
				var halfPrice = curParent.find(".price_half");
				var halfDollar = halfPrice.find(".selectDollar").html();
				var ouncePrice = curParent.find(".price_ounce");
				var ounceDollar = ouncePrice.find(".selectDollar").html();
				data = {gram_doll: gramDollar, 
					    eigth_doll: eigthDollar, 
					    fourth_doll: fourthDollar,
					    half_doll: halfDollar,
					    ounce_doll: ounceDollar,
					    prod_type: curProd, 
						prod_id: prodSync, 
					    item_name: itemName, 
						menu_id: itemID, 
						menu_type: menuType}
			break;
			case 'wax':
				var halfPrice = curParent.find(".price_half");
				var halfDollar = halfPrice.find(".selectDollar").html();
				var gramPrice = curParent.find(".price_gram");
				var gramDollar = gramPrice.find(".selectDollar").html();
				data = {half_doll: halfDollar, 
				        gram_doll: gramDollar,
						prod_type: curProd, 
						prod_id: prodSync,
						item_name: itemName, 
						menu_id: itemID, 
						menu_type: menuType}
			break;
			case 'edible':
			case 'drink':
			case 'tincture':
			case 'ointment':
			case 'other':
				var eachPrice = curParent.find(".price_each");
				var eachDollar = eachPrice.find(".selectDollar").html();
				data = {each_doll: eachDollar, 
					    prod_type: curProd, 
						prod_id: prodSync, 
					    item_name: itemName, 
						menu_id: itemID, 
						menu_type: menuType}
			break;
		}
		
		url = __LOCATION__ + '/ajax/ajax_store_change_menu_item.php';
		
		$.ajax({
			beforeSend: function(){
				$(".error,.success").remove();
				$editButton.attr("disabled","disabled").val("");
			},
			url: url,
			type: 'POST',
			data: data,
			success: function(result){
				console.log(result);
				if(result){
					$result = $.parseJSON(result);
					iStatus = $result.code;
					switch(iStatus){
						case 401:
						case 500:
						case 501:
							curParent.prepend("<p class='error'>"+$result.status+"</p>");
						break;
						case 200:
							curParent.prepend("<p class='success'>"+$result.status+"</p>");
						break;
					}
				} else {
					curParent.prepend("<p class='error'>Internal error</p>");
				}
			},
			complete: function(){
				$editButton.attr("disabled",false).val(buttonText);
			}
		});
	});
	
	//DELETE MENU ITEM HANDLER
	$("body").on("click", ".deleteMenuItem", function(){
		$deleteButton = $(this);
		var buttonText = $deleteButton.val();
		var curParent = $deleteButton.parent("div.editItemWrap");
		//MENU ID
		var itemID = curParent.find("input.menu_id").val();
		url = __LOCATION__ + '/ajax/ajax_store_delete_menu_item.php';
		$.ajax({
			beforeSend: function(){
				$(".error,.success").remove();
				$deleteButton.attr("disabled","disabled").val("");
			},
			url: url,
			data: {menu_id: itemID},
			type: 'POST',
			success: function(result){
				if(result){
					$result = $.parseJSON(result);
					iStatus = $result.code;
					switch(iStatus){
						case 401:
						case 500:
						case 501:
							curParent.prepend("<p class='error'>"+$result.status+"</p>");
						break;
						case 200:
							curParent.remove();
						break;
					}
				} else {
					curParent.prepend("<p class='error'>internal error</p>");
				}
			},
			complete: function(){
				$deleteButton.attr("disabled",false).val(buttonText);
			}
		})
	});
	
	
	/*EDIT TIME*/
	
	//SELECT STORE HOUR(OPEN&CLOSE)
	$("body").on("click", ".oHour,.cHour", function(){
		toggles.css("display", "none");
		$button = $(this);
		hourClick = hourClick ? false : true;
		minClick = true;
		ampmClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!hourClick){
			$(".storeHour").css({
				"top": offTop - 890 +"px",
				"left": offLeft - 112+"px",
				"display" : "block"
			});
		} else {
			$(".storeHour").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".hAmount", function(){
		$amtButton = $(this);
		//var amtId = $amtButton.attr("id");
		//strStart = amtId.indexOf("-")+1;
		//strEnd = amtId.length;
		//var newAmount = amtId.slice(strStart,strEnd);
		var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.storeHour").css("display","none");
		hourClick = true;
		minClick = true;
		ampmClick = true;
	});
	
	//SELECT STORE MINUTE(OPEN&CLOSE)
	$("body").on("click", ".oMin,.cMin", function(){
		toggles.css("display", "none");
		$button = $(this);
		minClick = minClick ? false : true;
		hourClick = true;
		ampmClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!minClick){
			$(".storeMin").css({
				"top": offTop - 890+"px",
				"left": offLeft - 92+"px",
				"display" : "block"
			});
		} else {
			$(".storeMin").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".mAmount", function(){
		$amtButton = $(this);
		//var amtId = $amtButton.attr("id");
		//strStart = amtId.indexOf("-")+1;
		//strEnd = amtId.length;
		//var newAmount = amtId.slice(strStart,strEnd);
		var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.storeMin").css("display","none");
		hourClick = true;
		minClick = true;
		ampmClick = true;
	});
	
	//SELECT STORE MINUTE(OPEN&CLOSE)
	$("body").on("click", ".oampm,.campm", function(){
		toggles.css("display", "none");
		$button = $(this);
		minClick = minClick ? false : true;
		hourClick = true;
		ampmClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT SPECIAL CLICKS
		monthClick = true;
		dayClick = true;
		yearClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!minClick){
			$(".ampmType").css({
				"top": offTop - 890+"px",
				"left": offLeft - 88+"px",
				"display" : "block"
			});
		} else {
			$(".ampmType").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".amType,.pmType", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.ampmType").css("display","none");
		hourClick = true;
		minClick = true;
		ampmClick = true;
	});
	
	//SUBMIT TIME CHANGE
	$("#changeTime").on("click",function(){
		$button = $(this);
		var buttonText = $button.val();
		var data = null;
		var timeWrap = $button.parent("div.editTimesWrap");
		var timeListWrap = timeWrap.find("ul.timeListWrap");
		//MONDAY
		var monWrap = timeListWrap.find("li.monTime");
		//MONDAY OPEN
		var monOpenWrap = monWrap.find("span.openTimeEdit");
		var monOhour = monOpenWrap.find("span.oHour").html();
		var monOmin = monOpenWrap.find("span.oMin").html();
		var monOampm = monOpenWrap.find("span.oampm").html();
		//MONDAY CLOSE
		var monCloseWrap = monWrap.find("span.closeTimeEdit");
		var monChour = monCloseWrap.find("span.cHour").html();
		var monCmin = monCloseWrap.find("span.cMin").html();
		var monCampm = monCloseWrap.find("span.campm").html();
		
		//TUESDAY
		var tueWrap = timeListWrap.find("li.tuesTime");
		//TUESDAY OPEN
		var tueOpenWrap = tueWrap.find("span.openTimeEdit");
		var tueOhour = tueOpenWrap.find("span.oHour").html();
		var tueOmin = tueOpenWrap.find("span.oMin").html();
		var tueOampm = tueOpenWrap.find("span.oampm").html();
		//TUESDAY CLOSE
		var tueCloseWrap = tueWrap.find("span.closeTimeEdit");
		var tueChour = tueCloseWrap.find("span.cHour").html();
		var tueCmin = tueCloseWrap.find("span.cMin").html();
		var tueCampm = tueCloseWrap.find("span.campm").html();
		
		//WEDNESDAY
		var wedWrap = timeListWrap.find("li.wedTime");
		//WEDNESDAY OPEN
		var wedOpenWrap = wedWrap.find("span.openTimeEdit");
		var wedOhour = wedOpenWrap.find("span.oHour").html();
		var wedOmin = wedOpenWrap.find("span.oMin").html();
		var wedOampm = wedOpenWrap.find("span.oampm").html();
		//WEDNESDAY CLOSE
		var wedCloseWrap = wedWrap.find("span.closeTimeEdit");
		var wedChour = wedCloseWrap.find("span.cHour").html();
		var wedCmin = wedCloseWrap.find("span.cMin").html();
		var wedCampm = wedCloseWrap.find("span.campm").html();
		
		//THURSDAY
		var thuWrap = timeListWrap.find("li.thuTime");
		//THURSDAY OPEN
		var thuOpenWrap = thuWrap.find("span.openTimeEdit");
		var thuOhour = thuOpenWrap.find("span.oHour").html();
		var thuOmin = thuOpenWrap.find("span.oMin").html();
		var thuOampm = thuOpenWrap.find("span.oampm").html();
		//THURSDAY CLOSE
		var thuCloseWrap = thuWrap.find("span.closeTimeEdit");
		var thuChour = thuCloseWrap.find("span.cHour").html();
		var thuCmin = thuCloseWrap.find("span.cMin").html();
		var thuCampm = thuCloseWrap.find("span.campm").html();
		
		//FRIDAY
		var friWrap = timeListWrap.find("li.friTime");
		//FRIDAY OPEN
		var friOpenWrap = friWrap.find("span.openTimeEdit");
		var friOhour = friOpenWrap.find("span.oHour").html();
		var friOmin = friOpenWrap.find("span.oMin").html();
		var friOampm = friOpenWrap.find("span.oampm").html();
		//FRIDAY CLOSE
		var friCloseWrap = friWrap.find("span.closeTimeEdit");
		var friChour = friCloseWrap.find("span.cHour").html();
		var friCmin = friCloseWrap.find("span.cMin").html();
		var friCampm = friCloseWrap.find("span.campm").html();
		
		//SATURDAY
		var satWrap = timeListWrap.find("li.satTime");
		//SATURDAY OPEN
		var satOpenWrap = satWrap.find("span.openTimeEdit");
		var satOhour = satOpenWrap.find("span.oHour").html();
		var satOmin = satOpenWrap.find("span.oMin").html();
		var satOampm = satOpenWrap.find("span.oampm").html();
		//SATURDAY CLOSE
		var satCloseWrap = satWrap.find("span.closeTimeEdit");
		var satChour = satCloseWrap.find("span.cHour").html();
		var satCmin = satCloseWrap.find("span.cMin").html();
		var satCampm = satCloseWrap.find("span.campm").html();
		
		//SUNDAY
		var sunWrap = timeListWrap.find("li.sunTime");
		//SUNDAY OPEN
		var sunOpenWrap = sunWrap.find("span.openTimeEdit");
		var sunOhour = sunOpenWrap.find("span.oHour").html();
		var sunOmin = sunOpenWrap.find("span.oMin").html();
		var sunOampm = sunOpenWrap.find("span.oampm").html();
		//SUNDAY CLOSE
		var sunCloseWrap = sunWrap.find("span.closeTimeEdit");
		var sunChour = sunCloseWrap.find("span.cHour").html();
		var sunCmin = sunCloseWrap.find("span.cMin").html();
		var sunCampm = sunCloseWrap.find("span.campm").html();
		
		data = {
			mon_ohour: monOhour, mon_omin: monOmin, mon_oampm: monOampm,
			mon_chour: monChour, mon_cmin: monCmin, mon_campm: monCampm,
			tue_ohour: tueOhour, tue_omin: tueOmin, tue_oampm: tueOampm,
			tue_chour: tueChour, tue_cmin: tueCmin, tue_campm: tueCampm,
			wed_ohour: wedOhour, wed_omin: wedOmin, wed_oampm: wedOampm,
			wed_chour: wedChour, wed_cmin: wedCmin, wed_campm: wedCampm,
			thu_ohour: thuOhour, thu_omin: thuOmin, thu_oampm: thuOampm,
			thu_chour: thuChour, thu_cmin: thuCmin, thu_campm: thuCampm,
			fri_ohour: friOhour, fri_omin: friOmin, fri_oampm: friOampm,
			fri_chour: friChour, fri_cmin: friCmin, fri_campm: friCampm,
			sat_ohour: satOhour, sat_omin: satOmin, sat_oampm: satOampm,
			sat_chour: satChour, sat_cmin: satCmin, sat_campm: satCampm,
			sun_ohour: sunOhour, sun_omin: sunOmin, sun_oampm: sunOampm,
			sun_chour: sunChour, sun_cmin: sunCmin, sun_campm: sunCampm,
		}
		
		url = 'https://www.budvibes.com/change-store-time.php';
		
		$.ajax({
			beforeSend: function(){
				$(".error,.success").remove();
				timeWrap.append("<img class='replygear' src='https://www.budvibes.com/images/postgear.gif'>");
				$button.attr("disabled","disabled").val("");
			},
			type: 'POST',
			url: url,
			data: data,
			success: function(result){
				if(result == 0){
					timeListWrap.before("<p class='error'>internal error</p>");
				} else {
					timeListWrap.before("<p class='success'>updated successfully</p>");
				}
			},
			complete: function(){
				$("img.replygear").remove()
				$button.attr("disabled",false).val(buttonText);
			}
		})
	});
	
	//SPEICAL EXPIRATION DATE
	//EXPIRATION MONTH
	$("body").on("click", "#expMonth", function(){
		toggles.css("display", "none");
		$button = $(this);
		monthClick = monthClick ? false : true;
		dayClick = true;
		yearClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;

		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!monthClick){
			$(".specialMonth").css({
				"top": offTop - 75 +"px",
				"left": offLeft - 111+"px",
				"display" : "block"
			});
		} else {
			$(".specialMonth").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".mm", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.specialMonth").css("display","none");
		monthClick = true;
		dayClick = true;
		yearClick = true;
	});
	
	//EXPIRATION DAY
	$("body").on("click", "#expDay", function(){
		toggles.css("display", "none");
		$button = $(this);
		dayClick = dayClick ? false : true;
		monthClick = true;
		yearClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!dayClick){
			$(".specialDay").css({
				"top": offTop - 75 +"px",
				"left": offLeft - 111+"px",
				"display" : "block"
			});
		} else {
			$(".specialDay").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".dd", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.specialDay").css("display","none");
		monthClick = true;
		dayClick = true;
		yearClick = true;
	});
	
	//EXPIRATION YEAR
	$("body").on("click", "#expYear", function(){
		toggles.css("display", "none");
		$button = $(this);
		yearClick = yearClick ? false : true;
		monthClick = true;
		dayClick = true;
		//EDIT BASIC CLICKS
		storeClick = true;
		cashClick = true;
		//EDIT MENU CLICKS
		bodyClick = true;
		dollarClick = true;
		menClick = true;
		//EDIT TIME CLICKS
		hourClick = true;
		minClick = true;
		ampmClick = true;
		
		var buttonOffset = $button.offset();
		offTop = buttonOffset.top;
		offLeft = buttonOffset.left;
		
		if(!yearClick){
			$(".specialYear").css({
				"top": offTop - 75 +"px",
				"left": offLeft - 111+"px",
				"display" : "block"
			});
		} else {
			$(".specialYear").css({
				"display" : "none"
			});
		}
	});
	
	$("body").on("click", ".yyyy", function(){
		$amtButton = $(this);
		var newAmount = $amtButton.find("span").html();
		$button.html(newAmount);
		$amtButton.parent("div").parent("div.specialYear").css("display","none");
		monthClick = true;
		dayClick = true;
		yearClick = true;
	});
	
	/*ADD NEW SPECIAL*/
	$("#addSpecialButton").on("click", function(event){
		event.preventDefault();
		
		$button = $(this);
		var buttonVal = $button.html();
		var buttonParent = $button.parent("div");
		var fileChangeWrap = buttonParent.siblings("#photoFileButtonWrap");
		var fileChange = fileChangeWrap.find("input.photoFileButton");
		var curForm = buttonParent.parent("form");
		var tagPane = curForm.find("div.tagPane");
		var appendWrap = curForm.parent("div").parent("div.specialsWrap");
		var curSpecialWrap = appendWrap.siblings("div.curSpecialWrap");
		var expDate = appendWrap.find("div.expDate");
		
		var xhrType = curForm.find("input.xhr_type").val();
		var postType = curForm.find("input.post_type").val();
		var specialOfferBox = curForm.find("textarea#userFeedBox");
		var specialOffer = specialOfferBox.val();
		var specialImgWrap = tagPane.find("img.postImgPreview");
		var specialImg = specialImgWrap.attr("src");
		if(!specialImg){
			specialImg = 'NULL';
		}
		
		var expMonth = expDate.find("span#expMonth").html();
		var expDay = expDate.find("span#expDay").html();
		var expYear = expDate.find("span#expYear").html();
		
		data = {
			xhr_type: xhrType, post_type: postType,
			special_offer: specialOffer, special_img: specialImg,
			exp_month: expMonth, exp_day: expDay, exp_year: expYear
		}	
		url = __LOCATION__ + '/ajax/ajax_store_add_new_special.php';
		$.ajax({
			beforeSend: function(){
				$(".error,.success").remove();
				$button.attr("disabled", "disabled").html("");
			},
			url: url,
			data: data,
			type: 'POST',
			success: function(result){
				console.log(result);
				if(result){
					$result = $.parseJSON(result);
					iStatus = $result.code;
					switch(iStatus){
						case 401:
						case 500:
						case 501:
							appendWrap.prepend("<p class='error'>"+$result.status+"</p>");
						break;
						case 200:
							//CLEAR OUT FORM
							specialOfferBox.val("");
							tagPane.find("img").remove();
							expDate.find("span#expMonth").html("MM");
							expDate.find("span#expDay").html("DD");
							expDate.find("span#expYear").html("YYYY");
							
							var 
							  name = $result.store_name,
							  desc = $result.desc,
							  photo = $result.photo,
							  date = $result.exp;
							  curSpecialWrap.html(doSpecial(name,desc,photo,exp));
						break;
					}
				} else {
					appendWrap.prepend("<p class='error'>Internal error</p>");
				}
				
				/*
				if(result == 0){
					appendWrap.prepend("<p class='error'>internal error</p>");
				} else if(result == 2){
					appendWrap.prepend("<p class='error'>must be less than 140 characters</p>")
				} else {
					//CLEAR OUT FORM
					specialOfferBox.val("");
					tagPane.find("img").remove();
					expDate.find("span#expMonth").html("MM");
					expDate.find("span#expDay").html("DD");
					expDate.find("span#expYear").html("YYYY");
					
					new_special = $.parseJSON(result);
					if(new_special.photo == 'budvibes-special.png'){
						photoURL = __LOCATION__ + '/assets/images/budvibes-special.png';
					} else {
						photoURL = 'https://www.budvibes.com/user-images/'+new_special.user_id+'/'+new_special.photo;
					}
					curSpecialWrap.html(
						'<div class="curSpecial">'+
							'<div class="curSpecialHead">'+
								'<span class="specialStoreName">'+new_special.store_name+'</span><span class="specialDescrip">'+new_special.desc+'</span>'+
							'</div>'+
							'<div class="curSpecialPic">'+
								'<img class="specialPic" src="'+photoURL+'">'+
							'</div>'+
							'<div class="specialExp">'+
								'<span class="curexpDate">EXPIRES: '+new_special.exp+'</span>'+
							'</div>'+
						'</div>'
					)
				}
				*/
			},
			complete: function(){
				$button.attr("disabled",false).html(buttonVal);
				$("div.buttonToggle").find("input").attr("disabled", false);
				fileChange.val("");
			}
		})
	})
});

