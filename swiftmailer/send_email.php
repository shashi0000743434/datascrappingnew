<?php   
echo "Here";
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	echo "Here==".$sqlproperty_Query="select * from scrapped_data  limit 0,1"; 
	// fetching all records where auto_dialer_status is not updated
	
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);//echo mysqli_num_rows($property_Query);die();
	echo mysqli_num_rows($property_Query);
	echo $id=$db_field['id']; 
				$message_body.="sdsdasdas";	    
				$transporter = Swift_SmtpTransport::newInstance('68.178.195.121', 587)
				->setUsername('leads@enzymelabssetup.com')
				->setPassword('Howard321');
				$mailer = Swift_Mailer::newInstance($transporter);
				$message = Swift_Message::newInstance('YOU HAVE A NEW QUOTE REQUEST')
				
				->setFrom(array("leads@enzymelabssetup.com" => "FMAP LEAD"))
				->setTo(array(
				'atulsood.sood@gmail.com'=> 'LEAD INFO'				
				/* 'fmapleads1@gmail.com'=> 'LEAD INFO',
				'kellie@protectivechoice.com'=>'Kellie Hutchinson',
				'ben@protectivechoice.com'=> 'en Barnes',
				'ron@protectivechoice.com'=> 'Ron Joseph',
				'angel@protectivechoice.com'=> 'Angel Bartoszewicz',
				'amanda@protectivechoice.com'=> 'Amanda',
				'damien@protectivechoice.com'=> 'Damien Ramjattan',
				'mail2bilalkhokhar@gmail.com'=>'bilal',
				/* 'jennifer.masso@brightway.com'=> 'Jennifer Masso',
				'jason.koulesser@brightway.com'=> 'Jason Koulesser',
				'mike.doyle@brightway.com'=> 'Mike Doyle',
				'ben.barnes@brightway.com'=> 'Ben Barnes',
				'damien.ramjattan@brightway.com'=> 'Damien Ramjattan' */
				))
				->setBcc(array('atulsood.sood@gmail.com'=> 'FMAP LEAD'))
				
				
				->setBody($message_body,'text/html');
				echo "dfsdfsdfsd".$message_body;
				$result=$mailer->send($message,$failures);
				print_r($result);
					print_r($failures);
					var_dump($failures);
				if($mailer->send($message,$failures)) {
					print_r($result);
					print_r($failures);
					var_dump($failures);
				if(!empty($result)){ 
					echo "Sent messages";
					var_dump($failures);
					
					// update the table.
				//	$sql_update="update scrapped_data set email_sent_status=1 where id='".$id."'";
				//	$result_update = mysqli_query($db_handle, $sql_update);
			    }
				else {
					
					echo "messages not sent";
					
				}
				
				}
				
		
			
  ?>				
