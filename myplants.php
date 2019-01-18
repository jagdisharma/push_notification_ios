<?php
date_default_timezone_set('UTC');
	include "configration.php";
	$final_result = array();

        $user_id = $_REQUEST['user_id'];

	if($user_id=='')
	{
		$array_temp['Success'] = 'false';
		$array_temp['Message'] = 'Please enter user_id.';
	}
	else
	{
		$select = "SELECT * FROM plants WHERE is_delete != 1 AND user_id='$user_id' order by order_id";
                $selectquery = mysqli_query($con,$select);
                $num = mysqli_num_rows($selectquery);
                
                if($num>0)
                {
                
                	$j=0;
                    $rowInc = 1;
                        while($selectdata = mysqli_fetch_assoc($selectquery))
                        {
                        	
                        	$plantid = $selectdata['plant_id'];
                                
                            $selimg = "SELECT * FROM plant_images where plant_id='$plantid' and user_id='$user_id'";
                        	$selimgquery = mysqli_query($con,$selimg);
                        	
                        	$k=0;
                        	while($images = mysqli_fetch_assoc($selimgquery))
                        	{
                        		
                        		//$pltimages[$j][$k][] = $images;
                                $images['uploaded_at'] = date("Y-m-d h:i:s");
                                $filename = "uploads/".$images['image'];
                                if (file_exists($filename)) {
                                    $images['uploaded_at'] = date ("Y-m-d h:i:s", filemtime($filename));
                                }
                                
                                $pltimages[$j][$k][] = $images;
                        		$k++;
                        		
                        	}
                        	
                        	//$l=0;
                        	$selrem = "select reminder_id,plant_id,user_id,title,notes, daytime, timezone, repeat_reminders, customDict as custDict, intervalRemind as `interval`, repeatInterval, notifTitle from reminders where plant_id='$plantid' and user_id='$user_id'";
                        	$selremquery = mysqli_query($con,$selrem);
                        	$remainders = mysqli_fetch_assoc($selremquery);
                            // echo "<pre>";
                            // print_r($remainders);
                        	
                        	
                        	/*while($remainders = mysql_fetch_assoc($selremquery))
                        	{
                        		$pltremainder[$j][$l][] = $remainders;
                        		$l++;
                        	}*/
                        	
                            $data[$j]['plant_id']           = ($selectdata['plant_id']!=NULL)?$selectdata['plant_id']:"";
                            $data[$j]['user_id']            = ($selectdata['user_id']!=NULL)?$selectdata['user_id']:"";
                            $data[$j]['title']              = ($selectdata['title']!=NULL)?$selectdata['title']:"";
                            $data[$j]['genus']              = ($selectdata['genus']!=NULL)?$selectdata['genus']:"";
                            $data[$j]['species']            = ($selectdata['species']!=NULL)?$selectdata['species']:"";
                            $data[$j]['subspecies']         = ($selectdata['subspecies']!=NULL)?$selectdata['subspecies']:"";
                            $data[$j]['variety']            = ($selectdata['variety']!=NULL)?$selectdata['variety']:"";
                            //$data[$j]['form']               = $selectdata['form'];
                            $data[$j]['location']           = ($selectdata['location']!=NULL)?$selectdata['location']:"";
                            $data[$j]['size']               = ($selectdata['size']!=NULL)?$selectdata['size']:"";
                            $data[$j]['purchased_date']     = date("d-m-Y", strtotime($selectdata['purchased_date']));
                            $data[$j]['purchased_from']     = ($selectdata['purchased_from']!=NULL)?$selectdata['purchased_from']:"";
                            $data[$j]['purchased_price']    = ($selectdata['purchased_price']!=NULL)?$selectdata['purchased_price']:"";
                            $data[$j]['notes']              = ($selectdata['notes']!=NULL)?$selectdata['notes']:"";
                            $data[$j]['purchased_as']       = ($selectdata['purchased_as']!=NULL)?$selectdata['purchased_as']:"";
                            $data[$j]['date_shown']         = date("d-m-Y", strtotime($selectdata['date_shown']));
                            $data[$j]['cultivar']           = ($selectdata['cultivar']!=NULL)?$selectdata['cultivar']:"";
                            $data[$j]['order_id']           = (string) $rowInc++;
                            $data[$j]['reference_number']   = ($selectdata['reference_number']!=NULL)?$selectdata['reference_number']:"";
                            $data[$j]['watering']           = ($selectdata['watering']!=NULL)?$selectdata['watering']:"";
                            $data[$j]['soil']               = ($selectdata['soil']!=NULL)?$selectdata['soil']:"";
                            $data[$j]['fertilize']          = ($selectdata['fertilize']!=NULL)?$selectdata['fertilize']:"";
                            $data[$j]['sun']                = ($selectdata['sun']!=NULL)?$selectdata['sun']:"";
                            $data[$j]['dormancy']           = ($selectdata['dormancy']!=NULL)?$selectdata['dormancy']:"";

                            //watering`= '$watering',`soil`= '$soil',`fertilize`= '$fertilize',`sun`= '$sun',`dormancy`= '$dormancy'    
                        	//$data[$j]['remainders'] = $pltremainder[$j];
                        	//$data[$j]['remainders'] = $remainders['daytime'];

                                /*$DATE_PURCHASE =  $data[$j]['purchased_date'];
                                $newDate = date("d-m-Y", strtotime($DATE_PURCHASE));*/
                               /* echo $data[$j]['purchased_date'];
                                exit("--");*/

                        	if($remainders>0)
                        	{
                        	    $data[$j]['remainders'] = $remainders['daytime'];
                        	}else{
                        	    $data[$j]['remainders'] = "";
                        	}
                            if($remainders>0)
                            {
                                $data[$j]['remaindersInfo'] = $remainders;
                            }else{
                                $data[$j]['remaindersInfo'] = "";
                            }
                        	if($pltimages[$j] > 0)
                        	{
                        	    $data[$j]['images'] = $pltimages[$j];
                        	}else{
                        	    //$data[$j]['images'] = array();
                        	}
                        	
                        	$j++;
                       
                        }           
                        /*echo "<pre>";
                        print_r($data);
                        exit;*/
                        $array_temp['Success'] = 'true';
                        $array_temp['Message'] = 'Plants Found.';
                        $array_temp['result'] = $data;
                        
                }
                else
                {
                        $array_temp['Success'] = 'false';
                        $array_temp['Message'] = 'Plants Not Found.';
                }
	}

	$final_result = $array_temp;
	echo json_encode($final_result);
?>
