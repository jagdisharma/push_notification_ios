<?php

	include "configration.php";
	$final_result = array();

        $plant_id = $_REQUEST['plant_id'];
        $user_id = $_REQUEST['user_id'];

        if($plant_id=='')
        {
                $array_temp['Success'] = 'false';
		$array_temp['Message'] = 'Please enter plant_id.';
        }
	elseif($user_id=='')
	{
		$array_temp['Success'] = 'false';
		$array_temp['Message'] = 'Please enter user_id.';
	}
	else
	{
                //$data = mysql_query("SELECT * FROM plants WHERE plant_id='$plant_id' and user_id='$user_id'");

                $select = "select * from plants where plant_id='$plant_id' and user_id='$user_id'";
                $selectquery = mysqli_query($con,$select);

                while($row = mysqli_fetch_array($selectquery))
                {
                        $orderID = $row['order_id'];
                }

		//$delete = "delete from plants where plant_id='$plant_id' and user_id='$user_id'";
                $delete = "UPDATE plants SET is_delete =1 WHERE plant_id='$plant_id' and user_id='$user_id'";
                $deletequery = mysqli_query($con,$delete);
                
                if(mysqli_affected_rows($con)=='1')
                {
                        /*$d_update = "UPDATE plants SET order_id = order_id-1 WHERE order_id > $orderID and user_id='$user_id'";
                        $dd_update = mysql_query($d_update);*/
                        
                	$deleterem = "delete from reminders where plant_id='$plant_id' and user_id='$user_id'";
                	$deleteremquery = mysqli_query($con,$deleterem);
                	
                	$getimgs = "select image from plant_images where plant_id='$plant_id' and user_id='$user_id'";
                	$getimgsquery = mysqli_query($con,$getimgs);
                	
                	while($plantimages = mysqli_fetch_assoc($getimgsquery))
                	{
                		$pltimg[] = $plantimages;
                	}
                	
                	for($i=0;$i<count($pltimg);$i++)
                	{
                		unlink("uploads/".$pltimg[$i]['image']);
                	}
                	
                	$deleteimg = "delete from plant_images where plant_id='$plant_id' and user_id='$user_id'";
                	$deleteimgquery = mysqli_query($con,$deleteimg);
                	
                        $array_temp['Success'] = 'true';
                        $array_temp['Message'] = 'Plant Deleted Successfully.';
                }
                else
                {
                        $array_temp['Success'] = 'false';
                        $array_temp['Message'] = 'Processing Error Plant is Not Deleted.';
                }
	}

	$final_result = $array_temp;
	echo json_encode($final_result);
?>
