<?php
	require dirname(__DIR__) . '/bv_inc.php';
	require dirname(__DIR__) . '/controllers/stores_controller.php';
	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$StoresCtrl = new StoresCtrl;
	$Views = new ApplicationViews;
	$Helper = new ApplicationHelper;
	
	if(!$Helper->isLoggedIn()){
		$error['status'] = 'Must be logged in';
		$error['code'] = 401;
		echo json_encode($error);
		exit();
	}
	
	$storeId = (int)$_SESSION['store_id'];
	//MONDAY
	$monOhour = $_POST['mon_ohour'];
	$monOmin = $_POST['mon_omin'];
	$monOampm = $_POST['mon_oampm'];
	$monChour = $_POST['mon_chour'];
	$monCmin = $_POST['mon_cmin'];
	$monCampm = $_POST['mon_campm'];
	//TUESDAY
	$tueOhour = $_POST['tue_ohour'];
	$tueOmin = $_POST['tue_omin'];
	$tueOampm = $_POST['tue_oampm'];
	$tueChour = $_POST['tue_chour'];
	$tueCmin = $_POST['tue_cmin'];
	$tueCampm = $_POST['tue_campm'];
	//WEDNESDAY
	$wedOhour = $_POST['wed_ohour'];
	$wedOmin = $_POST['wed_omin'];
	$wedOampm = $_POST['wed_oampm'];
	$wedChour = $_POST['wed_chour'];
	$wedCmin = $_POST['wed_cmin'];
	$wedCampm = $_POST['wed_campm'];
	//THURSDAY
	$thuOhour = $_POST['thu_ohour'];
	$thuOmin = $_POST['thu_omin'];
	$thuOampm = $_POST['thu_oampm'];
	$thuChour = $_POST['thu_chour'];
	$thuCmin = $_POST['thu_cmin'];
	$thuCampm = $_POST['thu_campm'];
	//FRIDAY
	$friOhour = $_POST['fri_ohour'];
	$friOmin = $_POST['fri_omin'];
	$friOampm = $_POST['fri_oampm'];
	$friChour = $_POST['fri_chour'];
	$friCmin = $_POST['fri_cmin'];
	$friCampm = $_POST['fri_campm'];
	//SATURDAY
	$satOhour = $_POST['sat_ohour'];
	$satOmin = $_POST['sat_omin'];
	$satOampm = $_POST['sat_oampm'];
	$satChour = $_POST['sat_chour'];
	$satCmin = $_POST['sat_cmin'];
	$satCampm = $_POST['sat_campm'];
	//SUNDAY
	$sunOhour = $_POST['sun_ohour'];
	$sunOmin = $_POST['sun_omin'];
	$sunOampm = $_POST['sun_oampm'];
	$sunChour = $_POST['sun_chour'];
	$sunCmin = $_POST['sun_cmin'];
	$sunCampm = $_POST['sun_campm'];
		
	if(!empty($monOhour) && !empty($monOmin) && !empty($monOampm) && !empty($monChour) && !empty($monCmin) && !empty($monCampm) && 
	   !empty($tueOhour) && !empty($tueOmin) && !empty($tueOampm) && !empty($tueChour) && !empty($tueCmin) && !empty($tueCampm) && 
	   !empty($wedOhour) && !empty($wedOmin) && !empty($wedOampm) && !empty($wedChour) && !empty($wedCmin) && !empty($wedCampm) && 
	   !empty($thuOhour) && !empty($thuOmin) && !empty($thuOampm) && !empty($thuChour) && !empty($thuCmin) && !empty($thuCampm) && 
	   !empty($friOhour) && !empty($friOmin) && !empty($friOampm) && !empty($friChour) && !empty($friCmin) && !empty($friCampm) && 
	   !empty($satOhour) && !empty($satOmin) && !empty($satOampm) && !empty($satChour) && !empty($satCmin) && !empty($satCampm) && 
	   !empty($sunOhour) && !empty($sunOmin) && !empty($sunOampm) && !empty($sunChour) && !empty($sunCmin) && !empty($sunCampm)){
		//MONDAY
			if($monOhour == 'closed' || $monChour == 'closed'){
				$monOhour = '00';
				$monOmin = '00';
				$monChour = '00';
				$monCmin = '00';
			} else {
				if($monOampm == 'pm'){
					$monOhour = $monOhour + 12;
				}
				if($monCampm == 'pm'){
					$monChour = $monChour + 12;
				}
			}
			$mondayOpen = $monOhour.':'.$monOmin.':00';
			$mondayClose = $monChour.':'.$monCmin.':00';
		//TUESDAY
			if($tueOhour == 'closed' || $tueChour == 'closed'){
				$tueOhour = '00';
				$tueOmin = '00';
				$tueChour = '00';
				$tueCmin = '00';
			} else {
				if($tueOampm == 'pm'){
					$tueOhour = $tueOhour + 12;
				}
				if($tueCampm == 'pm'){
					$tueChour = $tueChour + 12;
				}
			}
			$tuesdayOpen = $tueOhour.':'.$monOmin.':00';
			$tuesdayClose = $tueChour.':'.$monCmin.':00';
		//WEDNESDAY
			if($wedOhour == 'closed' || $wedChour == 'closed'){
				$wedOhour = '00';
				$wedOmin = '00';
				$wedChour = '00';
				$wedCmin = '00';
			} else {
				if($wedOampm == 'pm'){
					$wedOhour = $wedOhour + 12;
				}
				if($wedCampm == 'pm'){
					$wedChour = $wedChour + 12;
				}
			}
			$wednesdayOpen = $wedOhour.':'.$wedOmin.':00';
			$wednesdayClose = $wedChour.':'.$wedCmin.':00';
		//THURSDAY
			if($thuOhour == 'closed' || $thuChour == 'closed'){
				$thuOhour = '00';
				$thuOmin = '00';
				$thuChour = '00';
				$thuCmin = '00';
			} else {
				if($thuOampm == 'pm'){
					$thuOhour = $thuOhour + 12;
				}
				if($thuCampm == 'pm'){
					$thuChour = $thuChour + 12;
				}
			}
			$thursdayOpen = $thuOhour.':'.$thuOmin.':00';
			$thursdayClose = $thuChour.':'.$thuCmin.':00';
		//FRIDAY
			if($friOhour == 'closed' || $friChour == 'closed'){
				$friOhour = '00';
				$friOmin = '00';
				$friChour = '00';
				$friCmin = '00';
			} else {
				if($friOampm == 'pm'){
					$friOhour = $friOhour + 12;
				}
				if($friCampm == 'pm'){
					$friChour = $friChour + 12;
				}
			}
			$fridayOpen = $friOhour.':'.$friOmin.':00';
			$fridayClose = $friChour.':'.$friCmin.':00';
		//SATURDAY
			if($satOhour == 'closed' || $satChour == 'closed'){
				$satOhour = '00';
				$satOmin = '00';
				$satChour = '00';
				$satCmin = '00';
			} else {
				if($satOampm == 'pm'){
					$satOhour = $satOhour + 12;
				}
				if($satCampm == 'pm'){
					$satChour = $satChour + 12;
				}
			}
			$saturdayOpen = $satOhour.':'.$satOmin.':00';
			$saturdayClose = $satChour.':'.$satCmin.':00';
		//SUNDAY
			if($sunOhour == 'closed' || $sunChour == 'closed'){
				$sunOhour = '00';
				$sunOmin = '00';
				$sunChour = '00';
				$sunCmin = '00';
			} else {
				if($sunOampm == 'pm'){
					$sunOhour = $sunOhour + 12;
				}
				if($sunCampm == 'pm'){
					$sunChour = $sunChour + 12;
				}
			}
			$sundayOpen = $sunOhour.':'.$sunOmin.':00';
			$sundayClose = $sunChour.':'.$sunCmin.':00';
			
			$newTimes = $StoresCtrl->addNewTime($storeId,
												$mondayOpen,$mondayClose,
											    $tuesdayOpen,$tuesdayClose,
												$wednesdayOpen,$wednesdayClose,
												$thursdayOpen,$thursdayClose,
												$fridayOpen,$fridayClose,
												$saturdayOpen,$saturdayClose,
												$sundayOpen,$sundayClose);
			if($newTimes){
				$success['code'] = 200;
				$success['status'] = 'Hours successfully updated';
				echo json_encode($success);
				exit();
			} else {
				$error['code'] = 501;
				$error['status'] = 'Hours could not be updated';
				echo json_encode($error);
				exit();
			}
		} else {
			$error['code'] = 500;
			$error['status'] = "One or more items blank";
			echo json_encode($error);
			exit();
		}