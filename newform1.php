<?php
echo "sds";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.agentinsure.com/compare/auto-insurance-home-insurance/aandcin/quote.aspx");
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('pagename' => 'LeadGenerator','Applicant_IsCommercial' => 'yes','Applicant_BusinessName' => 'kk','Applicant_FirstName' => 'shashi','Applicant_LastName' => 'test','Applicant_Email' => 'atulsood.sood@gmail.com','Applicant_HomePhone' => '111','Applicant_HomePhone_1' => '234','Applicant_HomePhone_2' => '4566','Applicant_AddressLine1' => 'chg','Applicant_Unit' => '123','Applicant_City' => 'chd','Applicant_State' => 'AL','Applicant_Zip' => '36925')));
 // Receive server response ... 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
 $server_output = curl_exec($ch); 
 echo "<pre>";
 print_r($server_output);
 curl_close($ch);
 
 echo "kkk";
 ?>
 
 