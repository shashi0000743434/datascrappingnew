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
	$sqlproperty_Query="select * from scrapped_emails_2 where ctct_status = 0"; 	
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
			$state				=	$db_field['state'];
			$postal_code		=	$db_field['postal_code'];
			$schedule_value		=	$db_field['schedule_value'];
			$hurricane_deductible=	urlencode($db_field['hurricane_deductible']);
			$year_roof_updated	=	$db_field['year_roof_updated'];
 	
			
			$cf_date_value_month=	date("m");
			$cf_date_value_day	=	date("d");
			$cf_date_value_year	=	date("Y");
			$blk = ' ';
			$name_7 = 'what_is_the_schedule_a_value_of_your_home';
			$lab_7 = 'what_is_the_schedule_a_value_of-your-home?';
			
			// submitting data to form.
			$ch = curl_init();	
			$url="https://visitor2.constantcontact.com/api/signup"; 
	        $postinfo = "ca=a41adf9f-e938-4be4-b7bf-7f2e980675d0&list=1162386597&source=EFD&required=list,email&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_postal_code=$postal_code&cf_text_value--0=$phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_text_value--1=$city&cf_text_name--1=city&cf_text_label--1=city&cf_date_value_month--2=$cf_date_value_month&cf_date_value_day--2=$cf_date_value_day&cf_date_value_year--2=$cf_date_value_year&cf_date_name--2=date_entered&cf_date_label--2=date entered&cf_date_value_month--3=$cf_date_value_month&cf_date_value_day--3=$cf_date_value_day&cf_date_value_year--3=$cf_date_value_year&cf_date_name--3=full_name&cf_date_label--3=full_name&cf_text_value--4=$blk&cf_text_name--4=insurance_carrier&cf_text_label--4=insurance carrier&cf_text_value--5=Cat4WebsiteLeads&cf_text_name--5=lead_source&cf_text_label--5=lead source&cf_text_value--6=$street_address&cf_text_name--6=street_address&cf_text_label--6=street_address&cf_text_value--7=$schedule_value&cf_text_name--7=$name_7&cf_text_label--7=$lab_7&cf_text_value--8=$hurricane_deductible&cf_text_name--8=what_is_your_current_hurricane_deductible&cf_text_label--8=what_is_your_current_hurricane_deductible&cf_text_value--9=$year_roof_updated&cf_text_name--9=what_year_was_your_roof_last_updated&cf_text_label--9=what_year_was_your_roof_last_updated?&cf_text_value--10=$blk&cf_text_name--10=year_house_built&cf_text_label--10=Year House Built";	
			
			curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
			$result = curl_exec($ch); 
			//echo '<pre>';
			//print_r($result);
			curl_close($ch);
			$response= json_decode($result);
			echo $response;
			if($response==1){
			
				// update the table.
				$sql_update="update scrapped_emails_2 set ctct_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
			}
			
		
				
		}
		
		
	}
	

?>