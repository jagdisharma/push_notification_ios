<?php
error_reporting();
ini_set('display_errors',E_ALL);

$servername = "localhost";
$username = "aplussoz";
$password = "!ggFSwVNhZ6(";
$dbname = "aplussoz_plantap";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/************** Fetch all data of Table **************/

$getusers = "SELECT u.user_id as user_id, u.device_id as deviceToken, r.daytime as daytime,r.timezone as timezone,r.repeatInterval as repeatInterval,r.title as title,r.notes as notes,r.notifTitle as notifTitle, r.customDict as customDict  FROM reminders r JOIN users u ON r.user_id = u.user_id";
$getusersquery = mysqli_query($conn,$getusers);
$result = mysqli_fetch_all($getusersquery,MYSQLI_ASSOC);
//echo '<pre>'; print_r($result); echo count($result);die;	

$presentDate = new DateTime();
for($i=0;$i<count($result);$i++){

	$deviceToken_explode = explode(",", $result[$i]['deviceToken']);

	error_log(print_r($deviceToken_explode, TRUE), 3, 'errors.log');
	for($j=0;$j< count($deviceToken_explode); $j++){


		if($deviceToken_explode[$j] != '' && ($result[$i]['repeatInterval'] != '' || $result[$i]['repeatInterval'] != null)){
			$repeatInterval = $result[$i]['repeatInterval'];

			$timezone = $result[$i]['timezone'];
			//date_default_timezone_set($timezone);
	 		$currentTime = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/

			$customDist = $result[$i]['customDict'];
			$date = json_decode($result[$i]['customDict']);
			
			$daytime = $result[$i]['daytime'];
			
			$tz_from = $timezone;
			$tz_to = 'UTC';

			$dt = new DateTime($daytime, new DateTimeZone($tz_from));

			$dt->setTimeZone(new DateTimeZone($tz_to));
		
			$al = $date->sequence;
			/*------------------ Data send with cron -------------------*/
			$body = $result[$i]['notes'];
			$title = $result[$i]['title'];
			$notifTitle = $result[$i]['notifTitle'];
			$Devicetoken = $deviceToken_explode[$j];
			//$Devicetoken = 'c45493549a4d7e091f966d3812f595b6eaa273ca4a37fd8d9421bc8edf1a8434';

			$freq = $date->frequency;
			if($freq == 0 && $customDist != '' && $repeatInterval == 5){
				/*---------------------------------Daliy on same time when RepeatInterval is 5----------------------------*/
				$currentTime = $presentDate->format('H:i'); /*date('H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i');/*date('Y-m-d H:i');*/

				$daytimeUtc = $dt->format('H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');
				
				//for($k=1;$k<=31;$k++){
					//$currentTime = date($currentTime,strtotime('+24 hours'));
					if($daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}
				//}
				
			}else if($freq == 1 && $repeatInterval == 5){
				/*--------------------------------- on Some Selected Week days-------------------------------------*/
				$weekday  = json_decode($result[$i]['customDict']);
				$allWeekdays = $weekday->sequence;
				$currentWeekDay = $presentDate->format('l'); /*date('l');*/
				$currentTime = $presentDate->format('H:i');/* date('H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/
				
				$daytimeUtc = $dt->format('H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				foreach($allWeekdays as $key=>$allWeekday){
					if($key == 7 && $allWeekday == 1 && $currentWeekDay == 'Saturday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 6 && $allWeekday == 1 && $currentWeekDay == 'Friday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 5 && $allWeekday == 1 && $currentWeekDay == 'Thursday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 4 && $allWeekday == 1 && $currentWeekDay == 'Wednesday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 3 && $allWeekday == 1 && $currentWeekDay == 'Tuesday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 2 && $allWeekday == 1 && $currentWeekDay == 'Monday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}else if($key == 1 && $allWeekday == 1 && $currentWeekDay == 'Sunday' && $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}
				}
			}else if($freq == 2 && $repeatInterval == 5){
				/*---------------------------------Monthly on Some selected days----------------------------------*/
				$currentDay = $presentDate->format('d'); /*date('d');*/
				$currentTime= $presentDate->format('H:i'); /*date('H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/

				$daytimeUtc = $dt->format('H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				foreach ($al as $key => $al) {
					$value = $al + 1;
					if($value == $currentDay &&  $daytimeUtc == $currentTime  && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}
				}
			}else if($freq == 3  && $repeatInterval == 5){
				/*--------------------------------- Yearly on Selected Months -------------------------------------*/
				$currentMonth = $presentDate->format('M'); /*date('M');*/
				$currentTime= $presentDate->format('d H:i'); /*date('d H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/
				
				$daytimeUtc = $dt->format('d H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				foreach ($al as $key => $al) {
					$value = $al;
					if($value === $currentMonth &&  $daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
						cronjob($title, $body, $Devicetoken, $notifTitle);
					}
				}
			}else if($customDist === '' && $repeatInterval == 4){
				/*--------------------------------- Not Customized with yearly repeat -------------------------------*/
				$currentTime = $presentDate->format('m-d H:i'); /*date('m-d H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/

				$daytimeUtc = $dt->format('m-d H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');
	
				if($daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
					cronjob($title, $body, $Devicetoken, $notifTitle);
				}
			}else if($customDist === '' && $repeatInterval == 3){
				/*--------------------------------- Not Customized with monthly repeat -------------------------------*/
				$currentTime = $presentDate->format('d H:i'); /*date('d H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i');/*date('Y-m-d H:i');*/

				$daytimeUtc = $dt->format('d H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				if($daytimeUtc == $currentTime  && $currentTimeAll >= $daytimeUtcAll){
					cronjob($title, $body, $Devicetoken, $notifTitle);
				}
			}else if($customDist === '' && $repeatInterval == 2){
				/*--------------------------------- Not Customized with weekly repeat -------------------------------*/	
				$currentTime = $presentDate->format('H:i'); /*date('H:i');*/
				$curentDay = $presentDate->format('l'); /*date('l');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/

				$timestamp = strtotime($result[$i]['daytime']);
				$timeStampDay = date('l', $timestamp);

				$daytimeUtc = $dt->format('H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				if($daytimeUtc == $currentTime && $timeStampDay == $curentDay && $currentTimeAll >= $daytimeUtcAll){
					cronjob($title, $body, $Devicetoken, $notifTitle);
				}
			}else if($customDist === '' && $repeatInterval == 1){
				/*--------------------------------- Not Customized with daily repeat -------------------------------*/
				$currentTime = $presentDate->format('H:i'); /*date('H:i');*/
				$currentTimeAll = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/
				
				$daytimeUtc = $dt->format('H:i');
				$daytimeUtcAll = $dt->format('Y-m-d H:i');

				if($daytimeUtc == $currentTime && $currentTimeAll >= $daytimeUtcAll){
					cronjob($title, $body, $Devicetoken, $notifTitle);
				}
			}else if($repeatInterval == 0 && $customDist === ''){
				/*--------------------------------- Never Repeat after this day -------------------------------------*/
				$currentTime = $presentDate->format('Y-m-d H:i'); /*date('Y-m-d H:i');*/

				$daytimeUtc = $dt->format('Y-m-d H:i');

				if($daytimeUtc == $currentTime){
					cronjob($title, $body, $Devicetoken, $notifTitle);
				}
			}
		}
		echo '<br><br>';
	}
}

