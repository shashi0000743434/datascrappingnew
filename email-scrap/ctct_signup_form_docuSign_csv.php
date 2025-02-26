<?php
    /**
	@@ Script for posting data for email purposes.
	@@
	@@
	**/	
	?>
	<script> var _ctct_m = "cb8d42523b0fe0f838f33766775a6343"; </script>
<script id="signupScript" src="//static.ctctcdn.com/js/signup-form-widget/current/signup-form-widget.min.js" async defer></script>
<div class="ctct-inline-form" data-form-id="55e56113-bd56-45b0-bb9c-2699a159e2d8"></div>
	<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);	
	//$sqlproperty_Query="select * from scrapped_docusign_csv where ctct_status = 0"; 	
	$sqlproperty_Query="select * from scrapped_docusign_csv where id = 1"; 		
	
	/*$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {		
			$id					=	$db_field['id']; 
			
			$name = explode(' ',$db_field['recipient_name']);
			
			$first_name			=	$name[0];
			if(count($name)>1){
				$last_name			=	$name[1];
			}
			else {
				$last_name			=	' ';
			}
				
			$email				=	$db_field['recipient_email'];
			$phone				=	$db_field['phone'];
			

			$street_address		=	'';
			$city				=	'';
			$state				=	'';
			$postal_code		=	'';
			
			
				
			
			$cf_date_value_month=	date("m");
			$cf_date_value_day	=	date("d");
			$cf_date_value_year	=	date("Y");
			
			$val4 = $val5 = $val6 = ' ';
			
			// submitting data to form.
			$ch = curl_init();	
			$url="https://visitor2.constantcontact.com/api/signup"; 
	        //$postinfo = "ca=8c040eaf-e53c-4290-8de0-35a8f3dafbb5&list=OtherLeadSources&source=EFD&required=list,email,first_name&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_country=&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_date_value_month--1=$cf_date_value_month&cf_date_value_day--1=$cf_date_value_day&cf_date_value_year--1=$cf_date_value_year&cf_date_name--1=date_entered&cf_date_label--1=date entered";
			//$postinfo = "ca=64a62596-9df6-4f5b-8875-2ded34efd1b0&list=2107901781&source=EFD&required=list,email&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_postal_code=$postal_code&cf_text_value--0=$cell_phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_text_value--1=$city&cf_text_name--1=city&cf_text_label--1=city&cf_date_value_month--2=$cf_date_value_month&cf_date_value_day--2=$cf_date_value_day&cf_date_value_year--2=$cf_date_value_year&cf_date_name--2=date_entered&cf_date_label--2=date_entered&cf_text_value--3=$street_address&cf_text_name--3=street_address&cf_text_label--3=street_address&cf_text_value--4=$val4&cf_text_name--4=what_is_the_schedule_a_value_of_your_home&cf_text_label--4=what_is_the_schedule_a_value_of-your-home?&cf_text_value--5=$val5&cf_text_name--5=what_is_your_current_hurricane_deductible&cf_text_label--5=what_is_your_current_hurricane_deductible&cf_text_value--6=$val6&cf_text_name--6=what_year_was_your_roof_last_updated&cf_text_label--6=what_year_was_your_roof_last_updated?&cf_text_value--7=$year_built&cf_text_name--7=year_house_built&cf_text_label--7=Year House Built&cf_text_value--8=$postal_code&cf_text_name--8=zip_code&cf_text_label--8=zip_code";
			//$postinfo = "ca=75c83ac3-2a6a-4cb5-9b73-c9a6544b9cb4&list=1618819469&source=EFD&required=list,email&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code&cf_text_value--0=$cell_phone&cf_text_name--0=cell_phone&cf_text_label--0=Cell Phone&cf_text_value--1=$city&cf_text_name--1=city&cf_text_label--1=city&what_is_the_schedule_a_value_of-your-home?_value:input=$coverage_amount&what_is_the_schedule_a_value_of-your-home?_name:input=what_is_the_schedule_a_value_of-your-home&what_is_the_schedule_a_value_of-your-home?_label:input=what_is_the_schedule_a_value_of-your-home?&what_year_was_your_roof_last_updated?_value:input=&what_year_was_your_roof_last_updated?_name:input=what_year_was_your_roof_last_updated&what_year_was_your_roof_last_updated?_label:input=what_year_was_your_roof_last_updated?&cf_text_value--4=$year_built&cf_text_name--4=year_house_built&cf_text_label--4=year_house_built&cf_text_value--5=$postal_code&cf_text_name--5=zip_code&cf_text_label--5=zip_code";
			$postinfo = "ca=55e56113-bd56-45b0-bb9c-2699a159e2d8&list=1671667451&source=EFD&required=list,email,first_name&url=&email=$email&first_name=$first_name&last_name=$last_name&phone=$phone&address_street=$street_address&address_city=$city&address_state=$state&address_postal_code=$postal_code";
			echo '<br>';

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
				$sql_update="update scrapped_docusign_csv set ctct_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
			}
			
		
				
		}
		
		
	}*/
	
	echo '<br>';
	
	$data = '{"group_id":"cb8d42523b0fe0f838f33766775a6343","contact":{"email_address":"gh@vgf.gfg","list_memberships"
