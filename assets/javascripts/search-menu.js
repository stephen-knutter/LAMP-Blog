function start(){
	var textBox = new AutoSuggest(document.getElementById("item_name"), new suggestionProvider());	
}

function createXmlHttp(){
	var xmlHttp;
	try{
		xmlHttp = new XMLHttpRequest();
	} catch(e){
		xmlVersions = Array('MSXML2.XMLHTTP.6.0',
							'MSXML2.XMLHTTP.5.0',
							'MSXML2.XMLHTTP.4.0',
							'MSXML2.XMLHTTP.3.0',
							'MSXML2.XMLHTTP',
							'Microsoft.XMLHTTP');
		for(var i=0; i<xmlVersions.length && !xmlHttp; i++){
			try{
				xmlHttp = new ActiveXObject(xmlVersions[i]);
			} catch(e){}
		}
	}
	if(!xmlHttp){
		alert("XmlHttp Error");
	} else {
		return xmlHttp;
	}
}

function AutoSuggest(textBox, oprovider){
	this.cur = -1;
	this.layer = null;
	this.provider = oprovider;
	this.textbox = textBox;
	this.timeoutId = null;
	this.curDir = textBox.parentNode.getAttribute("id");
	this.userText = textBox.value;
	this.init();
	this.prod_id = document.getElementById("prod_id");
}

AutoSuggest.prototype.autosuggest = function(aSuggestions){
	this.cur = -1;
	if(aSuggestions){
		this.showSuggestions(aSuggestions);
	} else {
		this.hideSuggestions();
	}
}

AutoSuggest.prototype.createDropDown = function(){
	this.layer = document.createElement("div");
	this.layer.className = "suggestions";
	this.layer.style.visibility = "hidden";
	this.layer.style.width = 340+"px";
	document.body.appendChild(this.layer);

	var oThis = this;
	this.layer.onmousedown =
	this.layer.onmouseup =
	this.layer.onmouseover = function(event){
		event = event || window.event;
		oTarget = event.target || event.srcElement;
		
		if(event.type == "mousedown"){
			var newProd = oTarget.innerHTML;
			oThis.textbox.value = newProd;
			var newProdId = oTarget.id;
			oThis.prod_id.value = newProdId;
			/*
			if(oTarget.parentNode.href){
				window.location = oTarget.parentNode.href;
			} else if(oTarget.parentNode.parentNode.href){
				window.location = oTarget.parentNode.parentNode.href;
			} else if(oTarget.href){
				window.location = oTarget.href;
			}
			*/
		} else if(event.type == "mouseover"){
			oThis.highlightSuggestions(oTarget);
		} else {
			oThis.textbox.focus();
		}
	}
}

AutoSuggest.prototype.getLeft = function(){
	var oNode = this.textbox;
	var iLeft = 0;
	
	while(oNode != document.body){
		iLeft += oNode.offsetLeft;
		oNode = oNode.offsetParent;
	}
	
	return iLeft;
}

AutoSuggest.prototype.getTop = function(){
	var oNode = this.textbox;
	var iTop = 0;
	
	while(oNode != document.body){
		iTop += oNode.offsetTop;
		oNode = oNode.offsetParent;
	}
	
	return iTop;
}

AutoSuggest.prototype.goToSuggestion = function(iDiff){
	var cSuggestionNodes = this.layer.childNodes;
	
	if(cSuggestionNodes.length > 0){
		var oNode = null;
		
		if(iDiff > 0){
			if(this.cur < cSuggestionNodes.length-1){
				oNode = cSuggestionNodes[++this.cur];
			}
		} else {
			if(this.cur > 0){
				oNode = cSuggestionNodes[--this.cur];
			}
		}
		
		if(oNode){
			this.highlightSuggestions(oNode);
			/*this.textbox.value = oNode.firstChild.nodeValue;*/
		}
	}
};

AutoSuggest.prototype.handleKeyDown = function(event){
	switch(event.keyCode){
		case 38: //UP
			this.goToSuggestion(-1);
		break;
		case 40: //DOWN
			this.goToSuggestion(1);
		break;
		case 27: //ESC
			this.textbox.value = this.userText;
		case 13: //ENTER
			/*
			oTarget = event.target || event.srcElement;
			$current = $("a.current").attr("href");
			//alert($current);
			if($current){
				this.textbox.value = $current.innerHTML;
			}
			*/
		break;
	}
};

