<div class="timeHead">
	<h3>Hours</h3>
</div>
<?php
	if($storeTimes){
		foreach($storeTimes as $time){
			$monOpen = $time['mon_o'];
			if($monOpen == '00:00:00'){
				$monOpen = 'closed';
			} else {
				$monOpen = $Helper->formatStoreTime($monOpen);
			}
			$monClose = $time['mon_c'];
			if($monClose == '00:00:00'){
				$monClose = '';
			} else {
				$monClose = $Helper->formatStoreTime($monClose);
			}
			$tueOpen = $time['tue_o'];
			if($tueOpen == '00:00:00'){
				$tueOpen = '';
			} else {
				$tueOpen = $Helper->formatStoreTime($tueOpen);
			}
			$tueClose = $time['tue_c'];
			if($tueClose == '00:00:00'){
				$tueClose = '';
			} else {
				$tueClose = $Helper->formatStoreTime($tueClose);
			}
			$wedOpen = $time['wed_o'];
			if($wedOpen == '00:00:00'){
				$wedOpen = '';
			} else {
				$wedOpen = $Helper->formatStoreTime($wedOpen);
			}
			$wedClose = $time['wed_c'];
			if($wedClose == '00:00:00'){
				$wedClose = '';
			} else {
				$wedClose = $Helper->formatStoreTime($wedClose);
			}
			$thuOpen = $time['thu_o'];
			if($thuOpen == '00:00:00'){
				$thuOpen = '';
			} else {
				$thuOpen = $Helper->formatStoreTime($thuOpen);
			}
			$thuClose = $time['thu_c'];
			if($thuClose == '00:00:00'){
				$thuClose = '';
			} else {
				$thuClose = $Helper->formatStoreTime($thuClose);
			}
			$friOpen = $time['fri_o'];
			if($friOpen == '00:00:00'){
				$friOpen = '';
			} else {
				$friOpen = $Helper->formatStoreTime($friOpen);
			}
			$friClose = $time['fri_c'];
			if($friClose == '00:00:00'){
				$friClose = '';
			} else {
				$friClose = $Helper->formatStoreTime($friClose);
			}
			$satOpen = $time['sat_o'];
			if($satOpen == '00:00:00'){
				$satOpen = '';
			} else {
				$satOpen = $Helper->formatStoreTime($satOpen);
			}
			$satClose = $time['sat_c'];
			if($satClose == '00:00:00'){
				$satClose = '';
			} else {
				$satClose = $Helper->formatStoreTime($satClose);
			}
			$sunOpen = $time['sun_o'];
			if($sunOpen == '00:00:00'){
				$sunOpen = '';
			} else {
				$sunOpen = $Helper->formatStoreTime($sunOpen);
			}
			$sunClose = $time['sun_c'];
			if($sunClose == '00:00:00'){
				$sunClose = '';
			} else {
				$sunClose = $Helper->formatStoreTime($sunClose);
			}
		}
	} else {
		$monOpen=$monClose=$tueOpen=$tueClose=$wedOpen=$wedClose=$thuOpen=$thuClose
		=$friOpen=$friClose=$satOpen=$satClose=$sunOpen=$sunClose 
		= $Helper->formatStoreTime('00:00:00');
		
	}
	echo '<ul class="timeListWrap">';
			$Views->appendStoreTime('MONDAY',$monOpen,$monClose);
			$Views->appendStoreTime('TUESDAY',$monOpen,$monClose);
			$Views->appendStoreTime('WEDNESDAY',$monOpen,$monClose);
			$Views->appendStoreTime('THURSDAY',$monOpen,$monClose);
			$Views->appendStoreTime('FRIDAY',$monOpen,$monClose);
			$Views->appendStoreTime('SATURDAY',$monOpen,$monClose);
			$Views->appendStoreTime('SUNDAY',$monOpen,$monClose);
	echo '</ul>';
?>

