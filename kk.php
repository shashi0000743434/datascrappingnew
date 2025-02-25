<?php	
	
	// Script for Posting scrapped information to the forms of  https://entryform.semcat.net/handcinsuranceservices.	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	//$sqlproperty_Query="select * from scrapped_data where brightwaypalmsprings_forms_submit_status IS NULL"; 
	// fetching all records where auto_dialer_status is not updated
	
	echo $sqlproperty_Query="select * from scrapped_data where id=44018"; // fetching all records where auto_dialer_status is not updated		
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {		
				$id=$db_field['id'];
				$consumer_info=str_replace("<none&gt","",$db_field['consumer']);
				$consumer_info_arr=explode("Home:",$consumer_info); // extracting consumer phone number and name. 
				$consumer_name=explode(",",$consumer_info_arr[0]);
				$consumer_first_name=$consumer_name[0];
				$consumer_last_name=$consumer_name[1];
				$phone_number=trim(substr($consumer_info_arr[1], 0, strpos($consumer_info_arr[1], 'Cell')));
				//echo $consumer_info;
				$email_arr=explode("Email:",$consumer_info);
				//print "<pre>"; print_r($email_arr); print "</pre>";die;
				$email=$email_arr[1];
				foreach ($email_arr as $key => $value) {
					
					$value = trim($value);
				    if($key==1 && empty($value)){
						//$email="fmap@gmail.com";
						$email=trim($consumer_first_name).trim($consumer_last_name)."@gmail.com";
					}
				
				}
				
				$property_address=explode(" ",$db_field['property_address']); // for house number.
				
				$address_number=$property_address[0]; // getting the house number
				
				$property_address_for_street=explode(",",$db_field['property_address']); // for street.
				echo "<pre>";
				print_r($property_address_for_street);
				
				$property_street_arr=explode(" ", $property_address_for_street[0]); // making array for street info 
				$street_removed=array_shift($property_street_arr); // removing house number from street info.
				$address_street=implode(" ",$property_street_arr); // making  string for street info.
				
				$zip_arr_val= $property_address_for_street[1];    
				$zip_arr=explode("?", utf8_decode($property_address_for_street[1]));
			    $zip_city_val=$zip_arr[2];	// this string contain both city and zip value.
				echo $address_zip_code= preg_replace('/[a-zA-Z]/', '', $property_address_for_street[2]); // removing all string from alphanumaric string.
				
				
				
				echo $address_city1=preg_replace('/[-0-9]/','',$property_address_for_street[1]);// removing all integers from alphanumaric string.
				// My Custom 
				$address_city=$address_city1." ".$address_zip_code;
				
				
				$sqlproperty_detailsQuery="select * from property_details where property_id=$id and property_key='*Year built'";
				$property_detailsQuery=mysqli_query($db_handle,$sqlproperty_detailsQuery);
				$year_built="";
				
				if(mysqli_num_rows($property_detailsQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_detailsQuery)) {		
					 $year_built=$db_field['property_value'];
					 
					}
				}
				
				
				$sqlproperty_useQuery="select * from property_details where property_id=$id and property_key='*Use'";
				$property_useQuery=mysqli_query($db_handle,$sqlproperty_useQuery);
				$use="";		
				if(mysqli_num_rows($property_useQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_useQuery)) {		
					  
					  $use=$db_field['property_value'];
					 
					}
				}
				
				
				$sqlproperty_Construction_typeQuery="select * from property_details where property_id=$id and property_key='*Construction type'";
				$property_Construction_typeQuery=mysqli_query($db_handle,$sqlproperty_Construction_typeQuery);
				$Construction_type="";
				if(mysqli_num_rows($property_Construction_typeQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_Construction_typeQuery)) {		
					   $Construction_type=$db_field['property_value'];
					}
				}
				
				$sqlproperty_Occupied_byQuery="select * from property_details where property_id=$id and property_key='*Occupancy'";
				$property_Occupied_byQuery=mysqli_query($db_handle,$sqlproperty_Occupied_byQuery);
				$Occupied_by="";
				if(mysqli_num_rows($property_Occupied_byQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_Occupied_byQuery)) {		
					    $Occupied_by=$db_field['property_value'];
					}
				}
				
				
				$sqlproperty_square_feetQuery="select * from property_details where property_id=$id and property_key='*Approximate livable square footage (heated/cooled, not including basement)'";
				$property_square_feetQuery=mysqli_query($db_handle,$sqlproperty_square_feetQuery);
				$square_feet="";
				if(mysqli_num_rows($property_square_feetQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_square_feetQuery)) {		
					    $square_feet=$db_field['property_value'];
					}
				}
				
				
				$sqlproperty_Coverage_amountQuery="select * from property_details where property_id=$id and property_key='*Amount of property coverage desired (excluding land)'";
				$property_Coverage_amountQuery=mysqli_query($db_handle,$sqlproperty_Coverage_amountQuery);
				$Coverage_amount="";
				if(mysqli_num_rows($property_Coverage_amountQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_Coverage_amountQuery)) {		
					    $Coverage_amount=$db_field['property_value'];
					}
				}
				
				$sqlproperty_Coverage_beginsQuery="select * from property_details where property_id=$id and property_key='*When do you need coverage on your property? (Need coverage by date)'";
				$property_Coverage_beginsQuery=mysqli_query($db_handle,$sqlproperty_Coverage_beginsQuery);
				$Coverage_begins="";
				$Coverage_begins_month="";
				$Coverage_begins_day="";
				$Coverage_begins_year="";
				if(mysqli_num_rows($property_Coverage_beginsQuery)>0){
					while ($db_field = mysqli_fetch_assoc($property_Coverage_beginsQuery)) {		
						  $Coverage_begins= explode("/",$Coverage_begins=$db_field['property_value']);
						  $Coverage_begins_month=$Coverage_begins[0];
						  $Coverage_begins_day=$Coverage_begins[1];
						  $Coverage_begins_year=$Coverage_begins[2];
					}
				}
				
				
				// scrapping the handcinsuranceservices form elements.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/handcinsuranceservices');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.

				
				$p = $dom->getElementsByTagName('input')->item(0);
				if ($p->hasAttributes()) {
				  foreach ($p->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;
					if($name =='authenticity_token'){
						echo "<br>3=".$authenticity_token= (trim($value));
					}
				  }
				}
		
				
				// submitting first form using curl.
				$ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices"; 
				//person[given_name]=abc&person[middle_name]=xyz&person[last_name]=pqr&person[suffix]=Jr&lead[email]=abczz@gmail.com&lead[home]=1&lead[routing_group_id]=3235 	
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[given_name]=$consumer_last_name&person[middle_name]=&person[last_name]=$consumer_first_name&person[suffix]=&lead[email]=$email&lead[home]=1&lead[routing_group_id]=FMAP&agent_uid=&from=&commit=Get quote";	
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
				
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/handcinsuranceservices/personal/applicant');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.

				
				$p = $dom->getElementsByTagName('input')->item(0);
				if ($p->hasAttributes()) {
				  foreach ($p->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;
					if($name =='authenticity_token'){
						echo "<br>4=".$authenticity_token= (trim($value));
					}
				  }
				}
				
				// submitting second form using curl.
				$ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/applicant"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[gender]=M&person[residence_state]=FL&person[county_id]=&person[birth_dt_month]=&person[birth_dt_day]=&person[birth_dt_year]=&lead[phone]=$phone_number&nextpage=default&commit=Next";
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
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/handcinsuranceservices/personal/coapplicant');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				echo "<br>Result".$result;
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.
				
				$p = $dom->getElementsByTagName('input')->item(0);
				if ($p->hasAttributes()) {
				  foreach ($p->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;
					if($name =='authenticity_token'){
						echo "<br>5=".$authenticity_token= (trim($value));
					}
				  }
				}

				// for third form api key is different so scrapping api key.
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/coapplicant"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[gender]=M&person[residence_state]=FL&person[county_id]=&person[birth_dt_month]=&person[birth_dt_day]=&person[birth_dt_year]=&lead[phone]=$phone_number&nextpage=default&commit=Next";
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
				
				
				// now submitting third form
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/handcinsuranceservices/personal/dwelling');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				echo "<br>Result".$result;
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.
				
				$p = $dom->getElementsByTagName('input')->item(0);
				if ($p->hasAttributes()) {
				  foreach ($p->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;
					if($name =='authenticity_token'){
						$authenticity_token= (trim($value));
					}
				  }
				}
			
				// submitting forth form
				
				$ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/dwelling"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[address_house_number]=$address_number&lead[address_street_name]=$address_street&lead[address_city]=$address_city&lead[address_postal_code]=$address_zip_code&dwelling[built_dt_year]=$year_built&dwelling[use]=$use&dwelling[construction_type]=$Construction_type&dwelling[occupied_by]=$Occupied_by&dwelling[total_sq_ft]=$square_feet&dwelling[cov]=$Coverage_amount&nextpage=default";	
				
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
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://entryform.semcat.net/handcinsuranceservices/personal/policies');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				echo "<br>Result".$result;
				curl_close($ch);	
				$dom = new DOMDocument();
				$dom->validateOnParse = true;
				$dom->loadHTML($result);
				$xpath = new DOMXpath($dom);
				
				// getting the authenticity_token and api key.
				
				$p = $dom->getElementsByTagName('input')->item(0);
				if ($p->hasAttributes()) {
				  foreach ($p->attributes as $attr) {
					$name = $attr->nodeName;
					$value = $attr->nodeValue;
					if($name =='authenticity_token'){
						$authenticity_token= (trim($value));
					}
				  }
				}
				
				// submitting fifth form
				$ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/policies"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[home_contract_effective_dt_month]=$Coverage_begins_month&lead[home_contract_effective_dt_day]=$Coverage_begins_day&lead[home_contract_effective_dt_year]=$Coverage_begins_year&nextpage=default&commit=Next";	
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
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/handcinsuranceservices/personal/confirm?api_key=$api_key2");
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);
				
				// submitting sixth form
                  
                $ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/confirm?api_key=$api_key2"; 
				$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&api_key=$api_key2&commit=Confirm that you&#x27;re done";	
				curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
				echo "<br>".$result_final = curl_exec($ch);
				curl_close($ch);				
				
				// getting thank you page.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/handcinsuranceservices/personal/finish?api_key=$api_key2");
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch); 
				curl_close($ch);
				
				
				if($result_final==1){
					// update the table.
					$sql_update="update scrapped_data set brightwaypalmsprings_forms_submit_status=1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
				}
				
			
            }
			
		
    }
?>