<?php
// print_r($_REQUEST);
	include "configration.php";
	$final_result = array();
		$user_id 			= $_REQUEST['user_id'];
		$title 				= $_REQUEST['title'];
		$genus 				= $_REQUEST['genus'];
		$species 			= $_REQUEST['species'];
		$subspecies 		= $_REQUEST['subspecies'];
		$variety			= $_REQUEST['variety'];
		$location 			= $_REQUEST['location'];
		$size 				= $_REQUEST['size'];
		$purchased_date 	= $_REQUEST['purchased_date']; 
		$purchased_from 	= $_REQUEST['purchased_from'];
		$purchased_price 	= $_REQUEST['purchased_price'];
		$remainder_title 	= $_REQUEST['remainder_title'];
		$remainder_date 	= $_REQUEST['remainder_date'];
		$remainder_notes 	= $_REQUEST['remainder_notes'];
		$timezone 			= $_REQUEST['timezone'];
		$notes 				= $_REQUEST['notes'];
		$version			= $_REQUEST['version'];
		$purchased_as		= $_REQUEST['purchased_as'];
		$date_shown			= $_REQUEST['date_shown'];
		$cultivar			= $_REQUEST['cultivar'];
		$notifTitle = '';
		if(isset($_REQUEST['notifTitle'])) {
           $notifTitle = $_REQUEST['notifTitle'];
        } 
		$repeat_reminders = ''; 
        if(isset($_REQUEST['repeat_reminders'])) {
           $repeat_reminders = $_REQUEST['repeat_reminders'];
        }
		$watering = '';
		if(isset($_REQUEST['watering'])) {
           $watering = $_REQUEST['watering'];
        } 
		$soil = ''; 
        if(isset($_REQUEST['soil'])) {
           $soil = $_REQUEST['soil'];
        }
		$fertilize = '';
		if(isset($_REQUEST['fertilize'])) {
           $fertilize = $_REQUEST['fertilize'];
        } 
		$sun = ''; 
        if(isset($_REQUEST['sun'])) {
           $sun = $_REQUEST['sun'];
        }
		$dormancy = ''; 
        if(isset($_REQUEST['dormancy'])) {
           $dormancy = $_REQUEST['dormancy'];
        }


		$reminderId = '';
		$_date = '';
		if($purchased_date != '')
		{
			$date 			= date_create($purchased_date);
			$_date 			= date_format($date,"Y-m-d");
		}else{
			$_date = $_date;
		}

		$SOWN_date = '';
		if ($date_shown != '') {
			$SOWNdate 		= date_create($date_shown);
			$SOWN_date 		= date_format($SOWNdate,"Y-m-d");
		}else{
			$SOWN_date	=	$SOWN_date;
		}


		

		$customDict = '';
		$Interval = '';
		$repeatInterval = '';
		if(isset($_REQUEST['custDict'])) {
           $customDict = $_REQUEST['custDict'];
        }
        if(isset($_REQUEST['interval'])) {
           $Interval = $_REQUEST['interval'];
        }
        if(isset($_REQUEST['repeatInterval'])) {
           $repeatInterval = $_REQUEST['repeatInterval'];
        }
		//elseif($genus=='')
		// {
		// 	$array_temp['Success'] = 'false';
		// 	$array_temp['Message'] = 'Please enter genus.';
		// }
		if($user_id=='')
		{
			$array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Please enter user_id.';
		}
		else if($title=='')
		{
			$array_temp['Success'] = 'false';
			$array_temp['Message'] = 'Please enter title.';
		}
		else
		{
			
			$fetchData = mysqli_query($con,"SELECT * FROM plants WHERE user_id =".$user_id);
			$res = mysqli_num_rows($fetchData);
			$res = $res+1;
			
			$qry = mysqli_query($con,"SELECT plant_id FROM plants WHERE is_delete = 1 AND user_id =".$user_id." limit 1");
			$_esr = mysqli_num_rows($qry);
			while ($row = mysqli_fetch_assoc($qry)) {
				$data = $row['plant_id'];
			}
			$is_delete = 0;

			if ($data != '') 
			{
				/*echo "UPDATE `plants` SET `user_id`= '$user_id',`title`= '$title',`genus`= '$genus',`species`= '$species',`subspecies`= '$subspecies',`variety`= '$variety',`location`= '$location',`size`= '$size',`purchased_date`= '$_date',`purchased_from`= '$purchased_from',`purchased_price`= '$purchased_price',`notes`='$notes',`purchased_as`= '$purchased_as',`date_shown`= '$SOWN_date',`cultivar`= '$cultivar',`is_delete`= '$is_delete' WHERE `plant_id`=$data";*/
				//exit('++');
				$insert = "UPDATE `plants` SET `user_id`= '$user_id',`title`= '$title',`genus`= '$genus',`species`= '$species',`subspecies`= '$subspecies',`variety`= '$variety',`location`= '$location',`size`= '$size',`purchased_date`= '$_date',`purchased_from`= '$purchased_from',`purchased_price`= '$purchased_price',`notes`='$notes',`purchased_as`= '$purchased_as',`date_shown`= '$SOWN_date',`cultivar`= '$cultivar',`is_delete`= '$is_delete',`watering`= '$watering',`soil`= '$soil',`fertilize`= '$fertilize',`sun`= '$sun',`dormancy`= '$dormancy' WHERE `plant_id`=$data";
				
				$insert_query = mysqli_query($con,$insert);
					
					if($remainder_title!='')
					{
						$insertremainder = "INSERT INTO reminders (plant_id,user_id,title,notes,daytime,timezone,repeat_reminders,customDict,intervalRemind,repeatInterval,notifTitle) VALUES ('$data','$user_id','$remainder_title','$remainder_notes','$remainder_date','$timezone','$repeat_reminders','$customDict','$Interval','$repeatInterval','$notifTitle')";

						$insertremainder_query = mysqli_query($con,$insertremainder);

						$reminderId = mysqli_insert_id($con);
					}


						$target_dir = "uploads/"; 
						$images = $_FILES["img"]["name"];
						$imgtemp = $_FILES["img"]["tmp_name"];
						for($i=0;$i<count($images);$i++)
						{
							$image_name = $data.'_'.basename($images[$i]);
							$target_file = $target_dir.$data.'_'.basename($images[$i]); 
							move_uploaded_file($imgtemp[$i],$target_file);

							$insertimg = "INSERT INTO plant_images (plant_id,user_id,image) VALUES ('$data','$user_id','$image_name')";
							$insertimg_query = mysqli_query($con,$insertimg);
						}		
		
					$sel = "SELECT * FROM plants WHERE plant_id='".$data."'";
					$sel_qry = mysqli_query($con,$sel);
					$data = mysqli_fetch_assoc($sel_qry);
					
					$array_temp['Success'] = 'true';
					$array_temp['Message'] = 'Your Plant is added successfully.';
					$array_temp['result'] = $data;
					$array_temp['reminderId'] = $reminderId;
				//}
				
			}else{
				/*echo "INSERT INTO plants (user_id,title,genus,species,subspecies,variety,location,size,purchased_date,purchased_from,purchased_price,notes,purchased_as,date_shown,cultivar,order_id,reference_number) VALUES ('$user_id','$title','$genus','$species','$subspecies','$variety','$location','$size','$_date','$purchased_from','$purchased_price','$notes', '$purchased_as' , '$SOWN_date' ,'$cultivar' , $res )";
					exit("--");*/

				$insert = "INSERT INTO plants (user_id,title,genus,species,subspecies,variety,location,size,purchased_date,purchased_from,purchased_price,notes,purchased_as,date_shown,cultivar,order_id,reference_number,watering,soil,fertilize,sun,dormancy) VALUES ('$user_id','$title','$genus','$species','$subspecies','$variety','$location','$size','$_date','$purchased_from','$purchased_price','$notes', '$purchased_as' , '$SOWN_date' ,'$cultivar' , $res , $res, '$watering','$soil','$fertilize','$sun','$dormancy' )";
				$insert_query = mysqli_query($con,$insert);
				$last = mysqli_insert_id($con);
		
				if(mysqli_affected_rows($con)=='1')
				{
					if($remainder_title!='')
					{
						$insertremainder = "INSERT INTO reminders (plant_id,user_id,title,notes,daytime,timezone,repeat_reminders,customDict,intervalRemind,repeatInterval,notifTitle) VALUES ('$last','$user_id','$remainder_title','$remainder_notes','$remainder_date','$timezone','$repeat_reminders','$customDict','$Interval','$repeatInterval','$notifTitle')";

						$insertremainder_query = mysqli_query($con,$insertremainder);

						$reminderId = mysqli_insert_id($con);
					}

						$target_dir = "uploads/"; 
						$images = $_FILES["img"]["name"];
						$imgtemp = $_FILES["img"]["tmp_name"];
						for($i=0;$i<count($images);$i++)
						{
							$image_name = $last.'_'.basename($images[$i]);
							$target_file = $target_dir.$last.'_'.basename($images[$i]); 
							move_uploaded_file($imgtemp[$i],$target_file);

							$insertimg = "INSERT INTO plant_images (plant_id,user_id,image) VALUES ('$last','$user_id','$image_name')";
							$insertimg_query = mysqli_query($con,$insertimg);
						}		
		
					$sel = "SELECT * FROM plants WHERE plant_id='".$last."'";
					$sel_qry = mysqli_query($con,$sel);
					$data = mysqli_fetch_assoc($sel_qry);
					
					$array_temp['Success'] = 'true';
					$array_temp['Message'] = 'Your Plant is added successfully.';
					$array_temp['result'] = $data;
					$array_temp['reminderId'] = $reminderId;
				}
			}

	}

	$final_result = $array_temp;
	echo json_encode($final_result);
?>