<?php    
	echo "Here";
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	$sqlproperty_Query="select * from scrapped_data where email_sent_status IS NULL"; // fetching all records where auto_dialer_status is not updated
	//$sqlproperty_Query="select * from scrapped_data where email_sent_status=9"; 
	//echo $sqlproperty_Query="select * from scrapped_data where id=15000"; // fetching all records where auto_dialer_status is not updated
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	//echo mysqli_num_rows($property_Query);die();
	if(mysqli_num_rows($property_Query)>0){
		echo "dsdasdas";
	    while ($db_field = (mysqli_fetch_assoc($property_Query))) {	
		    	$id=$db_field['id']; 
				$property_address=$db_field['property_address'];
				$need_by=$db_field['need_by'];
				$amount=$db_field['amount'];
				$property_type=$db_field['property_type'];
				$year=$db_field['year'];
				$construction=$db_field['construction'];
				$consumer_info=str_replace("<none&gt","",$db_field['consumer']);	
				// extracting consumer number1.
				$consumer_info_arr=explode("Home:",$consumer_info); // extracting consumer phone number and name. 
				$consumer_name=explode(",",$consumer_info_arr[0]);
				$consumer_first_name=$consumer_name[0];
				$consumer_last_name=$consumer_name[1];
				$phone_number=trim(substr($consumer_info_arr[1], 0, strpos($consumer_info_arr[1], 'Cell')));
				$consumer_info=str_replace($phone_number, "<a href=\"tel:{$phone_number}\">{$phone_number}</a>", $consumer_info); // for first phone number
				
				// extracting consumer number2.
				$consumer_mobileinfo_arr=explode("Cell:",$consumer_info);	
		        $phone_number2=trim(substr($consumer_mobileinfo_arr[2], 0, strpos($consumer_mobileinfo_arr[1], 'Email')));
			    $consumer_info=str_replace($phone_number, "<a href=\"tel:{$phone_number2}\">{$phone_number2}</a>", $consumer_info); // for first phone number
				
				
				$message_body="Hello Admin<br/><br/>Following is the new FMAP lead.<br/><br/>";	
				$message_body.="<table border='2'>";
				$message_body.="<tr><th>Property Address</th><th>Consumer Information</th><th>Need By</th><th>Amount($)</th><th>Property Type</th><th>Year</th><th>Construction</th></tr>";
				$message_body.="<tr><td>".$property_address."</td><td>".$consumer_info."</td><td>".$need_by."</td><td>".$amount."</td><td>".$property_type."</td><td>".$year."</td><td>".$construction."</td></tr>";
				$message_body.="</tr></table>";
				$message_body.="<p>Thanks and regards</p>";
				
				$transporter = Swift_SmtpTransport::newInstance('68.178.195.121', 25)
				->setUsername('leads@enzymelabssetup.com')
				->setPassword('Howard321');
				$mailer = Swift_Mailer::newInstance($transporter);
				$message = Swift_Message::newInstance('YOU HAVE A NEW QUOTE REQUEST')
				
				->setFrom(array("leads@enzymelabssetup.com" => "FMAP LEAD"))
				->setTo(array(
				'fmapleads1@gmail.com'=> 'LEAD INFO',
				'ben@protectivechoice.com'=> 'Ben Barnes',
				'damien@protectivechoice.com'=> 'Damien',
				'jerry@protectivechoice.com'=> 'Jerry',
				'brett@protectivechoice.com'=> 'Brett',
				'monica.va@protectivechoice.com'=> 'Monica',
				'Chad@protectivechoice.com'=> 'Chad Bartlett',
				'amanda@protectivechoice.com'=> 'Amanda',
				'jason@protectivechoice.com'=>'Jason',
				'brad@protectivechoice.com'=>'Brad',
				'roda.va@protectivechoice.com'=>'Roda',
				'leads@protectivechoice.com'=>'Leads',))
				->setBcc(array('checkrecords@enzymelabssetup.com'=> 'FMAP LEAD'))
				->setBody($message_body,'text/html');
				echo $message_body;
			
				if($consumer_first_name!=""){
					echo "<br>sdasdsadasd";
				if($mailer->send($message,$failures)) {
				//if(!empty($result)){ 
					//var_dump($failures);
					echo "Sent messages";
					// update the table.
					echo $sql_update="update scrapped_data set email_sent_status=1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
			    }
				else {
					
					echo "messages not sent";
					
				}
				
				}else{
				    
					echo "consumer name is not there";
				}
				
		    }
	
	
	}		
			
  ?>				
