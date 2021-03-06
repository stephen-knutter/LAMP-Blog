/*FOLLOW / UNFOLLOW*/
$(function(){
  irelations = {
	  curButton: null,
	  curButtonText: null,
	  curButtonClass: null,
	  curButtonAction: null,
	  relationTypeWrap: null,
    followWho: null,
	  relationType: null,
	  relationLink: $("span#relationLink"),
	  relationType: null,
	  relationUserId: null,
  }
  $("body").on("click", "span#relationLink, span.relationButton", function(){
    irelations.curButton = $(this);
	  irelations.relationTypeWrap = irelations
								                    .curButton
								                    .parent("div");
	  irelations.relationType = irelations
	                               .relationTypeWrap
								                 .attr("id");
    if(irelations.relationType.indexOf('-') >  0){
      /*SMALL BUTTON TYPE*/
      irelations.followWho = irelations
                               .relationType
                               .split('-');

      irelations.relationType = irelations
                                  .followWho[0];
      irelations.relationUserId = irelations
                                    .followWho[1];
      var small = true;
    } else {
	  /*BIG BUTTON TYPE*/
	    irelations.followWho = irelations
	                             .relationLink
								               .attr("class")
								               .split(' ')[0];
	    irelations.relationUserId  = irelations
						                         .followWho
						                         .split("-")[1];
      var small = false;
    }

      irelations.curButtonText = irelations
	                                 .curButton
								                   .text();
      irelations.curButtonClass = irelations
							                      .curButton
								                    .attr("class")
								                    .split(' ')[0];
	    var actionStart = irelations
	                        .curButtonClass
						              .indexOf("-");

	    irelations.curButtonAction = irelations
	                                   .curButtonClass
								                     .slice(0,actionStart);
	//!!! LEFT OFF HERE; NEED SCRIPT TO ADD/REMOVE PROD RELATIONSHIPS
    if(irelations.curButtonAction == 'follow'){
      if(irelations.relationType == 'typeProduct' || irelations.relationType == 'typeProd'){
        url = __LOCATION__ + '/ajax/ajax_prod_follow.php';
      } else {
        url = __LOCATION__ + '/ajax/ajax_user_follow.php';
      }

      if(!small){
        var newHtml = "Unfollow</b>"
        var newClass = "unfollow-"+irelations.relationUserId+" unfollowText";
      } else {
        var updateHtml = "Unfollow <b>&minus;</b>"
        var updateClass = "unfollow-"+irelations.relationUserId+" unfollowText";
        var newHtml = "&minus; Unfollow";
        var newClass = "unfollow-"+irelations.relationUserId+" relationButton";
      }
    } else if(irelations.curButtonAction == 'unfollow'){
      if(irelations.relationType == 'typeProduct' || irelations.relationType == 'typeProd'){
        url = __LOCATION__ + '/ajax/ajax_prod_unfollow.php';
      } else {
        url = __LOCATION__ + '/ajax/ajax_user_unfollow.php';
      }

      if(!small){
        var newHtml = "Follow";
        var newClass = "follow-"+irelations.relationUserId+" followText";
      } else {
        var updateHtml = "Follow <b>&#43;</b>";
        var updateClass = "follow-"+irelations.relationUserId+" followText";
        var newHtml = "&#43; Follow";
        var newClass = "follow-"+irelations.relationUserId+" relationButton";
      }
    } else {
		return false;
	}


  $.ajax({
      beforeSend: function(){
        if(!small){
		        irelations
		          .curButton
			        .html("");
        } else {
		        irelations
		          .curButton
			        .css("color","#ddd");
        }
      },
      type: 'POST',
      url: url,
	    data: {user_id: irelations.relationUserId,
	           relation_type: irelations.relationType},
      success: function(result){
        console.log(result);
        console.log(newHtml);
        console.log(newClass);
        console.log(irelations.curButton);
		      if(result){
			         $result = $.parseJSON(result);
			            iStatus = $result.code;
			               switch(iStatus){
				                 case 401:
					                  $(".chatBoxWrap").remove();
					                  $("body").append(doSignUpBox());
                            irelations
    			                    .curButton
    			                    .html(irelations.curButtonText)
    			                    .css("color", "#000");
				                  break;
				                  case 500:
				                  case 201:
					                     //DO NOTHING
                             irelations
    			                      .curButton
    			                      .html(irelations.curButtonText)
    			                      .css("color", "#000");
				                  break;
				                  case 200:
					                    irelations
					                      .curButton
					                      .html(newHtml)
					                      .attr("class",newClass)
                                .css("color","#000")
				                  break;
			               }
		        }
         }
     })

  })
});
