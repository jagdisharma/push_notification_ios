<?php

	include "configration.php";
	$final_result = array();

        $user_id = $_REQUEST['user_id'];
        $device_id = $_REQUEST['device_id'];
        $device_type = $_REQUEST['device_type'];
            

        if($user_id=='')
        {
            $array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Please enter user_id.';
        }
        elseif($device_id=='')
        {
        	$array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Please enter device_id.';
        }
	else
	{

		$select = "SELECT device_id FROM users WHERE user_id='$user_id'";
		$selectquery = mysqli_query($con,$select);
		$result = mysqli_fetch_all($selectquery,MYSQLI_ASSOC);
			
		$explode = array();
		if( $result[0]['device_id'] != ''){
			$explode = explode(",",$result[0]['device_id']);
		}	
		//echo"<pre>";print_r($explode);

		$status = 0;
		for($i=0; $i<count($explode); $i++){
			if($explode[$i] === $device_id){
				$status = 1;
			}
		}
		if($status === 0){
			array_push($explode, $device_id);
			//echo"<pre>";print_r($explode);die();

			$implode = implode($explode, "," );
			//echo"<pre>";print_r($implode);die();

			$update = "update users set device_id= '$implode', device_type='$device_type' where user_id='$user_id'";

			$updatequery = mysqli_query($con,$update);
			
			if($update)
			{
				$array_temp['Success'] = 'true';
				$array_temp['Message'] = 'Device Token Updated successfully.';
			}
				
		}else{
			$array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Device Token is Not Updated.';
		}

		
	}

	$final_result = $array_temp;
	echo json_encode($final_result);
?>
