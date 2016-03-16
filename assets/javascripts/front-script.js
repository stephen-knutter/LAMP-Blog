$(function(){
	/*GOOGLE MAP*/
	storedMarker = Array();
	var timeInterval = 1000;
	
	var customIcons = {
		rec: {
			icon: 'https://www.budvibes.com/images/rec_icon_40.png' 
		},
		rdel: {
			icon: 'https://www.budvibes.com/images/rec_icon_del_60.png'
		},
		med:{
			icon: 'https://www.budvibes.com/images/med_icon_40.png'
		},
		mdel:{
			icon: 'https://www.budvibes.com/images/med_icon_del_60.png'
		},
		tou:{
			icon: 'https://www.budvibes.com/images/tour-icon.png'
		}
	};
	
	/*MAP CLICK HANDLER REGION*/
	var currentRegion = $("ul#cityFilter li.selected");
	var currentMap = currentRegion.attr("id");
	displayMap(currentMap, 'all');
	$("ul#cityFilter li input").on("click", function(event){
		event.preventDefault();
		$link = $(this).parent("span").parent("li");
		var check = $link.attr("id");
		currentPage = $link.text().toLowerCase();
		currentPage = currentPage.replace(/\s+/g, '-');
		if(currentMap && check){
			if(currentPage == 'denver' || currentPage == 'telluride' || currentPage == 'colorado-springs' || currentPage == 'ft-collins' 
			|| currentPage == 'aspen' || currentPage == 'breckenridge' || currentPage == 'boulder' || currentPage == 'pueblo'){
				//COLORADO
				window.location = 'https://www.budvibes.com/colorado/'+currentPage
			} else if(currentPage == 'seattle' || currentPage == 'spokane' || currentPage == 'olympia' || currentPage == 'tacoma'){
				//WASHINGTON
				window.location = 'https://www.budvibes.com/washington/'+currentPage
			} else if(currentPage == 'portland' || currentPage == 'bend' || currentPage == 'medford' || currentPage == 'eugene' || currentPage == 'la-grande'){
				//OREGON
				window.location = 'https://www.budvibes.com/oregon/'+currentPage
			} else if(currentPage == 'los-angeles' || currentPage == 'san-fernando' || currentPage == 'orange-county' || currentPage == 'inland-empire' || 
					  currentPage == 'norcal' || currentPage == 'bay-area' || currentPage == 'central-cal' || currentPage == 'sacramento' || currentPage == 'san-diego'){
				//CALIFORNIA
				window.location = 'https://www.budvibes.com/california/'+currentPage
			} else if(currentPage == 'vancouver'){
				//VANCOUVER, BC
				window.location = 'https://www.budvibes.com/british-columbia/'+currentPage
			} else if(currentPage == 'vancouver-island'){
					window.location = 'https://www.budvibes.com/british-columbia/'+currentPage
			} else if(currentPage == 'detroit' || currentPage == 'ann-arbor' || currentPage == 'flint' || currentPage == 'lansing' || 
					  currentPage == 'grand-rapids' || currentPage == 'north-michigan'){
				//MICHIGAN
				window.location = 'https://www.budvibes.com/michigan/'+currentPage
			} else if(currentPage == 'phoenix' || currentPage == 'flagstaff' || currentPage == 'bullhead-city' || currentPage == 'tucson'){
				//ARIZONA
				window.location = 'https://www.budvibes.com/arizona/'+currentPage
			} else if(currentPage == 'las-vegas' || currentPage == 'reno'){
				//NEVADA
				window.location = 'https://www.budvibes.com/nevada/'+currentPage
			} else if(currentPage == 'hartford'){
				//CONNECTICUT
				window.location = 'https://www.budvibes.com/connecticut/'+currentPage
			} else if(currentPage == 'wilmington'){
				//DELAWARE
				window.location = 'https://www.budvibes.com/delaware/'+currentPage
			} else if(currentPage == 'chicago'){
				//ILLINOIS
				window.location = 'https://www.budvibes.com/illinois/'+currentPage
			} else if(currentPage == 'amsterdam'){
				//NETHERLANDS
				window.location = 'https://www.budvibes.com/netherlands/'+currentPage
			} else if(currentPage == 'anchorage'){
				//ALASKA
				window.location = 'https://www.budvibes.com/alaska/'+currentPage
			} else if(currentPage == 'washington'){
				//DISTRICT OF COLUMBIA
				window.location = 'https://www.budvibes.com/district-of-columbia/'+currentPage
			} else if(currentPage == 'boston'){
				//MASSACHUSETTS
				window.location = 'https://www.budvibes.com/massachusetts/'+currentPage
			} else if(currentPage == 'minneapolis'){
				//MINNESOTA
				window.location = 'https://www.budvibes.com/minnesota/'+currentPage
			} else if(currentPage == 'montana'){
				//MONTANA
				window.location = 'https://www.budvibes.com/montana/'+currentPage
			} else if(currentPage == 'newark'){
				//NEW JERSEY
				window.location = 'https://www.budvibes.com/new-jersey/'+currentPage
			} else if(currentPage == 'albuquerque'){
				//NEW MEXICO
				window.location = 'https://www.budvibes.com/new-mexico/'+currentPage
			} else if(currentPage == 'augusta'){
				//MAINE
				window.location = 'https://www.budvibes.com/maine/'+currentPage
			} else if(currentPage == 'barcelona'){
				//SPAIN
				window.location = 'https://www.budvibes.com/spain/'+currentPage
			}
		}
	});
	
	/*MAP CLICK HANDLER FILTER*/
	$("ul#typeFilter li input").on("click", function(event){
		$link = $(this).parent("span").parent("li");
		currentSelection = $("ul#typeFilter li.selected");
		currentSelectionReg = $("ul#cityFilter li.selected");
		currentRegion = currentSelectionReg.attr("id");
		currentFilter = $link.attr("id");
		if(currentFilter && currentRegion){
			currentSelection.attr("class", "");
			$link.attr("class", "selected");
			displayMap(currentRegion, currentFilter);
		}
	})
	
function displayMap(region,filter){
	switch(region){
		/*DENVER, CO (SOUTH DENVER, DOWNTOWN DENVER, WEST DENVER, NORTHEAST DENVER, SOUTHEAST DENVER)*/
		case 'dev':
			var newCenter = new google.maps.LatLng(39.7392, -104.9847);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'devsth':
			var newCenter = new google.maps.LatLng(39.680038,-104.988956);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'devdtn':
			var newCenter = new google.maps.LatLng(39.770609,-104.996338);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'devwst':
			var newCenter = new google.maps.LatLng(39.742104,-105.081139);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'devnea':
			var newCenter = new google.maps.LatLng(39.780636,-104.903641);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'devsea':
			var newCenter = new google.maps.LatLng(39.679246,-104.908791);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*BOULDER, CO*/
		case 'bld':
			var newCenter = new google.maps.LatLng(40.014985, -105.270545);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'blddtn':
			var newCenter = new google.maps.LatLng(40.057639,-105.276833);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'bldest':
			var newCenter = new google.maps.LatLng(40.01354,-105.168686);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'bldned':
			var newCenter = new google.maps.LatLng(39.963018,-105.509949);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'bldlyn':
			var newCenter = new google.maps.LatLng(40.201551,-105.181732);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*COLORADO SPRINGS, CO*/
		case 'csp':
			var newCenter = new google.maps.LatLng(38.846127, -104.800644);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'cspdtn':
			var newCenter = new google.maps.LatLng(38.822654,-104.821587);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'cspnth':
			var newCenter = new google.maps.LatLng(38.907127,-104.823303);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'cspest':
			var newCenter = new google.maps.LatLng(38.836026,-104.752235);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'cspold':
			var newCenter = new google.maps.LatLng(38.852306,-104.866905);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		
		/*PUEBLO, CO*/
		case 'pue':
			var newCenter = new google.maps.LatLng(38.15965,-104.824677);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'puewst':
			var newCenter = new google.maps.LatLng(38.331137,-104.735413);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'puesth':
			var newCenter = new google.maps.LatLng(38.15965,-104.824677);
			var newZoom = 8;
			var mapType = 'hood';
		break;
		case 'puecan':
			var newCenter = new google.maps.LatLng(38.436632,-105.375366);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		
		/*APSEN, CO*/
		case 'asp':
		 	var newCenter = new google.maps.LatLng(39.195560, -106.8382);
			var newZoom = 9;
			var mapType = 'region';
		break;
		case 'aspdtn':
			var newCenter = new google.maps.LatLng(39.188709,-106.818609);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'asprif':
			var newCenter = new google.maps.LatLng(39.531382,-107.780342);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'aspglw':
			var newCenter = new google.maps.LatLng(39.4782,-107.293167);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'aspcbt':
			var newCenter = new google.maps.LatLng(38.867889,-106.981602);
			var newZoom = 16;
			var mapType = 'hood';
		break;
		case 'aspbue':
			var newCenter = new google.maps.LatLng(38.840038,-106.13205);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'aspgjc':
			var newCenter = new google.maps.LatLng(39.103673,-108.400726);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*BRECK, CO*/
		case 'brk':
			var newCenter = new google.maps.LatLng(39.482043, -106.038777);
			var newZoom = 9;
			var mapType = 'region';
		break;
			var newCenter = new google.maps.LatLng(39.482043, -106.038777);
			var newZoom = 9;
			var mapType = 'hood';
		case 'brkdtn':
			var newCenter = new google.maps.LatLng(39.507383,-106.051884);
			var newZoom = 16;
			var mapType = 'hood';
		break;
		case 'brkidh':
			var newCenter = new google.maps.LatLng(39.736358,-105.518875);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'brknth':
			var newCenter = new google.maps.LatLng(39.613156,-106.078835);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'brksth':
			var newCenter = new google.maps.LatLng(39.25271,-106.174622);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'brkegl':
			var newCenter = new google.maps.LatLng(39.657761,-106.64978);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*FT COLLINS, CO*/
		case 'ftc':
			var newCenter = new google.maps.LatLng(40.559167, -105.078056);
			var newZoom = 9;
			var mapType = 'region';
		break;
		case 'ftcdtn':
			var newCenter = new google.maps.LatLng(40.559167, -105.078056);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'ftcgly':
			var newCenter = new google.maps.LatLng(40.395759,-104.68945);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'ftcstm':
			var newCenter = new google.maps.LatLng(40.472086,-106.837921);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'ftcsgw':
			var newCenter = new google.maps.LatLng(40.935592,-102.520123);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		
		/*TELLURIDE, CO*/
		case 'tel':
			var newCenter = new google.maps.LatLng(37.939167,  -107.816389);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'teldtn':
			var newCenter = new google.maps.LatLng(37.939167,  -107.816389);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'teldur':
			var newCenter = new google.maps.LatLng(37.291664,-107.865143);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		
		/*SEATTLE, WA*/
		case 'sea':
			var newCenter = new google.maps.LatLng(47.670582,-122.247963);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'seadtn':
			var newCenter = new google.maps.LatLng(47.605986,-122.32933);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'seanth':
			var newCenter = new google.maps.LatLng(47.755768,-122.328644);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'seaest':
			var newCenter = new google.maps.LatLng(47.698888,-121.959229);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'seaprt':
			var newCenter = new google.maps.LatLng(47.545072,-122.623901);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'seabel':
			var newCenter = new google.maps.LatLng(48.792217,-122.450867);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*TACOMA, WA*/
		case 'tac':
			var newCenter = new google.maps.LatLng(47.342763,-122.242813);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'tacdtn':
			var newCenter = new google.maps.LatLng(47.25855,-122.437477);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'tacsth':
			var newCenter = new google.maps.LatLng(47.210816,-122.457047);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'tacknt':
			var newCenter = new google.maps.LatLng(47.423086,-122.011414);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'tacest':
			var newCenter = new google.maps.LatLng(47.183289,-122.205734);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*OLYMPIA, WA*/
		case 'oly':
			var newCenter = new google.maps.LatLng(47.037568, -122.900512);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'olydtn':
			var newCenter = new google.maps.LatLng(47.04398,-122.832642);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'olycen':
			var newCenter = new google.maps.LatLng(46.872038,-122.940445);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'olyabn':
			var newCenter = new google.maps.LatLng(46.988794,-123.717728);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		
		/*SPOKANE, WA*/
		case 'spk':
			var newCenter = new google.maps.LatLng(47.710332,-117.216911);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'spkdtn':
			var newCenter = new google.maps.LatLng(47.692718,-117.399902);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'spkvly':
			var newCenter = new google.maps.LatLng(47.675152,-117.214851);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*PORTLAND, OR*/
		case 'por':
			var newCenter = new google.maps.LatLng(45.544095,-122.55867);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'pordtn':
			var newCenter = new google.maps.LatLng(45.541041,-122.595406);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'porest':
			var newCenter = new google.maps.LatLng(45.532864,-122.479877);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'porwst':
			var newCenter = new google.maps.LatLng(45.548106,-122.689047);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*BEND, OR*/
		case 'bnd':
			var newCenter = new google.maps.LatLng(44.079998,-121.284084);
			var newZoom = 13;
			var mapType = 'region';
		break;
		
		/*EUGENE, OR*/
		case 'eug':
			var newCenter = new google.maps.LatLng(44.051836, -123.086733);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'eugdtn':
			var newCenter = new google.maps.LatLng(44.068898,-123.003788);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'eugsth':
			var newCenter = new google.maps.LatLng(43.581005,-123.258018);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		
		/*MEDFORD, OR*/
		case 'mef':
			var newCenter = new google.maps.LatLng(42.326257, -122.875396);
			var newZoom = 10;
			var mapType = 'region';
		break;
		/*LA GRANDE, OR*/
		case 'lgr':
			var newCenter = new google.maps.LatLng(45.32417, -118.087417);
			var newZoom = 10;
			var mapType = 'region';
		break;
		
		/*LOS ANGELES, CA*/
		case 'los':
			var newCenter = new google.maps.LatLng(34.079091,-118.096848);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'losdtn':
			var newCenter = new google.maps.LatLng(34.051952, -118.243672);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'lossth':
			var newCenter = new google.maps.LatLng(33.98443,-118.241558);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loshly':
			var newCenter = new google.maps.LatLng(34.096587,-118.304558);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'lossan':
			var newCenter = new google.maps.LatLng(33.997591,-118.394508);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loshar':
			var newCenter = new google.maps.LatLng(33.792735,-118.272028);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'losped':
			var newCenter = new google.maps.LatLng(33.772474,-118.308077);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loscom':
			var newCenter = new google.maps.LatLng(33.907029,-118.174782);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'losmon':
			var newCenter = new google.maps.LatLng(34.019975,-118.114614);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'losnly':
			var newCenter = new google.maps.LatLng(34.213639,-118.334084);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'lospas':
			var newCenter = new google.maps.LatLng(34.144336,-118.066978);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loscas':
			var newCenter = new google.maps.LatLng(34.718176,-118.063889);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'loscov':
			var newCenter = new google.maps.LatLng(34.087053,-117.849655);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loswit':
			var newCenter = new google.maps.LatLng(33.951401,-117.989902);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'lostor':
			var newCenter = new google.maps.LatLng(33.866743,-118.346872);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'loslgb':
			var newCenter = new google.maps.LatLng(33.81523,-118.154869);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'losmal':
			var newCenter = new google.maps.LatLng(34.048241,-118.694572);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'losven':
			var newCenter = new google.maps.LatLng(33.99493,-118.465662);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'losgln':
			var newCenter = new google.maps.LatLng(34.141821,-118.232203);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'losvta':
			var newCenter = new google.maps.LatLng(34.247885,-119.115057);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*SAN FERNANDO VALLEY, CA*/
		case 'sfv':
			var newCenter = new google.maps.LatLng(34.245431, -118.425407);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'sfvvan':
			var newCenter = new google.maps.LatLng(34.186421,-118.451071);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sfvdtn':
			var newCenter = new google.maps.LatLng(34.291065,-118.43884);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sfvcan':
			var newCenter = new google.maps.LatLng(34.206474,-118.579044);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfvsan':
			var newCenter = new google.maps.LatLng(34.396472,-118.542244);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'sfvnth':
			var newCenter = new google.maps.LatLng(34.238269,-118.526516);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfvths':
			var newCenter = new google.maps.LatLng(34.21414,-118.824177);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfvbur':
			var newCenter = new google.maps.LatLng(34.148356,-118.386526);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		
		/*ORANGE COUNTY, CA*/
		case 'org':
			var newCenter = new google.maps.LatLng(33.755526,-117.733097);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'organh':
			var newCenter = new google.maps.LatLng(33.835293, -117.914504);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'orgsan':
			var newCenter = new google.maps.LatLng(33.747952,-117.867197);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'orggar':
			var newCenter = new google.maps.LatLng(33.774175,-117.94099);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'orghun':
			var newCenter = new google.maps.LatLng(33.671388,-117.988443);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'orglke':
			var newCenter = new google.maps.LatLng(33.679102,-117.688894);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'orgvne':
			var newCenter = new google.maps.LatLng(33.685585,-117.772965);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'orgmes':
			var newCenter = new google.maps.LatLng(33.645509,-117.918792);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'orgfull':
			var newCenter = new google.maps.LatLng(33.898699,-117.86665);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'orglag':
			var newCenter = new google.maps.LatLng(33.582944,-117.699108);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'orgcte':
			var newCenter = new google.maps.LatLng(33.436919,-117.622633);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*INLAND EMPIRE, CA*/
		case 'emp':
			var newCenter = new google.maps.LatLng(33.925156, -116.876314);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'empplm':
			var newCenter = new google.maps.LatLng(33.830448,-116.544921);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'emphem':
			var newCenter = new google.maps.LatLng(33.82835,-117.000961);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empper':
			var newCenter = new google.maps.LatLng(33.903743,-117.225494);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empmor':
			var newCenter = new google.maps.LatLng(33.903743,-117.225494);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'emprvr':
			var newCenter = new google.maps.LatLng(33.957733,-117.385483);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empbdo':
			var newCenter = new google.maps.LatLng(34.107607,-117.286949);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'emppan':
			var newCenter = new google.maps.LatLng(34.064957,-117.68486);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'empcor':
			var newCenter = new google.maps.LatLng(33.950462,-117.491913);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empmur':
			var newCenter = new google.maps.LatLng(33.595528,-117.215538);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'empdes':
			var newCenter = new google.maps.LatLng(33.733744,-116.300583);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'emptec':
			var newCenter = new google.maps.LatLng(33.509659,-117.13829);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'empapp':
			var newCenter = new google.maps.LatLng(34.682046,-117.05246);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		case 'empcno':
			var newCenter = new google.maps.LatLng(34.042685,-117.597656);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'empcuc':
			var newCenter = new google.maps.LatLng(34.108744,-117.580833);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'emphie':
			var newCenter = new google.maps.LatLng(34.477393,-117.285919);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empvic':
			var newCenter = new google.maps.LatLng(34.54025,-117.288494);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'empyuc':
			var newCenter = new google.maps.LatLng(34.121433,-116.364441);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empbig':
			var newCenter = new google.maps.LatLng(34.249404,-116.882858);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empyca':
			var newCenter = new google.maps.LatLng(33.996386,-116.93573);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empels':
			var newCenter = new google.maps.LatLng(33.698333,-117.224121);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'empndl':
			var newCenter = new google.maps.LatLng(34.849378,-114.608345);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		
		/*NORCAL, CA*/
		case 'nca':
			var newCenter = new google.maps.LatLng(39.793633,-120.657349);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'ncaeur':
			var newCenter = new google.maps.LatLng(40.862962,-124.071007);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'ncared':
			var newCenter = new google.maps.LatLng(41.061993,-122.430267);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		case 'ncauki':
			var newCenter = new google.maps.LatLng(39.269723,-123.189697);
			var newZoom = 10;
			var mapType = 'hood';
		break;	
		case 'ncalke':
			var newCenter = new google.maps.LatLng(39.045978,-122.715225);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'ncasan':
			var newCenter = new google.maps.LatLng(38.448463,-122.649994);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'ncaval':
			var newCenter = new google.maps.LatLng(38.163969,-122.202301);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'ncachi':
			var newCenter = new google.maps.LatLng(39.732701,-121.825676);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'ncatrk':
			var newCenter = new google.maps.LatLng(39.323924,-120.132751);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'ncavca':
			var newCenter = new google.maps.LatLng(38.365601,-121.942749);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*BAY AREA, CA*/
		case 'sfc':
			var newCenter = new google.maps.LatLng(37.78508,-121.955795);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'sfcdtn':
			var newCenter = new google.maps.LatLng(37.75952,-122.399883);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sfcoak':
			var newCenter = new google.maps.LatLng(37.810765,-122.269163);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'sfcsth':
			var newCenter = new google.maps.LatLng(37.606871,-122.424088);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'sfcsan':
			var newCenter = new google.maps.LatLng(37.728621,-121.928329);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'sfcjse':
			var newCenter = new google.maps.LatLng(37.411874,-121.861038);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'sfcant':
			var newCenter = new google.maps.LatLng(37.980181,-121.701736);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfcbrk':
			var newCenter = new google.maps.LatLng(37.931232,-122.225389);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfcwal':
			var newCenter = new google.maps.LatLng(37.943241,-122.03167);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sfcraf':
			var newCenter = new google.maps.LatLng(38.001827,-122.483139);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*CENTRAL CAL, CA*/
		case 'cca':
			var newCenter = new google.maps.LatLng(36.431158,-119.726257);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'ccasan':
			var newCenter = new google.maps.LatLng(37.058045,-121.962318);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'ccamon':
			var newCenter = new google.maps.LatLng(36.67088,-121.557541);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'ccabar':
			var newCenter = new google.maps.LatLng(34.436496,-119.679222);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'ccabak':
			var newCenter = new google.maps.LatLng(35.372008,-118.998671);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'ccastk':
			var newCenter = new google.maps.LatLng(38.022385,-121.076889);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'ccafes':
			var newCenter = new google.maps.LatLng(36.775807,-119.709263);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'ccamod':
			var newCenter = new google.maps.LatLng(37.584021,-120.770645);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'ccaslo':
			var newCenter = new google.maps.LatLng(35.295644,-120.587654);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'ccamar':
			var newCenter = new google.maps.LatLng(34.898453,-120.372734);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		/*SACRAMENTO, CA*/
		case 'sac':
			var newCenter = new google.maps.LatLng(38.65145,-121.403732);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'sacdtn':
			var newCenter = new google.maps.LatLng(38.581578,-121.518745);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sacsth':
			var newCenter = new google.maps.LatLng(38.52251,-121.409912);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sacnth':
			var newCenter = new google.maps.LatLng(38.707448,-121.181946);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'sacyub':
			var newCenter = new google.maps.LatLng(39.152552,-121.589127);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sacabn':
			var newCenter = new google.maps.LatLng(38.915676,-121.065903);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		
		/*SAN DIEGO, CA*/
		case 'san':
			var newCenter = new google.maps.LatLng(32.741218,-117.129707);
			var newZoom = 13;
			var mapType = 'region';
		break;
		case 'sandtn':
			var newCenter = new google.maps.LatLng(32.741218,-117.129707);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sanspr':
			var newCenter = new google.maps.LatLng(32.763349,-116.992207);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sanchu':
			var newCenter = new google.maps.LatLng(32.638286,-117.03804);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sanvis':
			var newCenter = new google.maps.LatLng(33.208024,-117.230301);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sancar':
			var newCenter = new google.maps.LatLng(33.162145,-117.337933);
			var newZoom = 15;
			var mapType = 'hood';
		break;
		case 'saneta':
			var newCenter = new google.maps.LatLng(33.043561,-117.2509);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sanmrc':
			var newCenter = new google.maps.LatLng(33.127556,-117.031517);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'sanosd':
			var newCenter = new google.maps.LatLng(33.205532,-117.371063);
			var newZoom = 14;
			var mapType = 'hood';
		break;
		case 'sanpcb':
			var newCenter = new google.maps.LatLng(32.811584,-117.225494);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sanelc':
			var newCenter = new google.maps.LatLng(32.809241,-116.954956);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'sancto':
			var newCenter = new google.maps.LatLng(32.868765,-115.523987);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		case 'sanfll':
			var newCenter = new google.maps.LatLng(33.384793,-117.239227);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		
		/*DETROIT, MI*/
		case 'det':
			var newCenter = new google.maps.LatLng(42.537129,-82.87056);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'detdtn':
			var newCenter = new google.maps.LatLng(42.349216,-83.025913);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'detwst':
			var newCenter = new google.maps.LatLng(42.378389,-83.183155);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		case 'detnth':
			var newCenter = new google.maps.LatLng(42.576581,-83.086166);
			var newZoom = 10;
			var mapType = 'hood';
		break;
		
		/*ANN ARBOR, MI*/
		case 'ann':
			var newCenter = new google.maps.LatLng(42.272466,-83.640976);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'anndtn':
			var newCenter = new google.maps.LatLng(42.270225,-83.719683);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'annyps':
			var newCenter = new google.maps.LatLng(42.24637,-83.600636);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		
		case 'fln':
			/*FLINT, MI*/
			var newCenter = new google.maps.LatLng(43.012399, -83.687346);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'lan':
			/*LANSING, MI*/
			var newCenter = new google.maps.LatLng(42.732325, -84.555502);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'grp':
			/*GRAND RAPIDS, MI*/
			var newCenter = new google.maps.LatLng(42.963285, -85.668168);
			var newZoom = 8;
			var mapType = 'region';
		break;
		
		/*NORTHERN MICHIGAN, MI*/
		case 'nmi':
			var newCenter = new google.maps.LatLng(45.693201,-85.834808);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'nmitvc':
			var newCenter = new google.maps.LatLng(44.760501,-85.593452);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		case 'nmiest':
			var newCenter = new google.maps.LatLng(45.182944,-84.377747);
			var newZoom = 9;
			var mapType = 'hood';
		break;
		case 'nmiwst':
			var newCenter = new google.maps.LatLng(47.146649,-87.006226);
			var newZoom = 7;
			var mapType = 'hood';
		break;
		
		
		case 'pnx':
			/*PHOENIX, AZ*/
			var newCenter = new google.maps.LatLng(33.478686,-112.033768);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'flg':
			/*FLAGSTAFF, AZ*/
			var newCenter = new google.maps.LatLng(35.198255, -111.651392);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'bull':
			/*BULLHEAD CITY, AZ*/
			var newCenter = new google.maps.LatLng(35.135774, -114.528587);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'tuc':
			/*TUCSON, AZ*/
			var newCenter = new google.maps.LatLng(32.217993,-110.757294);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'las':
			/*LAS VEGAS, NV*/
			var newCenter = new google.maps.LatLng(36.169737, -115.139723);
			var newZoom = 11;
			var mapType = 'region';
		break;
		case 'rno':
			/*RENO, NV*/
			var newCenter = new google.maps.LatLng(39.537534,-119.74926);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'htf':
			/*HARTFORD, CT*/
			var newCenter = new google.maps.LatLng(41.761227, -72.684774);
			var newZoom = 9;
			var mapType = 'region';
		break;
		case 'wil':
			/*WILMINGTON, DE*/
			var newCenter = new google.maps.LatLng(39.708491,-75.540619);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'chi':
			/*CHICAGO, IL*/
			var newCenter = new google.maps.LatLng(40.484179,-88.993671);
			var newZoom = 7;
			var mapType = 'region';
		break;
		case 'ams':
			/*AMSTERDAM, NL*/
			var newCenter = new google.maps.LatLng(52.369972,4.895031);
			var newZoom = 14;
			var mapType = 'region';
		break;
		case 'anc':
			/*ANCHORAGE, AK*/
			var newCenter = new google.maps.LatLng(61.215434,-149.900036);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'wsh':
			/*WASHINGTON, DC*/
			var newCenter = new google.maps.LatLng(38.898765,-77.031326);
			var newZoom = 10;
			var mapType = 'region';
		break;
		case 'aug':
			/*AUGUSTA, ME*/
			var newCenter = new google.maps.LatLng(44.309922,-69.779534);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'bos':
			/*BOSTON, MA*/
			var newCenter = new google.maps.LatLng(42.358939,-71.058841);
			var newZoom = 9;
			var mapType = 'region';
		break;
		case 'min':
			/*MINNEAPOLIS, MN*/
			var newCenter = new google.maps.LatLng(44.977477,-93.264838);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'boz':
			/*BOZEMAN, MT*/
			var newCenter = new google.maps.LatLng(45.67685,-111.042928);
			var newZoom = 6;
			var mapType = 'region';
		break;
		case 'new':
			/*NEWARK, NJ*/
			var newCenter = new google.maps.LatLng(40.73549,-74.172424);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'abq':
			/*ALBUQUERQUE, NM*/
			var newCenter = new google.maps.LatLng(35.470667,-106.169128);
			var newZoom = 9;
			var mapType = 'region';
		break;
		
		/*VANCOUVER, BC*/
		case 'van':
			var newCenter = new google.maps.LatLng(49.282681, -123.120888);
			var newZoom = 12;
			var mapType = 'region';
		break;
		case 'vandtn':
			var newCenter = new google.maps.LatLng(49.286198,-123.105841);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'vanmtp':
			var newCenter = new google.maps.LatLng(49.273026,-123.044171);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'vankto':
			var newCenter = new google.maps.LatLng(49.272746,-123.14734);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		case 'vansth':
			var newCenter = new google.maps.LatLng(49.232224,-123.041553);
			var newZoom = 13;
			var mapType = 'hood';
		break;
		
		/*VANCOUVER ISLAND, BC*/
		case 'vci':
			var newCenter = new google.maps.LatLng(48.757886,-123.909302);
			var newZoom = 8;
			var mapType = 'region';
		break;
		case 'vcivta':
			var newCenter = new google.maps.LatLng(48.435792,-123.337326);
			var newZoom = 12;
			var mapType = 'hood';
		break;
		case 'vcinmo':
			var newCenter = new google.maps.LatLng(49.210631,-123.914108);
			var newZoom = 11;
			var mapType = 'hood';
		break;
		
		//BARCELONS, SPAIN
		case 'bar':
			var newCenter = new google.maps.LatLng(41.529159,2.150574);
			var newZoom = 10;
			var mapType = 'region';
		break;
		/*
		default:
			//DEFUALT TO DENVER
			var newCenter = new google.maps.LatLng(39.7392, -104.9847);
			var newZoom = 12;
		break;
		*/
	}
	
	var map = new google.maps.Map(document.getElementById("map"), {
		/*styles: [{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#71ABC3"},{"saturation":-10},{"lightness":-21},{"visibility":"simplified"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"hue":"#7DC45C"},{"saturation":37},{"lightness":-41},{"visibility":"simplified"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#C3E0B0"},{"saturation":23},{"lightness":-12},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#A19FA0"},{"saturation":-98},{"lightness":-20},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#ffa500"},{"saturation":100},{"lightness":0},{"visibility":"simplified"}]}],*/
		center: newCenter,
		zoom: newZoom,
		
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.BOTTOM_LEFT
		},
		scrollwheel: false,
		scaleControl: false,
		streetViewControl: true,
		panControl: false,
		mapTypeControl: false,
		mapTypeId: 'roadmap'
	});
	
		var  infoBubble = new InfoBubble({
			    map: map,
				shadowStyle: 0,
				padding: 10,
				backgroundColor: '#ffffff',
				borderRadius: 5,
				arrowSize: 15,
				borderWidth: 2,
				borderColor: 'orange',
				disableAutoPan: true,
				maxWidth: 310,
				minWidth: 310,
				maxHeight: 100,
				minHeight: 100,
				arrowPosition: 50,
				backgroundClassName: 'marker',
				arrowStyle: 2
		});
		
		var geoBubble = new InfoBubble({
			    map: map,
				shadowStyle: 0,
				padding: 10,
				backgroundColor: '#ffffff',
				borderRadius: 5,
				arrowSize: 15,
				borderWidth: 2,
				borderColor: 'orange',
				disableAutoPan: true,
				maxWidth: 100,
				minWidth: 100,
				maxHeight: 20,
				minHeight: 20,
				arrowPosition: 50,
				backgroundClassName: 'phoney',
				arrowStyle: 2
			 });
		
		
			if (navigator.geolocation) {
			    navigator.geolocation.getCurrentPosition(function(position) {
			        var latitude = position.coords.latitude;
			        var longitude = position.coords.longitude;
			        var geolocpoint = new google.maps.LatLng(latitude, longitude);
			        // Place a marker
			        var geolocation = new google.maps.Marker({
			            position: geolocpoint,
			            map: map,
			            title: 'Your geolocation',
			            icon: 'https://www.budvibes.com/images/smoke_icon_75.png'
			        });
			
					google.maps.event.addListener(geolocation, 'click', function(){
						geoBubble.close();
						geoBubble.setContent('YOUR LOCATION');
						geoBubble.open(map, geolocation);
					});
			    });
			}
		var url = "https://www.budvibes.com/weed-dbinfo.php?storeType="+filter+"&reg="+region+"&map="+mapType
		//alert(url);
	$.ajax({
		type: "GET",
		dataType: "xml",
		url : "https://www.budvibes.com/weed-dbinfo.php?storeType="+filter+"&reg="+region+"&map="+mapType,
		
		success : function(xml){
			var markers = xml.documentElement.getElementsByTagName("marker");
			$("#listings").html("");
			for(var i=0; i < markers.length; i++){
				var name = markers[i].getAttribute("name");
				var id = markers[i].getAttribute("id");
				var name = markers[i].getAttribute("name");
				var address = markers[i].getAttribute("address");
				var addresslength = markers[i].getAttribute("address").length;
				var type = markers[i].getAttribute("type");
				var phone = markers[i].getAttribute("phone");
				var url_name = markers[i].getAttribute("url_name");
				var region = markers[i].getAttribute("region");
				var website = (markers[i].getAttribute("website") == 'N/A') ? '' : markers[i].getAttribute("website");
				var cash = (markers[i].getAttribute("cash") == 'd') ? "<span>Debit Card</span>" + "<div class='cash_image_debit clearfix'><img src='https://www.budvibes.com/images/debit.png' class='cash_image'/></div>" : 
				"<span>ATM</span>" + "<div class='cash_image_atm clearfix'><img src='https://www.budvibes.com/images/atm.png'/></div>";
				var lat = parseFloat(markers[i].getAttribute("lat"));
				var lng = parseFloat(markers[i].getAttribute("lng"));
				var point = new google.maps.LatLng(
					 lat, lng
				);
				var votes = markers[i].getAttribute("votes");
				var value = markers[i].getAttribute("value");
				var num = value / votes;
				var directory;
				
				switch(region){
					case 'dev':
						directory = 'https://www.budvibes.com/colorado/denver';
					break;
					case 'bld':
						directory = 'https://www.budvibes.com/colorado/boulder';
					break;
					case 'csp':
						directory = 'https://www.budvibes.com/colorado/colorado-springs';
					break;
					case 'tel':
						directory = 'https://www.budvibes.com/colorado/telluride';
					break;
					case 'asp':
						directory = 'https://www.budvibes.com/colorado/aspen';
					break;
					case 'ftc':
						directory = 'https://www.budvibes.com/colorado/ft-collins';
					break;
					case 'brk':
						directory = 'https://www.budvibes.com/colorado/breckenridge';
					break;
					case 'pue':
						directory = 'https://www.budvibes.com/colorado/pueblo';
					break;
					case 'sea':
						directory = 'https://www.budvibes.com/washington/seattle';
					break;
					case 'tac':
						directory = 'https://www.budvibes.com/washington/tacoma';
					break;
					case 'oly':
						directory = 'https://www.budvibes.com/washington/olympia';
					break;
					case 'spk':
						directory = 'https://www.budvibes.com/washington/spokane';
					break;
					case 'por':
						directory = 'https://www.budvibes.com/oregon/portland';
					break;
					case 'bnd':
						directory = 'https://www.budvibes.com/oregon/bend';
					break;
					case 'mef':
						directory = 'https://www.budvibes.com/oregon/medford';
					break;
					case 'eug':
						directory = 'https://www.budvibes.com/oregon/eugene';
					break;
					case 'lgr':
						directory = 'https://www.budvibes.com/oregon/la-grande';
					break;
					case 'los':
						directory = 'https://www.budvibes.com/california/los-angeles';
					break;
					case 'sfv':
						directory = 'https://www.budvibes.com/california/san-fernando';
					break;
					case 'org':
						directory = 'https://www.budvibes.com/california/orange-county';
					break;
					case 'emp':
						directory = 'https://www.budvibes.com/california/inland-empire';
					break;
					case 'nca':
						directory = 'https://www.budvibes.com/california/norcal';
					break;
					case 'sfc':
						directory = 'https://www.budvibes.com/california/bay-area';
					break;
					case 'cca':
						directory = 'https://www.budvibes.com/california/central-cal';
					break;
					case 'sac':
						directory = 'https://www.budvibes.com/california/sacramento';
					break;
					case 'san':
						directory = 'https://www.budvibes.com/california/san-diego';
					break;
					case 'det':
						directory = 'https://www.budvibes.com/michigan/detroit';
					break;
					case 'ann':
						directory = 'https://www.budvibes.com/michigan/ann-arbor';
					break;
					case 'fln':
						directory = 'https://www.budvibes.com/michigan/flint';
					break;
					case 'lan':
						directory = 'https://www.budvibes.com/michigan/lansing';
					break;
					case 'grp':
						directory = 'https://www.budvibes.com/michigan/grand-rapids';
					break;
					case 'nmi':
						directory = 'https://www.budvibes.com/michigan/north-michigan';
					break;
					case 'pnx':
						directory = 'https://www.budvibes.com/arizona/phoenix';
					break;
					case 'flg':
						directory = 'https://www.budvibes.com/arizona/flagstaff';
					break;
					case 'bull':
						directory = 'https://www.budvibes.com/arizona/bullhead-city';
					break;
					case 'tuc':
						directory = 'https://www.budvibes.com/arizona/tucson';
					break;
					case 'las':
						directory = 'https://www.budvibes.com/nevada/las-vegas';
					break;
					case 'rno':
						directory = 'https://www.budvibes.com/nevada/reno';
					break;
					case 'htf':
						directory = 'https://www.budvibes.com/connecticut/hartford';
					break;
					case 'wil':
						directory = 'https://www.budvibes.com/delaware/wilmington';
					break;
					case 'chi':
						directory = 'https://www.budvibes.com/illinois/chicago';
					break;
					case 'ams':
						directory = 'https://www.budvibes.com/netherlands/amsterdam';
					break;
					case 'anc':
						directory = 'https://www.budvibes.com/alaska/anchorage';
					break;
					case 'wsh':
						directory = 'https://www.budvibes.com/district-of-columbia/washington';
					break;
					case 'aug':
						directory = 'https://www.budvibes.com/maine/augusta';
					break;
					case 'bos':
						directory = 'https://www.budvibes.com/massachusetts/boston';
					break;
					case 'min':
						directory = 'https://www.budvibes.com/minnesota/minneapolis';
					break;
					case 'boz':
						directory = 'https://www.budvibes.com/montana/bozeman';
					break;
					case 'new':
						directory = 'https://www.budvibes.com/new-jersey/newark';
					break;
					case 'abq':
						directory = 'https://www.budvibes.com/new-mexico/albuquerque';
					break;
					case 'van':
						directory = 'https://www.budvibes.com/british-columbia/vancouver';
					break;
					case 'vci':
						directory = 'https://www.budvibes.com/british-columbia/vancouver-island';
					break;
					case 'bar':
						directory = 'https://www.budvibes.com/spain/barcelona';
					break;
				}
				
				var html = "<div class='info'>";
				var ihtml = "<div class='info clearfix'>";
				ihtml += "<div class='infoPicWrap'>";
				ihtml += "<img class='infoPic' src='https://www.budvibes.com/images/no-store.png' alt='"+name+" marijuana dispensary'/>";
				ihtml += "</div>";
				ihtml += "<div class='infoWrap'>";
				if(name != 'Colorado Marijuana Tours'){
					//url_name = name.toLowerCase();
					//url_name = url_name.replace(/\s+/g, '-');
					//url_name = url_name.replace(/\&/g, 'and');
					//url_name = url_name.replace(/\&amp\;/g, 'and');
					//url_name = url_name.replace(/\'/g, '');
					//url_name = url_name.replace(/\&39\;/g, '');
					html  += "<a href="+ directory + "/" + url_name +"><b class='name'>" + name + "</b></a><br/>";
					ihtml  += "<a href="+ directory + "/" + url_name +"><b class='name'>" + name + "</b></a><br/>";
				} else {
					html += "<a href='coloradomarijuanatours.php'><b class='name'>" + name + "</b></a><br/>";
					ihtml += "<a href='coloradomarijuanatours.php'><b class='name'>" + name + "</b></a><br/>";
				}
				html += "<p class='address'><i>" + address + "</i></p>";
				ihtml += "<p class='address'><i>" + address + "</i></p>";
				if(name != 'Colorado Marijuana Tours'){
					html += "<p class='phone'><i>" + phone + "</i></p>";
					ihtml += "<p class='phone'><i>" + phone + "</i></p>";
				} 
				if(name != 'Colorado Marijuana Tours'){
					if(num > 0.1 && num <= .9){
						html +=	"<div class='half_star'></div>";
						ihtml +=	"<div class='half_star'></div>";
					} else if (num > .9 && num <= 1.4){
						html +=	"<div class='one_star'></div>";
						ihtml +=	"<div class='one_star'></div>";
					} else if (num > 1.4 && num <= 1.99){
						html +=	"<div class='one_half'></div>";
						ihtml +=	"<div class='one_half'></div>";
					} else if (num > 1.99 && num <= 2.4){
						html +=	"<div class='two_star'></div>";
						ihtml +=	"<div class='two_star'></div>";
					} else if (num > 2.4 && num <= 2.99){
						html +=	"<div class='two_half'></div>";
						ihtml += "<div class='two_half'></div>";
					} else if (num > 2.99 && num <= 3.4){
						html +=	"<div class='three_star'></div>";
						ihtml +=	"<div class='three_star'></div>";
					} else if(num > 3.4 && num  <= 3.99){
						html +=	"<div class='three_half'></div>";
						ihtml +=	"<div class='three_half'></div>";
					} else if(num > 3.99 && num <= 4.4){
						html +=	"<div class='four_star'></div>";
						ihtml += "<div class='four_star'></div>";
					} else if(num > 4.4 && num <= 4.99){
						html +=	"<div class='four_half'></div>";
						ihtml +=	"<div class='four_half'></div>";
					} else if(num == 5){
						html += "<div class='five_star'></div>";
						ihtml += "<div class='five_star'></div>";
					} else {
						html += "<div class='no_stars'></div>";
						ihtml +=	"<div class='no_stars'></div>";
					}
				}
				
				if(name !== 'Colorado Marijuana Tours'){
					html += "<p class='cash'><b>Non-Cash Type:</b>"+cash +"</p>";
				} else {
					html += "<p class='address'>Click above for tour information</p>";
				}
				
				html += "<a id='" + id + "' class='on_map'>" + 'Mark on map' + "</a>";
				ihtml += "<a id='" + id + "' class='on_map'>" + 'Mark on map' + "</a>";
				
				html += "</div>";
				ihtml += "</div>";
				ihtml += "</div>";
				var icon = customIcons[type] || {};
				var marker = new google.maps.Marker({
					map: map,
					position: point,
					icon: icon.icon,
					title: name
				});
				
				storedMarker[id] = marker;
				bindInfoWindow(marker, addresslength, address, map, infoBubble, html);
				appendListing(ihtml);
			}
		},
		complete: function(){
			$("body").click();
			$("#listingWrap").css("display","block");
			//$("a#menuIcon").click();
		}
	})
}
	
function bindInfoWindow(marker, addresslength, addressFill, map, infoBubble, html){
		google.maps.event.addListener(marker, 'click', function(){
			infoBubble.close();
			infoBubble.setContent(html);
			infoBubble.open(map, marker);
			var address =  $('.address').text().substring(0,addresslength);
			$('#to').val(addressFill);
		});
	}
});

