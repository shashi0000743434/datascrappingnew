<?php
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	$message_body = "welcome here";
	$transporter = Swift_SmtpTransport::newInstance('p3plcpnl0270.prod.phx3.secureserver.net', 587)
		->setUsername('leads@enzymelabssetup.com')
		->setPassword('Howard321');
		$mailer = Swift_Mailer::newInstance($transporter);
		$message = Swift_Message::newInstance(' YOU HAVE A NEW QUOTE REQUEST')
		
		->setFrom(array("gmtestingid123@gmail.com" => "FMAP LEAD"))
		->setTo(array('gmtestingid123@gmail.com'=> 'FMAP LEAD','gargrohit836@gmail.com'=> 'FMAP LEAD'))
		//->setTo(array('anilthakur6385@gmail.com'=> 'FMAP LEAD'))
		
		//->setBcc(array('anilkumarthakur6385@gmail.com'=>'FMAP LEAD'))
		->setBody($message_body,'text/html');
		//echo $message_body;
		$result = $mailer->send($message);

		if(!empty($result)){ 		
			echo "done";
	    }else{
	    	echo "not done";
	    }

?>