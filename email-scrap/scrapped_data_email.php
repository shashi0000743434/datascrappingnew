<?php
    
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	$sqlproperty_Query="select * from scrapped_emails_1 where email_status = 0"; // fetching all records where email_status is not updated
	//$sqlproperty_Query="select * from scrapped_emails_1 where id = 1";
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {		
				
				$id					=	$db_field['id']; 
				$first_name			=	$db_field['first_name'];
				$last_name			=	$db_field['last_name'];
				$email				=	$db_field['email'];
				$phone				=	$db_field['phone'];
				$street_address		=	$db_field['street_address'];
				$city				=	$db_field['city'];
				$state				=	$db_field['state'];
				$postal_code		=	$db_field['postal_code'];
				$apt_suite			=	$db_field['apt_suite'];
				$contact_method		=	$db_field['contact_method'];
				$insurance_type		=	$db_field['insurance_type'];
				$lead_source		=	$db_field['lead_source'];
							
				
				$message_body="Hello Admin<br/><br/>Following is the new Other Lead Sources .<br/><br/>";	
				$message_body.="<table border='2'>";
				$message_body.="<tr><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>City</th><th>State</th><th>Postal Code</th><th>Apt/Suite</th><th>Contact Method</th><th>Insurance Type</th><th>Lead Source</th></tr>";
				$message_body.="<tr><td>".$first_name." ".$last_name."</td><td>".$email."</td><td><a href='tel:".$phone."'>".$phone."</a></td><td>".$street_address."</td><td>".$city."</td><td>".$state."</td><td>".$postal_code."</td><td>".$apt_suite."</td><td>".$contact_method."</td><td>".$insurance_type."</td><td>".$lead_source."</td></tr>";
				$message_body.="</tr></table>";
				$message_body.="<p>Thanks and regards</p>";
				
				//$transporter = Swift_SmtpTransport::newInstance('a2plvcpnl13722.prod.iad2.secureserver.net', 587)
				$transporter = Swift_SmtpTransport::newInstance('mail.enzymelabssetup.com', 25)
				->setUsername('leads@enzymelabssetup.com')
				->setPassword('Howard321');
				$mailer = Swift_Mailer::newInstance($transporter);
				$message = Swift_Message::newInstance('YOU HAVE A NEW OtherLeadSources LEAD REQUEST')
				
				->setFrom(array("leads@enzymelabssetup.com" => "OtherLeadSources LEAD"))
				->setTo(array('leadinfo@brightwaycoleagency.com'=> 'OtherLeadSources LEAD','enzymelabs1@a2plvcpnl17954.prod.iad2.secureserver.net'=> 'OtherLeadSources LEAD','cpanel@enzymelabssetup.com'=> 'OtherLeadSources LEAD'))
				//->setTo(array('keithfoxloans@gmail.com'=> 'OtherLeadSources LEAD'))
				//->setBcc(array('sunilmangal09@gmail.com'=>'OtherLeadSources LEAD'))
				
				
				->setBody($message_body,'text/html');
				//echo $message_body;
				//$result = $mailer->send($message);
       
				if($mailer->send($message)){ 
					// update the table.
					$sql_update="update scrapped_emails_1 set email_status = 1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
			    }
	
				
		    }
	
	
	}		
			
  ?>