:["2f0391c0-b9e2-11e7-9865-d4ae528eb986"],"first_name":"gfgf","last_name":"gf","phone_number":"54543543"
,"custom_fields":[{"custom_field_id":"491f0950-080c-11e7-b0ba-d4ae527548e1","value":"12/12/2001"}],"source_name"
:"Inline Form"},"token":"03AHhf_50kMLF06X_svFZHQCvRxzxcozWEgO03ad_WAXGYpQQC4r5vV9c0Suxhuh_NGRLl2BE5FfKy--1f5IbprCX1
uwF6YnDDDUAdCtt2WAarkDHGrVZ3PmBcBLfY1lhqh0hut7SKzTt2A1xFh_eEZFVbxtw7Wdw0fSUDElFFE8v9m0C5TKYmBvaTP6ct
pX2pFR40onNMsi6cXi7nd4StFgDULDUUqv9XFlN1khhRo5RTzIi4oD0kxq76fOWNl-YryVK3OSyde6i6wdYxExxQzcicglGw1MrV
f1odadV5-ukvdoDLXtKtBg8DbhsZ2lqx4a58XEjaRl-QpGF4ge7qvFsud8DSENu0MgISxqrqvC7hi3O0NDfYpgLb3x1nZOtPqJbZ
kXEMtNEBDquUSHowKFeszrlKpEp-k-tYQF5fK7Pc9Wu8Gg-PzXZ5b60RLq0Cwle29GOXMoQgDr5rJFgKjNEnwYo-sFs4I-ls5Vb2
CJF8oixoI_bj2oC7w9JS_PHekk3wNTYiHOrYMpB9OI8jKJZiPiu95OIISyxPYAsqbGi-ttTTejelPxJ8ya70LCPEHOPW3gKh5Yev
utMC9lu4ckuhDNXmVtbiu5le_2-CNilBlzOPQhJcKXRmq4kWY1M6L0r6d97J6k25JS_iP7QLJLJP90LA4pNSotRuQS_M6iTgxYCW
xUY0Nv9HVKEzdvt4B3eDzDWqqzvjBS14ZKpRtbrfMmxoQ5tXSOr_VUYso3fT0cm7jgO3N_Ty_IdOxcOPrR8JUm0Dwmm2A1OjbNtE
7gKcCkHoz3gY3_desXMX-eNG3f3s2ScBcqp2fuaWpQjm_2wJi59qIojLjLsI_sogh353eh25YuOtldZbmm72v5vDmN1rMGzcJ1Eu
GTNlmhT-wxXmHRS_lA4cyoonzUZ-Ku3hT88tv3aYXbGpanUt0ezK7x7e_O-UHdTNO-QVZib6v7jaLQCMqhfO_ewKXO5m_W40i9am
E47GMCTjqI1ySybl2BsMwy-ltegDvw1Foq8HA1ywY7jF17pdhH6YC4cxWuKx9-SxofJipM2KBaFuMHEyOVflRei90D3v10G5wjt1
B3rGeqnM65B8Ikiw0zeR0Jfl7PMoiiOKelHX4OBXPjcW2QlllDd-FUbyC4J_nKRb8dK6b7nnhOY6HGZTuTVts-g7wncf7OnDE_HAWHOj4qsSvDbV5YT_NWiOU4JLXqUfHTApj5z4SQR9yzqED4-aN-3mnzktn0IjO_gmgNWw9tQZ4zU3BUJAmjA","recaptcha_key":"6LfHrSkUAAAAAPnKk5cT6JuKlKPzbwyTYuO8--Vr","is_test":false}';                                                                    
	$data_string = json_encode($data);                                                                                   
																														 
	$ch = curl_init('https://visitor2.constantcontact.com/api/v1/signup_forms/55e56113-bd56-45b0-bb9c-2699a159e2d8');                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data))                                                                       
	);                                                                                                                   
																														 
	$result = curl_exec($ch);
	
	print_r($result);

?>