/***************************** Cron Job Function ********************************/
function cronjob($reminder_title, $reminder_body, $reminder_Devicetoken, $reminder_notifTitle){
		$apnsServer = 'ssl://gateway.sandbox.push.apple.com:2195';
        
        $privateKeyPassword = 'Lbim2201';
        
        $title = $reminder_title;
        $message = $reminder_body;
		       		
        $deviceToken = $reminder_Devicetoken;
        
        $pushCertAndKeyPemFile = 'pushcert.pem';
        $stream = stream_context_create();
        stream_context_set_option($stream,'ssl','passphrase', $privateKeyPassword);
        stream_context_set_option($stream,'ssl','local_cert', $pushCertAndKeyPemFile);

        $connectionTimeout = 20;
        $connectionType = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;
        $connection = stream_socket_client($apnsServer, $errorNumber, $errorString, $connectionTimeout, $connectionType, $stream);
        if(!$connection){
        	echo "Failed to connect to the APNS server. Error no = $errorNumber<br/>";
        	exit;
        } else{
        	echo "Successfully connected to the APNS. Processing...</br>";
        }
        $messageBody['aps'] = array(
        	'alert' => array(
        		'title' => $reminder_notifTitle,
        		'subtitle' => $title,
        		'body' => $message
        	),
        	'sound' => 'default',
        	'badge' => 1,
        );
        $payload = json_encode($messageBody);

        $notification = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) .$payload;

        $wroteSuccessfully = fwrite($connection, $notification, strlen($notification));
        if (!$wroteSuccessfully){
        	echo "Could not send the message<br/>";
        }
        else {
	        echo "Successfully sent the message.<br/>";
        }
        fclose($connection);
}	

mysqli_close($conn);
?>