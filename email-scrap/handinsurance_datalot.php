<?php
    /**
	@@ Script for posting data for email purposes.
	@@
	@@
	**/	
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);	
	echo $sqlproperty_Query="select * from scrapped_datalot_home_insurance_lead where webform_status = 0"; 	
	//$sqlproperty_Query="select * from scrapped_datalot_home_insurance_lead where id = 43"; 		
	
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {
			echo $id				=	$db_field['id']; 
			echo '<br>';
			$name = explode(' ',$db_field['name']);
			echo $first_name		=	$name[0];
			echo '<br>';
			if(count($name)>1){
				echo $last_name		=	$name[1];
			}
			else {
				echo $last_name		=	' ';
			}
			echo '<br>';
			echo $email				=	$db_field['email'];
			echo '<br>';
			echo $phone				=	$db_field['home_phone'];
			echo '<br>';
			
			$address = explode(',',$db_field['address']);
			
	        $addressfound=str_replace(" ","&",$address[0]);
			$addressfound;
			$addresscity = preg_replace('/([A-Z])/', '/$1', $addressfound);
			$addressfound1=str_replace("&/"," ",$addresscity);
			$addresscityarray=explode('/',$addressfound1);
			
			$addressarray=explode(' ',$addresscityarray[0]);
			print_r($addressarray);
			echo "<br>Number==".$address_number =$addressarray[0];
			echo "<br>street==".$address_street=$addressarray[1]." ".$addressarray[2]." ".$addressarray[3]." ".$addressarray[4];
			echo "<br>city==".$address_city=$addresscityarray[1];
			
  
  
			if(count($address)==2) {
				$adderss_det = explode(' ',trim($address[1]));
				$street_address	=	$address[0];
			}
			if(count($address)==3) {
				$adderss_det = explode(' ',trim($address[2]));
				$street_address	=	$address[0].', '.$address[1];
			}
			echo '<br>';
			echo "City====".$city				=	$adderss_det[0];
			echo '<br>';
			echo "state====".$state				=	$adderss_det[0];
			echo '<br>';
			
			if(count($adderss_det)>1){
				echo $address_zip_code	=	$adderss_det[1];
			}
			else {
				echo $address_zip_code	=	' ';
			}
			
			echo '<br>';
			echo $year_built		=	$db_field['year_built'];
			echo '<br>';
			echo $apprx_square_footage		=	$db_field['square_footage'];
			echo '<br>';
			
			$occupancy_status	=	$db_field['property_occupancy'];
			if(trim($occupancy_status) == 'Primary' || trim($occupancy_status) == 'Primarya'){
				echo $property_usage = 'Primary Home';
			}
			elseif(trim($occupancy_status) == 'Secondary'){
				echo $property_usage = 'Secondary Home';
			}
			else {
				echo $property_usage = '';
			}
			echo '<br>';
			
			$property_type	=	$db_field['property_type'];
			if(trim($property_type) == 'Single_family' || trim($property_type) == 'Apartment'){
				echo $structure_type = 'Single Family';
			}
			elseif(trim($property_type) == 'Condo' || trim($property_type) == 'Multi_family') {
				echo $structure_type = 'Condominium';
			}
			elseif(trim($property_type) == 'Townhome' ){
				echo $structure_type = 'Townhouse (Center Unit)';
			}
			elseif(trim($property_type) == 'Mobile_Home' ){
				echo $structure_type = 'Mobile Home';
			}
			else {
				echo $structure_type = '';
			}
			//echo '<br>';
			$use='';
			$Occupied_by='';
			$Construction_type ='';
			$square_feet ='';
			$Coverage_amount ='';
			$Coverage_begins_month='';
			$Coverage_begins_day='';
			$Coverage_begins_year ='';
			/*$construction_type 	=	$db_field['construction_type'];
			if($construction_type  == 'Stucco' ){
				echo $construction_type = 'Concrete Block';
			}
			elseif($construction_type  == 'Wood Frame' ){
				echo $construction_type = 'Frame';
			}
			else {
				echo $construction_type = 'Mixed (Block And Frame)';
			}
			echo '<br>';*/

			$deductible 	=	$db_field['desired_policy_deductible'];
			if($deductible  == '2500' ){
				echo $deductible = '$2,500';
			}
			elseif($deductible  == '2000' ){
				echo $deductible = '$2,500';
			}
			elseif($deductible  == '1000' ){
				echo $deductible = '$1,000';
			}
			elseif($deductible  == '500' ){
				echo $deductible = '$500';
			}
			elseif($deductible  == '250' ){
				echo $deductible = '$500';
			}
			elseif($deductible  == '100' ){
				echo $deductible = '$500';
			}
			else {
				echo $deductible = '';
			}
			
			echo '<br>';
			echo $estimated_replacement_cost =	$db_field['replacement_cost'];
			echo '<hr>';
			
			
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

				$tags_authenticity_token = $xpath->query('//input[@name="authenticity_token"]');

				foreach ($tags_authenticity_token as $tag_at) {
				  
				  $authenticity_token= (trim($tag_at->getAttribute('value')));echo "<br/>";
				}

				
				$tags_api_key = $xpath->query('//input[@name="api_key"]');
				foreach ($tags_api_key as $tag_ak) {
				   
				   $api_key= (trim($tag_ak->getAttribute('value')));echo "<br/>";
				}
				
		
				
				// submitting first form using curl.
				$ch = curl_init();	
				$url="https://entryform.semcat.net/handcinsuranceservices"; 
				//person[given_name]=abc&person[middle_name]=xyz&person[last_name]=pqr&person[suffix]=Jr&lead[email]=abczz@gmail.com&lead[home]=1&lead[routing_group_id]=3235 	
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[given_name]=$first_name&person[middle_name]=&person[last_name]=$last_name&person[suffix]=&lead[email]=$email&lead[home]=1&lead[routing_group_id]=Datalot&agent_uid=&api_key=$api_key&from=&commit=Get quote";	
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
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/applicant"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&person[gender]=M&person[residence_state]=FL&person[county_id]=&person[birth_dt_month]=&person[birth_dt_day]=&person[birth_dt_year]=&lead[phone]=$phone&nextpage=default&api_key=$api_key&commit=Next";	
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
				curl_setopt($ch, CURLOPT_URL, "https://entryform.semcat.net/handcinsuranceservices/personal/coapplicant?api_key=$api_key");
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
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/to_dwelling?api_key=$api_key2"; 
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
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/dwelling"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[address_house_number]=$address_number&lead[address_street_name]=$address_street&lead[address_city]=$address_city&lead[address_postal_code]=$address_zip_code&dwelling[built_dt_year]=$year_built&dwelling[use]=$use&dwelling[construction_type]=$Construction_type&dwelling[occupied_by]=$Occupied_by&dwelling[total_sq_ft]=$square_feet&dwelling[cov]=$Coverage_amount&nextpage=default&api_key=$api_key2";	
				
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
				$url="https://entryform.semcat.net/handcinsuranceservices/personal/policies"; 
				echo "<br>".$postinfo = "utf8=&#x2713;&authenticity_token=$authenticity_token&lead[home_contract_effective_dt_month]=$Coverage_begins_month&lead[home_contract_effective_dt_day]=$Coverage_begins_day&lead[home_contract_effective_dt_year]=$Coverage_begins_year&nextpage=default&commit=Next&api_key=$api_key2";	
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
				$result_final = curl_exec($ch);
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
				echo $sql_update="update scrapped_datalot_home_insurance_lead set webform_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
			}
			
		
		}
		
		
	}
	

?>