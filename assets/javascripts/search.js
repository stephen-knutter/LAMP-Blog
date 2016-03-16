function createXmlHttp(){
  var xmlHttp;
  try{
    xmlHttp = new XMLHttpRequest();
  } catch(e) {
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
    alert("xmlhttp not supported, we would recommend a new browser such as google chrome");
  } else {
    return xmlHttp;
  }
}

function start(){
  var textBox = new AutoSuggest(document.getElementById("search"), new suggestionProvider());
}

function AutoSuggest(textBox, oprovider){
  this.cur = -1;
  this.layer = null;
  this.provider = oprovider;
  this.textbox = textBox;
  this.timeoutId = null;
  this.userText = textBox.value;
  this.init();
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
  this.layer.style.display = "none";
  this.layer.style.width = 340+"px";//290+"px";
  document.body.appendChild(this.layer);
  
  var oThis = this;
  this.layer.onmousedown=
  this.layer.onmouseup=
  this.layer.onmouseover = function(event){
  event = event || window.event;
  oTarget = event.target || event.srcElement;
    
    if(event.type == "mousedown"){
      if(oTarget.href){
        window.location = oTarget.href;
      } else if(oTarget.parentNode.href){
        window.location = oTarget.parentNode.href;
      } else if(oTarget.parentNode.parentNode.href){
        window.location = oTarget.parentNode.parentNode.href;
      }
    } else if(event.type == "mouseover"){
      oThis.highlightSuggestions(oTarget);
    } else {
      oThis.textbox.focus();
    }
  }
  this.layer.onmouseleave = function(event){
    this.layer.childNodes.className = "";
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
}

AutoSuggest.prototype.handleKeyDown = function(event){
  switch(event.keyCode){
    case 38://up
      this.goToSuggestion(-1);
    break;
    case 40://down
      this.goToSuggestion(1);
    break;
    case 13://enter
      oTarget = event.target || event.srcElement;
      if(this.cur != -1){
        if(this.layer.childNodes[this.cur].href){
		  var newLoc = this.layer.childNodes[this.cur].href.replace(/\s+/g, '-')
          window.location = newLoc;
        } else if(this.layer.childNodes[this.cur].parentNode.href){
		  var newLoc = this.layer.childNodes[this.cur].parentNode.href.replace(/\s+/g, '-')
          window.location = newLoc
        } else if(this.layer.childNodes[this.cur].parentNode.parentNode.href){
	      var newLoc = this.layer.childNodes[this.cur].parentNode.parentNode.href.replace(/\s+/g, '-')
          window.location = newLoc
        }
      } else {
	    var newLoc = this.textbox.value.replace(/\s+/g, '-');
        window.location = 'https://www.budvibes.com/tags/'+newLoc;
      }
    break;
  }
}

AutoSuggest.prototype.handleKeyUp = function(event){
  var iKeyCode = event.keyCode;
  var oThis = this;
  
  this.userText = this.textbox.value;
  clearTimeout(this.timeoutId);
  if(iKeyCode == 8 || iKeyCode == 46){
    this.timeoutId = setTimeout(function(){
      oThis.provider.requestSuggestions(oThis);
    }, 250)
  } else if((iKeyCode != 16 && iKeyCode < 32) || (iKeyCode >= 33 && iKeyCode < 46) || (iKeyCode >= 112 && iKeyCode <= 123)){
    //do nothing
  } else {
    this.timeoutId = setTimeout(function(){
      oThis.provider.requestSuggestions(oThis);
    },250);
  }
}

AutoSuggest.prototype.hideSuggestions = function(){
  this.layer.style.display = "none";
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
}

AutoSuggest.prototype.showSuggestions = function(aSuggestions){
  var oLink = null;
  var oSpan = null;
  this.layer.innerHTML = "";
  
  var suggestionRoot = aSuggestions.documentElement;
	
  //USER AND STORES LOOP
  var aItems = suggestionRoot.getElementsByTagName("user");
  for(var i=0; i < aItems.length; i++){
    var username = aItems[i].getAttribute("username");
    var linkName = aItems[i].getAttribute("link_name");
    var type = aItems[i].getAttribute("type");
    var region = aItems[i].getAttribute("region");
    var state = aItems[i].getAttribute("state");
    var id = aItems[i].getAttribute("id");
    var pic = aItems[i].getAttribute("pic");
    var address = aItems[i].getAttribute("address");
    oLink = document.createElement("a");
    oSpan = document.createElement("span");
    zSpan = document.createElement("span");
    oImg = document.createElement("img");
    oImg.className = "searchPic";
    if(pic == 'no-profile.png'){
      oImg.src = "https://www.budvibes.com/images/relation-"+pic;
    } else {
      oImg.src = "https://www.budvibes.com/user-images/"+id+"/"+"relation-"+pic;
    }
    oSpan.appendChild(oImg);
    oSpan.appendChild(document.createTextNode(username));
    oSpan.appendChild(document.createElement("br"));
    oSpan.className = "storeSearchHead";
    if(type == 'store'){
      zSpan.appendChild(document.createTextNode(address));
      zSpan.className = "storeAddress";
      zSpan.appendChild(document.createElement("br"));
    }
    oLink.appendChild(oSpan);
    oLink.appendChild(zSpan);
    if(type == 'store'){
      oLink.href = "https://www.budvibes.com/"+state+"/"+region+"/"+linkName;
    } else {
      oLink.href = "https://www.budvibes.com/"+linkName;
    }

    this.layer.appendChild(oLink);
  }
  
  //PRODUCTS LOOP
  var bItems = suggestionRoot.getElementsByTagName("product");
  for(var i=0; i < bItems.length; i++){
    var name = bItems[i].getAttribute("name");
    var linkName = bItems[i].getAttribute("link_name");
    var id = bItems[i].getAttribute("id");
    var pic = bItems[i].getAttribute("pic");
    var tags = bItems[i].getAttribute("tags");
    oLink = document.createElement("a");
    oSpan = document.createElement("span");
    zSpan = document.createElement("span");
    oImg = document.createElement("img");
    oImg.className = "searchPic";
    oImg.src = "https://www.budvibes.com/strains/images/60-"+pic;
    oSpan.appendChild(oImg);
    oSpan.appendChild(document.createTextNode(name));
    oSpan.appendChild(document.createElement("br"));
    oSpan.className = "storeSearchHead";
    zSpan.appendChild(document.createTextNode(tags));
    zSpan.className = "storeAddress";
    zSpan.appendChild(document.createElement("br"));
    oLink.appendChild(oSpan);
    oLink.appendChild(zSpan);
    oLink.href = "https://www.budvibes.com/strains/"+linkName;

    this.layer.appendChild(oLink);
    
  }

  //TAGS LOOP
  var cItems = suggestionRoot.getElementsByTagName("comment");
  for(var i=0; i < cItems.length; i++){
    var username = cItems[i].getAttribute("username");
    var linkName = cItems[i].getAttribute("link_name");
    var id = cItems[i].getAttribute("id");
    var userId = cItems[i].getAttribute("user_id");
    var pic = cItems[i].getAttribute("pic");
    var tags = cItems[i].getAttribute("tags");
    var type = cItems[i].getAttribute("type");
    oLink = document.createElement("a");
    oSpan = document.createElement("span");
    zSpan = document.createElement("span");
    oImg = document.createElement("img");
    oImg.className = "searchPic";
    if(type == 'sll' || type == 'slf' || type == 'pll' || type == 'plf' || type == 'rll' || type == 'rlf' || 
    type == 'shsll' || type == 'shslf' || type == 'shpll' || type == 'shplf' || type == 'shrll' || type == 'shrlf'){
      oImg.src = "https://www.budvibes.com/user-images/"+userId+"/"+pic;
    } else {
      oImg.src = "https://www.budvibes.com/user-images/"+userId+"/"+pic;
    }
    oSpan.appendChild(oImg);
    oSpan.appendChild(document.createTextNode(username));
    oSpan.appendChild(document.createElement("br"));
    oSpan.className = "storeSearchHead";
    zSpan.appendChild(document.createTextNode(tags));
    zSpan.className = "storeAddress";
    zSpan.appendChild(document.createElement("br"));
    oLink.appendChild(oSpan);
    oLink.appendChild(zSpan);
    oSearchVal = this.textbox.value.replace(/\s+/g, '-');
    oLink.href = "https://www.budvibes.com/tags/"+oSearchVal;

    this.layer.appendChild(oLink);
    
  }
  
  findLink = document.createElement("a");
  //findLink.appendChild(document.createTextNode("More..."));
  //findLink.id = "moreSearch";
  findLink.id = "moreSearchWrap";
  searchVal = this.textbox.value.replace(/\s+/g, '-');
  findLink.href = "https://www.budvibes.com/tags/"+searchVal;
  findSpan = document.createElement("span");
  findSpan.appendChild(document.createTextNode("More..."));
  findSpan.id = "moreSearch";
  findLink.appendChild(findSpan);
  this.layer.appendChild(findLink);
  
  /*this.layer.style.left = this.getLeft() + "px";*/
  this.layer.style.left = 144+"px";
  /*this.layer.style.top = (this.getTop() + this.textbox.offsetHeight) + "px";*/
  this.layer.style.top = 44+"px";
  this.layer.style.position = "fixed";
  this.layer.style.display = "block";
  
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
  }
  
  this.createDropDown();
}

suggestionProvider = function(){
  this.xhr = createXmlHttp();
}

suggestionProvider.prototype.requestSuggestions = function(AutoSuggestControl){
  var oXHR = this.xhr
  var qString = "?keyword="+AutoSuggestControl.userText;
  var url = 'https://www.budvibes.com/suggest.php'+qString;
  oXHR.open("GET", url, true);
  oXHR.onreadystatechange = function (){
    if(oXHR.readyState == 4){
      var aSuggestions = oXHR.responseXML;
      AutoSuggestControl.autosuggest(aSuggestions);
    }
  }
  oXHR.send(null);
}

$(function(){
  $("#search").focus(function(){
    start();
  })
})