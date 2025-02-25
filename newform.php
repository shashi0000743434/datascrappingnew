<?php	
// submitting first form using curl.https://www.agentinsure.com/compare/auto-insurance-home-insurance/aandcin/quote.aspx
				$ch = curl_init();	
				$url="https://www.agentinsure.com/compare/auto-insurance-home-insurance/aandcin/quote.aspx"; 
				//person[given_name]=abc&person[middle_name]=xyz&person[last_name]=pqr&person[suffix]=Jr&lead[email]=abczz@gmail.com&lead[home]=1&lead[routing_group_id]=3235 	
echo $postinfo = "utf8=&#x2713;&pagename=LeadGenerator&Applicant_IsCommercial=yes&Applicant_BusinessName=kk&Applicant_FirstName=shashi&Applicant_LastName=test&Applicant_Email=atulsood.sood@gmail.com&Applicant_HomePhone=111&Applicant_HomePhone_1=234&Applicant_HomePhone_2=456&Applicant_AddressLine1=chg&Applicant_Unit=123&Applicant_City=chd&Applicant_State=AL&Applicant_Zip=36925&SubmitButton=Continue";	
				curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
				$result = curl_exec($ch);
				echo "sdsd".$responseInfo = curl_getinfo($ch);
				curl_close($ch);
				
				echo "<pre>";
				print_r($result);
				echo "</pre>";
				
				
				echo "<pre>";
				print_r($responseInfo);
				echo "</pre>";
$httpResponseCode = $responseInfo['http_code']
				
				
				//https://www.agentinsure.com/compare/auto-insurance-home-insurance/aandcin/quote.aspx?pagename=LeadGenerator&Applicant_IsCommercial=yes&Applicant_BusinessName=kk&Applicant_FirstName=Atul&Applicant_LastName=test&Applicant_Email=atulsood.sood@gmail.com&Applicant_HomePhone=111&Applicant_HomePhone_1=234&Applicant_HomePhone_2=456&Applicant_AddressLine1=chg&Applicant_Unit=123&Applicant_City=chd&Applicant_State=AK&Applicant_Zip=234567
				
				
				
				
				
				
				
			
            
			
		
    
?>