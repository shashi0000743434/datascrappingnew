<?php	
	
	// Script for Posting scrapped information to the forms of  https://entryform.semcat.net/brightwaypalmsprings.	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	$sqlproperty_Query="select * from scrapped_emails_1 where webform_status = 0"; // fetching all records where auto_dialer_status is not updated
	//$sqlproperty_Query="select * from scrapped_emails_1 where id = 1"; // fetching all records where auto_dialer_status is not updated		
	$query = mysqli_query($db_handle,$sqlproperty_Query);
	
	$lenght = mysqli_num_rows($query);
	
	if(mysqli_num_rows($query)>0){
		$i=1;
		while ($db_field = mysqli_fetch_assoc($query)) {	
				//echo '<pre>';  print_r($db_field);
				
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
				$lead_insurance = 'lead['.strtolower($insurance_type).']';
				
				// scrapping the brightwaypalmsprings form elements.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/brightwaypalmsprings');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.

				$tags_authenticity_token = $xpath->query('//input[@name="authenticity_token"]');

				foreach ($tags_authenticity_token as $tag_at) {
				  
				  echo $authenticity_token= (trim($tag_at->getAttribute('value')));echo "<br/>";
				}

				
				$tags_api_key = $xpath->query('//input[@name="api_key"]');
				foreach ($tags_api_key as $tag_ak) {
				   
				   echo $api_key= (trim($tag_ak->getAttribute('value')));echo "<br/>";
				}
				
				
				// submitting first form using curl.
				$ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings"; 
				//person[given_name]=abc&person[middle_name]=xyz&person[last_name]=pqr&person[suffix]=Jr&lead[email]=abczz@gmail.com&lead[home]=1&lead[routing_group_id]=3235 	
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[given_name]=$first_name&person[middle_name]=&person[last_name]=$last_name&person[suffix]=&lead[email]=$email&$lead_insurance=1&lead[routing_group_id]=FMAP&agent_uid=&api_key=$api_key&from=&commit=Get quote";	
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
				
				
				// submitting second form using curl.
				$ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings/personal/applicant"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[gender]=M&person[residence_state]=FL&person[county_id]=&person[birth_dt_month]=&person[birth_dt_day]=&person[birth_dt_year]=&lead[phone]=$phone&nextpage=default&api_key=$api_key&commit=Next";	
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
				
				
				
				// for third form api key is different so scrapping api key.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/brightwaypalmsprings/personal/coapplicant?api_key=$api_key");
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				$tags_api_key = $xpath->query('//input[@name="api_key"]');
				foreach ($tags_api_key as $tag_ak) {
				   $api_key2= (trim($tag_ak->getAttribute('value')));echo "<br/>";
				}
				
				// now submitting third form
				$ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings/personal/to_dwelling?api_key=$api_key2"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token";	
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
			
				
				// submitting forth form
				
				$ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings/personal/dwelling"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[address_house_number]=&lead[address_street_name]=$street_address&lead[address_city]=$city&lead[address_postal_code]=$postal_code&dwelling[built_dt_year]=&dwelling[use]=&dwelling[construction_type]=&dwelling[occupied_by]=&dwelling[total_sq_ft]=&dwelling[cov]=&nextpage=default&api_key=$api_key2";	
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
				
				
				// submitting fifth form
				$ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings/personal/policies"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[home_contract_effective_dt_month]=&lead[home_contract_effective_dt_day]=&lead[home_contract_effective_dt_year]=&nextpage=default&commit=Next&api_key=$api_key2";	
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
				
				
				// getting sixth form
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/brightwaypalmsprings/personal/confirm?api_key=$api_key2");
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);
				
				// submitting sixth form
                  
                $ch = curl_init();	
				$url="https://entryform.semcat.net/brightwaypalmsprings/personal/confirm?api_key=$api_key2"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&api_key=$api_key2&commit=Confirm that you&#x27;re done";	
				curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
				echo $result_final = curl_exec($ch);
				curl_close($ch);		

				
				// getting thank you page.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/brightwaypalmsprings/personal/finish?api_key=$api_key2");
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result1 = curl_exec($ch); 
				curl_close($ch);
				echo '<pre>'; print_r($result1); echo '</pre>';
			
			
				if($result_final==1){
					// update the table.
					$sql_update="update scrapped_emails_1 set webform_status = 1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
				}
				
				$i++;
					
            }
		
    }
?>