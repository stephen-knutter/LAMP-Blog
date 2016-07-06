<?php
	foreach($storeTimes as $time){
		
		//MONDAY TIME
		if($time['mon_o'] == '00:00:00'){
			$monOH = '00';
			$monOM = '00';
			$monOO = 'am';
		} else {
			$monOH = $Helper->getFormatHour($time['mon_o']);
			$monOM = $Helper->getFormatMinute($time['mon_o']);
			$monOO = $Helper->getFormatMeridian($time['mon_o']);
		}
		if($time['mon_c'] == '00:00:00'){
			$monCH = '00';
			$monCM = '00';
			$monOC = 'am';
		} else {
			$monCH = $Helper->getFormatHour($time['mon_c']);
			$monCM = $Helper->getFormatMinute($time['mon_c']);
			$monOC = $Helper->getFormatMeridian($time['mon_c']);
		}
		//TUESDAY TIME
		if($time['tue_o'] == '00:00:00'){
			$tueOH = '00';
			$tueOM = '00';
			$tueOO = 'am';
		} else {
			$tueOH = $Helper->getFormatHour($time['tue_o']);
			$tueOM = $Helper->getFormatMinute($time['tue_o']);
			$tueOO = $Helper->getFormatMeridian($time['tue_o']);
		}
		if($time['tue_c'] == '00:00:00'){
			$tueCH = '00';
			$tueCM = '00';
			$tueOC = 'am';
		} else {
			$tueCH = $Helper->getFormatHour($time['tue_c']);
			$tueCM = $Helper->getFormatMinute($time['tue_c']);
			$tueOC = $Helper->getFormatMeridian($time['tue_c']);
		}
		//WEDNESDAY TIME
		if($time['wed_o'] == '00:00:00'){
			$wedOH = '00';
			$wedOM = '00';
			$wedOO = 'am';
		} else {
			$wedOH = $Helper->getFormatHour($time['wed_o']);
			$wedOM = $Helper->getFormatMinute($time['wed_o']);
			$wedOO = $Helper->getFormatMeridian($time['wed_o']);
		}
		if($time['wed_c'] == '00:00:00'){
			$wedCH = '00';
			$wedCM = '00';
			$wedOC = 'am';
		} else {
			$wedCH = $Helper->getFormatHour($time['wed_c']);
			$wedCM = $Helper->getFormatMinute($time['wed_c']);
			$wedOC = $Helper->getFormatMeridian($time['wed_c']);
		}
		//THURSDAY TIME
		if($time['thu_o'] == '00:00:00'){
			$thuOH = '00';
			$thuOM = '00';
			$thuOO = 'am';
		} else {
			$thuOH = $Helper->getFormatHour($time['thu_o']);
			$thuOM = $Helper->getFormatMinute($time['thu_o']);
			$thuOO = $Helper->getFormatMeridian($time['thu_o']);
		}
		if($time['thu_c'] == '00:00:00'){
			$thuCH = '00';
			$thuCM = '00';
			$thuOC = 'am';
		} else {
			$thuCH = $Helper->getFormatHour($time['thu_c']);
			$thuCM = $Helper->getFormatMinute($time['thu_c']);
			$thuOC = $Helper->getFormatMeridian($time['thu_c']);
		}
		//FRIDAY TIME
		if($time['fri_o'] == '00:00:00'){
			$friOH = '00';
			$friOM = '00';
			$friOO = 'am';
		} else {
			$friOH = $Helper->getFormatHour($time['fri_o']);
			$friOM = $Helper->getFormatMinute($time['fri_o']);
			$friOO = $Helper->getFormatMeridian($time['fri_o']);
		}
		if($time['fri_c'] == '00:00:00'){
			$friCH = '00';
			$friCM = '00';
			$friOC = 'am';
		} else {
			$friCH = $Helper->getFormatHour($time['fri_c']);
			$friCM = $Helper->getFormatMinute($time['fri_c']);
			$friOC = $Helper->getFormatMeridian($time['fri_c']);
		}
		//SATURDAY TIME
		if($time['sat_o'] == '00:00:00'){
			$satOH = '00';
			$satOM = '00';
			$satOO = 'am';
		} else {
			$satOH = $Helper->getFormatHour($time['sat_o']);
			$satOM = $Helper->getFormatMinute($time['sat_o']);
			$satOO = $Helper->getFormatMeridian($time['sat_o']);
		}
		if($time['sat_c'] == '00:00:00'){
			$satCH = '00';
			$satCM = '00';
			$satOC = 'am';
		} else {
			$satCH = $Helper->getFormatHour($time['sat_c']);
			$satCM = $Helper->getFormatMinute($time['sat_c']);
			$satOC = $Helper->getFormatMeridian($time['sat_c']);
		}
		//SUNDAY TIME
		if($time['sun_o'] == '00:00:00'){
			$sunOH = '00';
			$sunOM = '00';
			$sunOO = 'am';
		} else {
			$sunOH = $Helper->getFormatHour($time['sun_o']);
			$sunOM = $Helper->getFormatMinute($time['sun_o']);
			$sunOO = $Helper->getFormatMeridian($time['sun_o']);
		}
		if($time['sun_c'] == '00:00:00'){
			$sunCH = '00';
			$sunCM = '00';
			$sunOC = 'am';
		} else {
			$sunCH = $Helper->getFormatHour($time['sun_c']);
			$sunCM = $Helper->getFormatMinute($time['sun_c']);
			$sunOC = $Helper->getFormatMeridian($time['sun_c']);
		}
		
		echo '<ul class="timeListWrap">';
				$Views->appendStoreTimeEdit('MONDAY',$monOH,$monCH,$monOM,$monCM,$monOO,$monOC,'monTime');
				$Views->appendStoreTimeEdit('TUESDAY',$tueOH,$tueCH,$tueOM,$tueCM,$tueOO,$tueOC,'tuesTime');
				$Views->appendStoreTimeEdit('WEDNESDAY',$wedOH,$wedCH,$wedOM,$wedCM,$wedOO,$wedOC,'wedTime');
				$Views->appendStoreTimeEdit('THURSDAY',$thuOH,$thuCH,$thuOM,$thuCM,$thuOO,$thuOC,'thuTime');
				$Views->appendStoreTimeEdit('FRIDAY',$friOH,$friCH,$friOM,$friCM,$friOO,$friOC,'friTime');
				$Views->appendStoreTimeEdit('SATURDAY',$satOH,$satCH,$satOM,$satCM,$satOO,$satOC,'satTime');
				$Views->appendStoreTimeEdit('SUNDAY',$sunOH,$sunCH,$sunOM,$sunCM,$sunOO,$sunOC,'sunTime');
		echo '</ul>';
	}
?>