AutoSuggest.prototype.handleKeyUp = function(event){
	var iKeyCode = event.keyCode;
	var oThis = this;
	
	this.userText = this.textbox.value;
	clearTimeout(this.timeoutId);
	if(iKeyCode == 8 || iKeyCode == 46){
		this.timeoutId = setTimeout(function(){
			oThis.provider.requestSuggestions(oThis);
		},250)
	} else if((iKeyCode != 16 && iKeyCode < 32) || (iKeyCode >= 33 && iKeyCode < 46) || (iKeyCode >= 112 && iKeyCode <= 123)){
		//ignore				
	} else {
		this.timeoutId = setTimeout(function(){
			oThis.provider.requestSuggestions(oThis);
		},250);
	}
}

AutoSuggest.prototype.hideSuggestions = function(){
	this.layer.style.visibility = "hidden";
}

AutoSuggest.prototype.highlightSuggestions = function(oSuggestions){
	for(var i=0; i < this.layer.childNodes.length; i++){
		var oNode = this.layer.childNodes[i];
		if(oNode == oSuggestions){
			oNode.className = "current";
		} else if(oNode.className == "current"){
			oNode.className = "";
		}
	}
};

AutoSuggest.prototype.showSuggestions = function(aSuggestions){
	var oLink = null;
	var oSpan = null;
	this.layer.innerHTML = "";
	
	var suggestionRoot = aSuggestions.documentElement;
	/*BEER SEARCH RESULTS*/
	var oItems = suggestionRoot.getElementsByTagName("product");
	for(var i=0; i < oItems.length; i++){
		var name = oItems[i].getAttribute("name");
		var linkName = oItems[i].getAttribute("link_name");
		var prodId = oItems[i].getAttribute("id");
		var linkId = oItems[i].getAttribute("link_id");
		var pic = oItems[i].getAttribute("pic");
		oLink = document.createElement("a");
		oSpan = document.createElement("span");
		oImg = document.createElement("img");

		oImg.className = "searchPic";
		oSpan.appendChild(oImg);
		//oSpan.appendChild(document.createTextNode(name));
		oLink.appendChild(document.createTextNode(name));
		oSpan.appendChild(document.createElement("br"));
		//oSpan.className = "storeSearchHead";
		//oLink.appendChild(oSpan);
		oLink.className = "productLink";
		oLink.id = prodId;
		this.layer.appendChild(oLink);
	}
	
	/*POSITION SEARCH BOX*/
	/*this.layer.style.left = this.getLeft() + "px";*/
	this.layer.style.left = 485+"px";
	this.layer.style.top = (this.getTop() + this.textbox.offsetHeight) + "px";

	this.layer.style.position = "absolute";
	this.layer.style.visibility = "visible";
}

AutoSuggest.prototype.init = function(){
	var oThis = this;
	this.textbox.onkeyup = function(event){
		if(!event){
			event = window.event;
		}
		
		oThis.handleKeyUp(event);
	}
	
	this.textbox.onkeydown = function(event){
		if(!event){
			event = window.event;
		}
		
		oThis.handleKeyDown(event);
	}
	
	this.textbox.onblur = function(){
		oThis.hideSuggestions();
	};
	
	this.createDropDown();
}

suggestionProvider = function(){
	this.xhr = createXmlHttp();
}

suggestionProvider.prototype.requestSuggestions = function(AutoSuggestControl){
	var oXHR = this.xhr;
	var qString = "?keyword="+AutoSuggestControl.userText;
	var url = __LOCATION__ + '/ajax/ajax_store_suggest_menu.php'+qString;

	oXHR.open("GET", url, true);
	oXHR.onreadystatechange =  function(){
		if(oXHR.readyState == 4){
			var aSuggestions = oXHR.responseXML;
			AutoSuggestControl.autosuggest(aSuggestions);
		}
	}
	oXHR.send(null);
}

$(function(){
	$("body").on("focus", "#item_name", function(){
		start();
	})
});






