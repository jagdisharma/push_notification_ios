<?php

	include "configration.php";
	$final_result = array();

        $reminder_id = $_REQUEST['reminder_id'];
         
        if($reminder_id=='')
        {
			$final_result['Success'] = 'false';
			$final_result['Message'] = 'Please enter reminder_id.';
        }
		else
		{
			$delete = "delete from reminders where reminder_id='".$reminder_id."'";
			$insertquery = mysqli_query($con,$delete);
			
			$final_result['Success'] = 'true';
			$final_result['Message'] = 'Remainder is deleted successfully.';
			$final_result['result'] = array();

		}

	echo json_encode($final_result);
?>