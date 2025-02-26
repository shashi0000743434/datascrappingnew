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
	$sqlproperty_Query="select * from scrapped_emails_1 where ctct_status = 0"; 	
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
			$contact_method		=	$db_field['contact_method'];
			$insurance_type		=	$db_field['insurance_type'];
			$lead_source		=	$db_field['lead_source'];
				
			
			$cf_date_value_month=	date("m");
			$cf_date_value_day	=	date("d");
			$cf_date_value_year	=	date("Y");
			
			// submitting data to form.
			$ch = curl_init();	
			$url="https://visitor2.constantcontact.com/api/signup"; 
	        //$postinfo = "ca=8c040eaf-e53c-4290-8de0-35a8f3dafbb5&list=OtherLeadSources&source=EFD&required=list,email,first_name&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_country=&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_date_value_month--1=$cf_date_value_month&cf_date_value_day--1=$cf_date_value_day&cf_date_value_year--1=$cf_date_value_year&cf_date_name--1=date_entered&cf_date_label--1=date entered";
			$postinfo = "ca=8c040eaf-e53c-4290-8de0-35a8f3dafbb5&list=1713011698&source=EFD&required=list,cf_date--1,cf_text--2,email,first_name,last_name,phone,address_street,address_city,address_state,address_postal_code&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_date_value_month--1=$cf_date_value_month&cf_date_value_day--1=$cf_date_value_day&cf_date_value_year--1=$cf_date_value_year&cf_date_name--1=date_entered&cf_date_label--1=date entered&cf_text_value--2=$insurance_type&cf_text_name--2=policy_type1&cf_text_label--2=policy type1";
			
			curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
			$result = curl_exec($ch); 
			curl_close($ch);
			$response= json_decode($result);
			print_r($result);
			if($response==1){
			
				// update the table.
				$sql_update="update scrapped_emails_1 set ctct_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
			}
			
		
				
		}
		
		
	}
	

?>