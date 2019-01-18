<?php
	include "configration.php";
		
	function getDevice($user_id)
	{
		$sel = "select device_id,device_type from users where user_id='$user_id'";
		$selquery = mysqli_query($con,$sel);
		$seldata = mysqli_fetch_assoc($selquery);
		return $seldata; 
	}
	
	
	$userselect = "select * from users";
	$userquery = mysqli_query($con,$userselect);
	
	$i=0;
	while($userdata = mysqli_fetch_assoc($userquery))
	{
		$user[$i]['user_id'] = $userdata['user_id'];
		$user[$i]['device_id'] = $userdata['device_id'];
		$user[$i]['device_type'] = $userdata['device_type'];
		$i++;
	}
	
	for($j=0;$j<count($user);$j++)
	{
		$userid = $user[$j]['user_id'];
		$selectremainder = "select * from reminders where user_id='$userid'";
		$selremquery = mysqli_query($con,$selectremainder);
		$k=0;
		while($userremanider = mysqli_fetch_assoc($selremquery))
		{
			$remainders[] = $userremanider;
			$k++;
		}
	}
	
	foreach($remainders as $remainder)
	{
		$reminder_timezone = $remainder['timezone'];
		//date_default_timezone_set($reminder_timezone);	
		
		//$date = strtotime(date('Y-m-d H:i:s'));

                date_default_timezone_set($reminder_timezone);
                $date= date('Y-m-d H:i') ;
	
		$devicedata = getDevice($remainder['user_id']);
		$deviceid = $devicedata['device_id'];
		$devicetype = $devicedata['device_type'];
		
		$body = $remainder['notes'];
		$title = $remainder['title'];
if(empty($body))
{$body =$title;}
		$remainder_id = $remainder['reminder_id'];
		$reminder_date = strtotime($remainder['daytime']);
		$badge = 1;
		
    		define('API_ACCESS_KEY','AIzaSyDzRSpLetclu3MFb350iHQlNKSBoe3og_U');
                $date22=date('Y-m-d H:i', $reminder_date);
//echo "date1-----".$date."----date2-----".$date22."<br/>";
    		if($date==$date22)
    		{
	    		$registrationIds = array($deviceid);
			$notification = array('body'=>$body,'title'=>$title,'remainder_id'=>$remainder_id,'remainder_date'=>$reminder_date,'badge'=>$badge,'sound' => 'default');
		
			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'notification'          => $notification,
				'priority'              => 'high' 
			);
	 	
			$headers = array
			(
				'Authorization: key =' .API_ACCESS_KEY,
				'Content-Type: application/json'
			);
	 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
			//echo $result;
		}
		
		
	}

?>