<?php
    /**
	@@ Script for posting data for email purposes.
	@@
	@@
	**/	
	
	error_reporting(E_ALL);
	ini_set("display_errors", 1);	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);	
	$sqlproperty_Query="select * from scrapped_realtor where ctct_status = 0"; 	
	//$sqlproperty_Query="select * from scrapped_realtor WHERE id  = 2"; 		
	
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {	

			$id			=	$db_field['id']; 
			$first_name	=	$db_field['first_name'];
			$last_name	=	$db_field['last_name'];
			if($last_name == '') {
				$last_name = 'N/A';
			}
			$email		=	$db_field['email'];
			$phone		=	$db_field['phone'];
			
			$address = explode(',',$db_field['address']);
			if(isset($address[0])) {
				$street_address =	$address[0];
			}
			else {
				$street_address =	'NA';
			}
			if(isset($address[1])) {
				$city	=	$address[1];
			}
			else {
				$city	=	'NA';
			}
			if(isset($address[2])){
				$state_zip = explode(' ',trim($address[2]));
				if(isset($state_zip[0])) {
					$state	=	$state_zip[0];
				}
				else {
					$state	=	'FL';
				}
				if(isset($state_zip[1])) {
					$postal_code	=	$state_zip[1];
				}
				else {
					$postal_code	=	'33404';
				}
			}
			else {
				$state	=	'FL';
				$postal_code	=	'33404';
			}
			
			$mls_id		=	$db_field['mls_id'];
				
			
			//$cf_date_value_month=	date("m");
			//$cf_date_value_day	=	date("d");
			//$cf_date_value_year	=	date("Y");
			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://visitor.r20.constantcontact.com/manage/optin?v=001fF7rnIqcTi_SG89YYbyxWbtVXzrA-_-Gnbenq5lOkUnuDMLgj6mDNdvI_VX9vh_7XBJeb0t4FwqB7_ofdtPXEbEnoX6otM860usCaHshyqM%3D");
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); 
			curl_close($ch);
			$result = explode('captcha.control" type="hidden" value="',$result);
			$result1 = explode('"',$result[1]);
			$captcha = $result1[0];
			
			// submitting data to form.
			$ch = curl_init();	
			$url="https://visitor.r20.constantcontact.com/manage/optin?v=001fF7rnIqcTi_SG89YYbyxWbtVXzrA-_-Gnbenq5lOkUnuDMLgj6mDNdvI_VX9vh_7XBJeb0t4FwqB7_ofdtPXEbEnoX6otM860usCaHshyqM%3D"; 
	        $captchaurl = 'http://visitor.r20.constantcontact.com/manage/optin&captcha.remoteAddr=122.180.20.83';
		
			$postinfo = "subscriberProfile.visitorProps[0].value=$email&subscriberProfile.visitorProps[0].id=80&subscriberProfile.visitorProps[0].name=Email Address&subscriberProfile.visitorProps[0].maxLen=50&subscriberProfile.visitorProps[0].required=true&subscriberProfile.visitorProps[0].inputType=input&subscriberProfile.visitorProps[0].refDataKey=&subscriberProfile.visitorProps[0].customPropID=&subscriberProfile.visitorProps[1].value=$first_name&subscriberProfile.visitorProps[1].id=100&subscriberProfile.visitorProps[1].name=First Name&subscriberProfile.visitorProps[1].maxLen=50&subscriberProfile.visitorProps[1].required=true&subscriberProfile.visitorProps[1].inputType=input&subscriberProfile.visitorProps[1].refDataKey=&subscriberProfile.visitorProps[1].customPropID=&subscriberProfile.visitorProps[2].value=$last_name&subscriberProfile.visitorProps[2].id=300&subscriberProfile.visitorProps[2].name=Last  Name&subscriberProfile.visitorProps[2].maxLen=50&subscriberProfile.visitorProps[2].required=true&subscriberProfile.visitorProps[2].inputType=input&subscriberProfile.visitorProps[2].refDataKey=&subscriberProfile.visitorProps[2].customPropID=&subscriberProfile.visitorProps[3].value=$phone&subscriberProfile.visitorProps[3].id=800&subscriberProfile.visitorProps[3].name=Phone Number&subscriberProfile.visitorProps[3].maxLen=50&subscriberProfile.visitorProps[3].required=true&subscriberProfile.visitorProps[3].inputType=input&subscriberProfile.visitorProps[3].refDataKey=&subscriberProfile.visitorProps[3].customPropID=&subscriberProfile.visitorProps[4].value=$street_address&subscriberProfile.visitorProps[4].id=900&subscriberProfile.visitorProps[4].name=Street Address&subscriberProfile.visitorProps[4].maxLen=50&subscriberProfile.visitorProps[4].required=false&subscriberProfile.visitorProps[4].inputType=input&subscriberProfile.visitorProps[4].refDataKey=&subscriberProfile.visitorProps[4].customPropID=&subscriberProfile.visitorProps[5].value=$city&subscriberProfile.visitorProps[5].id=1200&subscriberProfile.visitorProps[5].name=City&subscriberProfile.visitorProps[5].maxLen=50&subscriberProfile.visitorProps[5].required=false&subscriberProfile.visitorProps[5].inputType=input&subscriberProfile.visitorProps[5].refDataKey=&subscriberProfile.visitorProps[5].customPropID=&subscriberProfile.visitorProps[6].value=$state&subscriberProfile.visitorProps[6].id=1300&subscriberProfile.visitorProps[6].name=State/Province&subscriberProfile.visitorProps[6].maxLen=50&subscriberProfile.visitorProps[6].required=false&subscriberProfile.visitorProps[6].inputType=select&subscriberProfile.visitorProps[6].refDataKey=states&subscriberProfile.visitorProps[6].customPropID=&subscriberProfile.visitorProps[7].value=$postal_code&subscriberProfile.visitorProps[7].id=1700&subscriberProfile.visitorProps[7].name=Zip Code&subscriberProfile.visitorProps[7].maxLen=25&subscriberProfile.visitorProps[7].required=false&subscriberProfile.visitorProps[7].inputType=input&subscriberProfile.visitorProps[7].refDataKey=&subscriberProfile.visitorProps[7].customPropID=&subscriberProfile.visitorProps[8].value=us&subscriberProfile.visitorProps[8].id=1500&subscriberProfile.visitorProps[8].name=Country&subscriberProfile.visitorProps[8].maxLen=25&subscriberProfile.visitorProps[8].required=false&subscriberProfile.visitorProps[8].inputType=select&subscriberProfile.visitorProps[8].refDataKey=countries&subscriberProfile.visitorProps[8].customPropID=&subscriberProfile.visitorProps[9].value=$mls_id&subscriberProfile.visitorProps[9].id=2100&subscriberProfile.visitorProps[9].name=Custom Field 1&subscriberProfile.visitorProps[9].maxLen=50&subscriberProfile.visitorProps[9].required=true&subscriberProfile.visitorProps[9].inputType=input&subscriberProfile.visitorProps[9].refDataKey=&subscriberProfile.visitorProps[9].customPropID=custom_field_1&_save=Sign Up&captcha.url=$captchaurl&captcha.challenge=&captcha.response=&captcha.key=6LcB5AIAAAAAANx7J2ADgUFxwd_zllfY4DxX81c5&captcha.control=$captcha&subscriptions.interestCategories[0].id=1143457130&subscriptions.interestCategories[0].name=Realtor.com&subscriptions.interestCategories[0].selected=true&_subscriptions.interestCategories[0].selected=on&subscriberProfile.visitorParams=001fF7rnIqcTi_SG89YYbyxWbtVXzrA-_-Gnbenq5lOkUnuDMLgj6mDNdvI_VX9vh_7XBJeb0t4FwqB7_ofdtPXEbEnoX6otM860usCaHshyqM=&subscriptions.interestCategoriesSize=1&subscriberProfile.visitorPropertiesSize=10";
			
			curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); 
			curl_close($ch);
			$response= json_decode($result);
			print_r($result); 
			if($result) {
				mysqli_query($db_handle,"update scrapped_realtor SET ctct_status = 1 WHERE id  = $id");
			}
			
			
		
		}
		
		
	}
	
?>