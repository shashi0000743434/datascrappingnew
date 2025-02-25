<?php
 $code = file_get_contents ("https://www.chamberofcommerce.com/united-states/maryland/silver-spring/water-damage-restoration-service/2023321276-midatlantic-mold-and-water-damage");


//$code = str_replace(">", "<>", $code);

$splitCode = explode('<div class="card-body">', $code);
//echo "Here";
//echo "<pre>";
//print_r($splitCode[3]);
//echo "</pre>";
$splitCode1 = explode('<ul class="ul-lh-lg list-unstyled fw-bold text-dark">', $splitCode[3]);
//echo "Here";
//echo "<pre>";
//print_r($splitCode1);
//echo "</pre>";
$splitCode2 = explode('<li>', $splitCode1[1]);
//echo "<pre>";
//print_r($splitCode2);
//echo "</pre>";
//echo $splitCode2[2];
$splitCode4 = explode('<span class="__cf_email__" data-cfemail="', $splitCode2[2]);
echo $variable_email= explode('"', $splitCode4[1]);
echo "<pre>";
print_r($variable_email);
echo "</pre>";
echo "<br>here".$email=cfDecodeEmail($variable_email[0]);
// Find the first occurance of the opening tag of the quotes: 
$openingTag = array_search('<span class="text-muted-darker d-sm-block phone-align">', $code, true);

// Find the first occurance of the closing tag of the quotes 
$closingTag = array_search('/span', $splitCode, true);

// Now, find the text in between the tags 
$i = $openingTag;
$total = "";
while ($i < $closingTag) {
	$total = $total . $splitCode[$i];
	$i = $i + 1;
}
$final = substr($total, 37);
echo $final; 

function cfDecodeEmail($encodedString){
  $k = hexdec(substr($encodedString,0,2));
  for($i=2,$email='';$i<strlen($encodedString)-1;$i+=2){
    $email.=chr(hexdec(substr($encodedString,$i,2))^$k);
  }
  return $email;
}

//echo cfDecodeEmail('13617c71717a767f7c657653707b727d777f76617776607a747d74617c66633d707c7e');
/*$url="https://www.chamberofcommerce.com/business-directory/georgia/statham/water-damage-restoration-service/2023394694-chandler-design-group"; 
$postinfo = "searchId=$search_id&_event_=runSearch";	
curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
echo $html = curl_exec($ch);
*/
 
?>
