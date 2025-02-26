<?php
    
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	$sqlproperty_Query="select * from scrapped_emails_2 where email_status = 0"; // fetching all records where email_status is not updated
	//$sqlproperty_Query="select * from scrapped_emails_2 where id = 1";
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
				$postal_code		=	$db_field['postal_code'];
				$schedule_value		=	$db_field['schedule_value'];
				$hurricane_deductible=	$db_field['hurricane_deductible'];
				$year_roof_updated	=	$db_field['year_roof_updated'];
							
				
				$message_body="Hello Admin<br/><br/>Following is the new Cat4WebsiteLeads.<br/><br/>";	
				$message_body.="<table border='2'>";
				$message_body.="<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Address</th><th>City</th><th>Postal Code</th><th>Schedule A Value</th><th>Hurricane Deductible</th><th>Year Roof Updated</th></tr>";
				$message_body.="<tr><td>".$first_name."</td><td>".$last_name."</td><td>".$email."</td><td><a href='tel:".$phone."'>".$phone."</a></td><td>".$street_address."</td><td>".$city."</td><td>".$postal_code."</td><td>".$schedule_value."</td><td>".$hurricane_deductible."</td><td>".$year_roof_updated."</td></tr>";
				$message_body.="</tr></table>";
				$message_body.="<p>Thanks and regards</p>";
				
				$transporter = Swift_SmtpTransport::newInstance('p3plcpnl0270.prod.phx3.secureserver.net', 587)
				->setUsername('leads@enzymelabssetup.com')
				->setPassword('Howard321');
				$mailer = Swift_Mailer::newInstance($transporter);
				$message = Swift_Message::newInstance('YOU HAVE A NEW Cat4WebsiteLeads REQUEST')
				
				->setFrom(array("leads@enzymelabssetup.com" => "Cat4WebsiteLeads LEAD"))
				->setTo(array('leadinfo@brightwaycoleagency.com'=> 'Cat4WebsiteLeads LEAD','enzymelabs1@p3plcpnl0270.prod.phx3.secureserver.net'=> 'Cat4WebsiteLeads LEAD','cpanel@enzymelabssetup.com'=> 'Cat4WebsiteLeads LEAD'))
				//->setTo(array('keithfoxloans@gmail.com'=> 'Cat4WebsiteLeads LEAD'))
				//->setBcc(array('sunilmangal09@gmail.com'=>'Cat4WebsiteLeads LEAD'))
				
				
				->setBody($message_body,'text/html');
				//echo $message_body;
				//$result = $mailer->send($message);
       
				if($mailer->send($message)){ 
					// update the table.
					$sql_update="update scrapped_emails_2 set email_status = 1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
			    }
	
				
		    }
	
	
	}		
			
  ?>