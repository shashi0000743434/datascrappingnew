<?php
	////////////////////////////////////////////////////////////////////////////////
	////// Script for Posting Consumer Phone number and Name to auto dialer API.////
    ////////////////////////////////////////////////////////////////////////////////
	
	// creating phone book.	
	//$phonebook_id=54316;
	$phonebook_id=74494; // Name: Realtors
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/swiftmailer/lib/swift_required.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	$sqlproperty_Query="select * from scrapped_realtor where phonebook_status = 0"; // fetching all records where phonebook_status is not updated
	//$sqlproperty_Query="select * from scrapped_realtor where id = 213";
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {
			$id					=	$db_field['id'];
			$phone_number		=	$db_field['phone'];
			
			$consumer_first_name =  $db_field['first_name'];
			$consumer_last_name =  $db_field['last_name'];
			
	
			if(trim($phone_number) != '') {
				//echo $phone_number.'<br>'; die;
				// calling the auto dialer API.
				$ch = curl_init();
				$api_key="f7eefc3f6640fabd205ddc1aed2bfe7a4ebd2263";
				$headers = array();
				$headers[] = "Authorization: token {$api_key}";
				$headers[] = "Content-Type: application/json";
				$data = array("contact" => $phone_number, 
				"first_name" =>  $consumer_first_name,
				"last_name" => $consumer_last_name,
				"mobile" => $phone_number); 
				//print "<pre>";print_r($data);print "</br/>";echo "<br/>";die;
				$data_to_post = json_encode($data);   
				$url ="https://api.callhub.io/v1/phonebooks/{$phonebook_id}/create_contact/";
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_post);
				$curl_response = curl_exec($ch); // Send request
				curl_close($ch);
				$decoded = json_decode($curl_response,true);		
				//print "<pre>";print_r($decoded); print "</pre>"; die;
				$response_id=$decoded['id'];
				if(!empty($response_id)){ // checking whether records added or not.
					// update the table.
					$sql_update="update scrapped_realtor set phonebook_status = 1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
					
				}
			
			} 
				

		}
   
	
	}

 
?>