function appendListing(html){
	$('#listings').append(html);
	
	this.onclick = function(){
	$("body").on("click", "#listings  .info a.on_map", function(event){
		event.stopPropagation();
		var pop_up = $(this).attr("id")
		google.maps.event.trigger(storedMarker[pop_up], "click");
	});
  }	
}

$(function(){
	// If the browser supports the Geolocation API
  if (typeof navigator.geolocation == "undefined") {
    console.log("Your browser doesn't support the Geolocation API");
    return;
  }

  $("#from-link").click(function(event) {
    event.preventDefault();
    var addressId = this.id.substring(0, this.id.indexOf("-"));

    navigator.geolocation.getCurrentPosition(function(position) {
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({
        "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
      },
      function(results, status) {
        if (status == google.maps.GeocoderStatus.OK)
          $("#" + addressId).val(results[0].formatted_address);
        else
          alert("Unable to retrieve your address, your browser may not support geolocation, please type in your address");
      });
    },
    function(positionError){
     ;
    },
    {
      enableHighAccuracy: true,
      timeout: 10 * 1000 // 10 seconds
    });
  });

 /*CALCULATE ROUTE FORM SUBMISSION*/
  $("#calculate-route").submit(function(event) {
    event.preventDefault();
    calculateRoute($("#from").val(), $("#to").val());
  });

function calculateRoute(from, to) {
 var markerArray=[];
  var myOptions = {
    zoom: 10,
	zoomControlOptions: {
		 style: google.maps.ZoomControlStyle.LARGE,
		 position: google.maps.ControlPosition.BOTTOM_RIGHT
	},
	scrollwheel: false,
	scaleControl: false,
	streetViewControl: false,
	panControl: false,
	mapTypeControl: false,
    center: new google.maps.LatLng(39.7392, -104.9847),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  // Draw the map
  var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);

  stepDisplay = new google.maps.InfoWindow();

  var directionsService = new google.maps.DirectionsService();
  var directionsRequest = {
    origin: from,
    destination: to,
    travelMode: google.maps.DirectionsTravelMode.DRIVING,
    unitSystem: google.maps.UnitSystem.METRIC
  };

  directionsService.route(directionsRequest,function(response, status){
      if (status == google.maps.DirectionsStatus.OK)
      {
		var renderOptions = {
			map: mapObject,
			directions: response,
			suppressMarkers: true
		}

        new google.maps.DirectionsRenderer(renderOptions);
		showSteps(response)
      }
      else{
       	alert("Unable to retrieve your route");
	  }	
    }
  );

	function showSteps(directionResult){
		var myRoute = directionResult.routes[0].legs[0];

		for(var i=0; i < myRoute.steps.length; i++){
			var marker = new google.maps.Marker({
				position: myRoute.steps[0].start_point,
				map: mapObject,
				icon: 'https://www.budvibes.com/images/smoke_icon_75.png',
				title: 'Your Location'
			});
			markerArray.push(marker);
		}
		var marker = new google.maps.Marker({
			position: myRoute.steps[i - 1].end_point,
			map: mapObject,
			icon: 'https://www.budvibes.com/images/leaf_icon_75.png'
		});

	}
}
	
	$(".weed_map").on('click', function(event){
			window.location = './';
	});
});

