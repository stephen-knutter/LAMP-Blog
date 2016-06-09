<?php
	foreach($storeTimes as $time){
		
		//MONDAY TIME
		if($time['mon_o'] == '00:00:00'){
			$monO = 'closed';
		} else {
			$monOH = $Helper->getFormatHour($time['mon_o']);
			$monOM = $Helper->getFormatMinute($time['mon_o']);
			$monOO = $Helper->getFormatMeridian($time['mon_o']);
		}
		if($time['mon_c'] == '00:00:00'){
			$monC = 'closed';
		} else {
			$monCH = $Helper->getFormatHour($time['mon_c']);
			$monCM = $Helper->getFormatMinute($time['mon_c']);
			$monOC = $Helper->getFormatMeridian($time['mon_c']);
		}
		//TUESDAY TIME
		if($time['tue_o'] == '00:00:00'){
			$tueO = 'closed';
		} else {
			$tueOH = $Helper->getFormatHour($time['tue_o']);
			$tueOM = $Helper->getFormatMinute($time['tue_o']);
			$tueOO = $Helper->getFormatMeridian($time['tue_o']);
		}
		if($time['tue_c'] == '00:00:00'){
			$tueC = 'closed';
		} else {
			$tueCH = $Helper->getFormatHour($time['tue_c']);
			$tueCM = $Helper->getFormatMinute($time['tue_c']);
			$tueOC = $Helper->getFormatMeridian($time['tue_c']);
		}
		//WEDNESDAY TIME
		if($time['wed_o'] == '00:00:00'){
			$wedO = 'closed';
		} else {
			$wedOH = $Helper->getFormatHour($time['wed_o']);
			$wedOM = $Helper->getFormatMinute($time['wed_o']);
			$wedOO = $Helper->getFormatMeridian($time['wed_o']);
		}
		if($time['wed_c'] == '00:00:00'){
			$wedC = 'closed';
		} else {
			$wedCH = $Helper->getFormatHour($time['wed_c']);
			$wedCM = $Helper->getFormatMinute($time['wed_c']);
			$wedOC = $Helper->getFormatMeridian($time['wed_c']);
		}
		//THURSDAY TIME
		if($time['thu_o'] == '00:00:00'){
			$thuO = 'closed';
		} else {
			$thuOH = $Helper->getFormatHour($time['thu_o']);
			$thuOM = $Helper->getFormatMinute($time['thu_o']);
			$thuOO = $Helper->getFormatMeridian($time['thu_o']);
		}
		if($time['thu_c'] == '00:00:00'){
			$thuC = 'closed';
		} else {
			$thuCH = $Helper->getFormatHour($time['thu_c']);
			$thuCM = $Helper->getFormatMinute($time['thu_c']);
			$thuOC = $Helper->getFormatMeridian($time['thu_c']);
		}
		//FRIDAY TIME
		if($time['fri_o'] == '00:00:00'){
			$friO = 'closed';
		} else {
			$friOH = $Helper->getFormatHour($time['fri_o']);
			$friOM = $Helper->getFormatMinute($time['fri_o']);
			$friOO = $Helper->getFormatMeridian($time['fri_o']);
		}
		if($time['fri_c'] == '00:00:00'){
			$friC = 'closed';
		} else {
			$friCH = $Helper->getFormatHour($time['fri_c']);
			$friCM = $Helper->getFormatMinute($time['fri_c']);
			$friOC = $Helper->getFormatMeridian($time['fri_c']);
		}
		//SATURDAY TIME
		if($time['sat_o'] == '00:00:00'){
			$satO = 'closed';
		} else {
			$satOH = $Helper->getFormatHour($time['sat_o']);
			$satOM = $Helper->getFormatMinute($time['sat_o']);
			$satOO = $Helper->getFormatMeridian($time['sat_o']);
		}
		if($time['sat_c'] == '00:00:00'){
			$satC = 'closed';
		} else {
			$satCH = $Helper->getFormatHour($time['sat_c']);
			$satCM = $Helper->getFormatMinute($time['sat_c']);
			$satOC = $Helper->getFormatMeridian($time['sat_c']);
		}
		//SUNDAY TIME
		if($time['sun_o'] == '00:00:00'){
			$sunO = 'closed';
		} else {
			$sunOH = $Helper->getFormatHour($time['sun_o']);
			$sunOM = $Helper->getFormatMinute($time['sun_o']);
			$sunOO = $Helper->getFormatMeridian($time['sun_o']);
		}
		if($time['sun_c'] == '00:00:00'){
			$sunC = 'closed';
		} else {
			$sunCH = $Helper->getFormatHour($time['sun_c']);
			$sunCM = $Helper->getFormatMinute($time['sun_c']);
			$sunOC = $Helper->getFormatMeridian($time['sun_c']);
		}
		
		echo '<ul class="timeListWrap">';
				$Views->appendStoreTimeEdit('MONDAY',$monOH,$monCH,$monOM,$monCM,$monOO,$monOC);
				$Views->appendStoreTimeEdit('TUESDAY',$tueOH,$tueCH,$tueOM,$tueCM,$tueOO,$tueOC);
				$Views->appendStoreTimeEdit('WEDNESDAY',$wedOH,$wedCH,$wedOM,$wedCM,$wedOO,$wedOC);
				$Views->appendStoreTimeEdit('THURSDAY',$thuOH,$thuCH,$thuOM,$thuCM,$thuOO,$thuOC);
				$Views->appendStoreTimeEdit('FRIDAY',$friOH,$friCH,$friOM,$friCM,$friOO,$friOC);
				$Views->appendStoreTimeEdit('SATURDAY',$satOH,$satCH,$satOM,$satCM,$satOO,$satOC);
				$Views->appendStoreTimeEdit('SUNDAY',$sunOH,$sunCH,$sunOM,$sunCM,$sunOO,$sunOC);
		echo '</ul>';
	}
?>