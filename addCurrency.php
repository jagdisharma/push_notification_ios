<?php
date_default_timezone_set('UTC');
include "configration.php";

	if(isset($_POST) && $_POST['email']!='' && $_POST['currencyCode']!=''){

		$sql_ref =  mysqli_query($con,"SELECT * FROM users WHERE email = '".$_POST['email']."'");
		$count = mysqli_num_rows($sql_ref);

		if($count > 0){
			/*$roww = mysqli_fetch_assoc($sql_ref); 
			$symbol = $roww['currency_symbol'];*/

			$query = "UPDATE users SET currency_symbol = '".$_POST['currencyCode']."' WHERE email = '".$_POST['email']."'";
			mysqli_query($con,$query);

			echo json_encode(array('success' => 1, 'data'=>'currency updated successfully' ));

		}else{
			echo json_encode(array('success' => 0, 'data'=>'email doesnot exist' ));
		}

	} else {
		echo json_encode(array('success' => 0, 'data'=>'fields cannot be empty' ));
	}