/*MORE NEIGHBORHOOD MENU*/
$(function(){
	$("span.moreWeed").on("click", function(){
		$curClick = $(this);
		var hoodMenu = $curClick.attr("id");
		/*
		$("."+hoodMenu).animate({
			height : "toggle"
		}, 200);
		*/
		
		$("."+hoodMenu).slideToggle(300);
	});
	
});

function adjustMap(){
	var height = document.documentElement.clientHeight;
	var mapHeight = height - 69 + "px";
	var listHeight = height - 205 + "px"
	var searchHeight = height - 101 + "px";
	var suggestHeight = height - 265 + "px";
	//MAP HEIGHT
	$("#map").css({
		height: mapHeight
	});
	//STORE LISTINGS HEIGHT
	$("#listings").css({
		height: listHeight
	});
	
	//SEARCH BOX HEIGHT
	$("#searchBox").css({
		height: searchHeight
	});
	/*$(".suggestions").css("height", suggestHeight);*/
	
	//POISION FILTER TOOTIPS AT BOTTOM OF MAP
	var pickCity = $("#pickCity");
	var pickCityPosTop = pickCity.offset().top;
	var pickCityPosLeft = pickCity.offset().left;
	var chooseFilter = $("#chooseFilter");
	var chooseFilterPosTop = chooseFilter.offset().top;
	var chooseFilterPosLeft = chooseFilter.offset().left;
	var cityHead = $("#cityHeadWrap");
	cityHead.css({
		"position":"absolute",
		"top": pickCityPosTop - 365 + "px",
		"left": -500 + "px" 
	})
	var filterHead = $("#filterHeadWrap");
	filterHead.css({
		"position":"absolute",
		"top": chooseFilterPosTop - 365 + "px",
		"left": -200 + "px"
	})
};

window.onload = adjustMap;
window.onresize = adjustMap;
