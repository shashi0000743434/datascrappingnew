<?php
	$ch = curl_init();
	$headers = array();
	$api_key="f7eefc3f6640fabd205ddc1aed2bfe7a4ebd2263";
	$headers[] = "Authorization: token {$api_key}";
	$headers[] = "Content-Type: application/json";
	//$data = array("name" => "FMAP CONTACTS", "description" => "FMAP CONTACTS");                         
	$data = array("name" => "FMAP Phonebook", "description" => "FMAP Phonebook contacts");                         
	$data_to_post = json_encode($data);   
	$url ="https://api.callhub.io/v1/phonebooks/";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch,CURLOPT_POSTFIELDS, $data_to_post);
	$curl_response = curl_exec($ch); // Send request
	print_r(curl_error($ch));
	curl_close($ch);
	$decoded_phone_book = json_decode($curl_response,true);
	print "<pre>";print_r($decoded_phone_book);print "</pre>";
	echo $phonebook_id=$decoded_phone_book['id'];
	
	
	
 
?>