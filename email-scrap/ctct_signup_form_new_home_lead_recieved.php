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
	$sqlproperty_Query="select * from scrapped_new_home_lead_received where ctct_status = 0"; 	
	//$sqlproperty_Query="select * from scrapped_new_home_lead_received where id = 1"; 		
	
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {		
			$id					=	$db_field['id']; 
			
			$name = explode(' ',$db_field['name']);
			
			$first_name			=	$name[0];
			if(count($name)>1){
				$last_name			=	$name[1];
			}
			else {
				$last_name			=	' ';
			}
			
			
			$email				=	$db_field['email_address'];
			$phone				=	$db_field['daytime_phone'];
			
			$location = explode(',',$db_field['location']);
			$adderss_det = explode(' ',trim($location[1]));
			
			$street_address		=	$db_field['street_address'];
			$city				=	$location[0];
			$state				=	$adderss_det[0];
			
			if(count($adderss_det)>1){
				$postal_code			=	$adderss_det[1];
			}
			else {
				$postal_code			=	' ';
			}
			
			$cell_phone		=	str_replace(')','',str_replace('(','',$db_field['daytime_phone']));
			$year_built		=	$db_field['year_built'];
			if($year_built=='') { $year_built = ' '; }
			
			
			$coverage_amount		=	$db_field['coverage_amount'];
				
			
			$cf_date_value_month=	date("m");
			$cf_date_value_day	=	date("d");
			$cf_date_value_year	=	date("Y");
			
			$val4 = $val5 = $val6 = ' ';
			
			// submitting data to form.
			$ch = curl_init();	
			$url="https://visitor2.constantcontact.com/api/signup"; 
	        //$postinfo = "ca=8c040eaf-e53c-4290-8de0-35a8f3dafbb5&list=OtherLeadSources&source=EFD&required=list,email,first_name&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_country=&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_date_value_month--1=$cf_date_value_month&cf_date_value_day--1=$cf_date_value_day&cf_date_value_year--1=$cf_date_value_year&cf_date_name--1=date_entered&cf_date_label--1=date entered";
			//$postinfo = "ca=64a62596-9df6-4f5b-8875-2ded34efd1b0&list=2107901781&source=EFD&required=list,email&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_postal_code=$postal_code&cf_text_value--0=$cell_phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_text_value--1=$city&cf_text_name--1=city&cf_text_label--1=city&cf_date_value_month--2=$cf_date_value_month&cf_date_value_day--2=$cf_date_value_day&cf_date_value_year--2=$cf_date_value_year&cf_date_name--2=date_entered&cf_date_label--2=date_entered&cf_text_value--3=$street_address&cf_text_name--3=street_address&cf_text_label--3=street_address&cf_text_value--4=$val4&cf_text_name--4=what_is_the_schedule_a_value_of_your_home&cf_text_label--4=what_is_the_schedule_a_value_of-your-home?&cf_text_value--5=$val5&cf_text_name--5=what_is_your_current_hurricane_deductible&cf_text_label--5=what_is_your_current_hurricane_deductible&cf_text_value--6=$val6&cf_text_name--6=what_year_was_your_roof_last_updated&cf_text_label--6=what_year_was_your_roof_last_updated?&cf_text_value--7=$year_built&cf_text_name--7=year_house_built&cf_text_label--7=Year House Built&cf_text_value--8=$postal_code&cf_text_name--8=zip_code&cf_text_label--8=zip_code";
			$postinfo = "ca=75c83ac3-2a6a-4cb5-9b73-c9a6544b9cb4&list=1618819469&source=EFD&required=list,email&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$cell_phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_text_value--1=$city&cf_text_name--1=city&cf_text_label--1=city&what_is_the_schedule_a_value_of-your-home?_value:input=$coverage_amount&what_is_the_schedule_a_value_of-your-home?_name:input=what_is_the_schedule_a_value_of-your-home&what_is_the_schedule_a_value_of-your-home?_label:input=what_is_the_schedule_a_value_of-your-home?&what_year_was_your_roof_last_updated?_value:input=&what_year_was_your_roof_last_updated?_name:input=what_year_was_your_roof_last_updated&what_year_was_your_roof_last_updated?_label:input=what_year_was_your_roof_last_updated?&cf_text_value--4=$year_built&cf_text_name--4=year_house_built&cf_text_label--4=year_house_built&cf_text_value--5=$postal_code&cf_text_name--5=zip_code&cf_text_label--5=zip_code";
			

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
				$sql_update="update scrapped_new_home_lead_received set ctct_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
			}
			
		
				
		}
		
		
	}
	

?>