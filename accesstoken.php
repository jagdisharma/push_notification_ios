<?php

	include "configration.php";
	$final_result = array();

        $user_id = $_REQUEST['user_id'];
       	$device_id = $_REQUEST['device_id'];

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
		$update = "update users set device_id='$device_id' where user_id='$user_id'";
		$updatequery = mysqli_query($con,$update);
		
		if($updatequery)
		{
			$array_temp['Success'] = 'true';
			$array_temp['Message'] = 'Device Token Updated successfully.';
		}
		else
		{
			$array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Device Token is Not Updated.';
		}
	}

	$final_result = $array_temp;
	echo json_encode($final_result);
?>

