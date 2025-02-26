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
	//$sqlproperty_Query="select * from scrapped_emails_1"; 		
	
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
				
			
			$cf_date_value_month=	date("m");
			$cf_date_value_day	=	date("d");
			$cf_date_value_year	=	date("Y");
			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://visitor.r20.constantcontact.com/manage/optin?v=001fF7rnIqcTi_SG89YYbyxWTqb74KJ671opSHQGdT3yj0ysR6DeRqhfdy4RzHf8LKS1uc-D2dd6Z3yOU9Q1SlRjB3el9lsSja8ef0IbNWQt-XYNul8pZ77bSl8wkQzpUlSrVhUeNEpShWXl3Zwj6UF8CbzUF34bPA2m991bPuKLjqiGRwx7QLYuBqwBz_fagjFaiyzKjly4i0AAXBcaFXD_50oT1yQJH-oOVGS8OyUAz8UjCCDCqsPcDryddegwPVWwIWhM-0BmfpE0CQH9ukWTkVqiXr1xA2wasgsSQtceuPhLsOiSHnq9btVATQjQ-D564Oqg4E_9gw7QdgaFZEnwY0oo7Tj10HXItlyp1B2EAna0n01DXc8y9I_FkNmXiGDT3BmSpatGb_Al8PK8K-yQVsTy2WBNj_L8UpA3XVz8OU%3D");
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); 
			curl_close($ch);
			$result = explode('captcha.control" type="hidden" value="',$result);
			$result1 = explode('"',$result[1]);
			$captcha = $result1[0];
			
			// submitting data to form.
			$ch = curl_init();	
			//$url="https://visitor.r20.constantcontact.com/manage/optin?"; 
			$url="https://visitor.r20.constantcontact.com/manage/optin?v=001fF7rnIqcTi_SG89YYbyxWTqb74KJ671opSHQGdT3yj0ysR6DeRqhfdy4RzHf8LKS1uc-D2dd6Z3yOU9Q1SlRjB3el9lsSja8ef0IbNWQt-XYNul8pZ77bSl8wkQzpUlSrVhUeNEpShWXl3Zwj6UF8CbzUF34bPA2m991bPuKLjqiGRwx7QLYuBqwBz_fagjFaiyzKjly4i0AAXBcaFXD_50oT1yQJH-oOVGS8OyUAz8UjCCDCqsPcDryddegwPVWwIWhM-0BmfpE0CQH9ukWTkVqiXr1xA2wasgsSQtceuPhLsOiSHnq9btVATQjQ-D564Oqg4E_9gw7QdgaFZEnwY0oo7Tj10HXItlyp1B2EAna0n01DXc8y9I_FkNmXiGDT3BmSpatGb_Al8PK8K-yQVsTy2WBNj_L8UpA3XVz8OU%3D"; 
	        $captchaurl = 'http://visitor.r20.constantcontact.com/manage/optin&captcha.remoteAddr=122.180.20.83';
			//$postinfo = "&subscriberProfile.visitorProps[0].value=$email&subscriberProfile.visitorProps[0].id=80&subscriberProfile.visitorProps[0].name=Email Address&subscriberProfile.visitorProps[0].maxLen=50&subscriberProfile.visitorProps[0].required=true&subscriberProfile.visitorProps[0].inputType=input&subscriberProfile.visitorProps[0].refDataKey=&subscriberProfile.visitorProps[0].customPropID=&subscriberProfile.visitorProps[1].value=$first_name&subscriberProfile.visitorProps[2].value=$last_name&subscriberProfile.visitorProps[3].value=$phone&subscriberProfile.visitorProps[4].value=$street_address&subscriberProfile.visitorProps[5].value=$city&subscriberProfile.visitorProps[6].value=$state&subscriberProfile.visitorProps[7].value=$postal_code&subscriberProfile.visitorProps[8].value=&subscriberProfile.visitorProps[9].value=&subscriberProfile.visitorProps[10].value=&subscriberProfile.visitorProps[11].value=&subscriberProfile.visitorProps[12].value=&subscriberProfile.visitorProps[13].value=&subscriberProfile.visitorProps[14].value=&subscriberProfile.visitorProps[15].value=&subscriberProfile.visitorProps[16].value=&subscriberProfile.visitorProps[17].value=&subscriberProfile.visitorProps[18].value=&subscriberProfile.visitorProps[19].value=&subscriberProfile.visitorProps[20].value=&_save=Sign Up";	
			//$postinfo = "&subscriberProfile.visitorProps[0].value=$email&subscriberProfile.visitorProps[0].id=80&subscriberProfile.visitorProps[0].name=Email Address&subscriberProfile.visitorProps[0].maxLen=50&subscriberProfile.visitorProps[0].required=true&subscriberProfile.visitorProps[0].inputType=input&subscriberProfile.visitorProps[0].refDataKey=&subscriberProfile.visitorProps[0].customPropID=&subscriberProfile.visitorProps[1].value=$first_name&subscriberProfile.visitorProps[2].value=$last_name&subscriberProfile.visitorProps[3].value=$phone&subscriberProfile.visitorProps[4].value=$street_address&subscriberProfile.visitorProps[5].value=$city&subscriberProfile.visitorProps[6].value=$state&subscriberProfile.visitorProps[7].value=$postal_code&subscriberProfile.visitorProps[8].value=&subscriberProfile.visitorProps[9].value=&subscriberProfile.visitorProps[10].value=&subscriberProfile.visitorProps[11].value=&subscriberProfile.visitorProps[12].value=&subscriberProfile.visitorProps[13].value=&subscriberProfile.visitorProps[14].value=&subscriberProfile.visitorProps[15].value=&subscriberProfile.visitorProps[16].value=&subscriberProfile.visitorProps[17].value=&subscriberProfile.visitorProps[18].value=&subscriberProfile.visitorProps[19].value=&subscriberProfile.visitorProps[20].value=&_save=Sign Up&captcha.url=http://visitor.r20.constantcontact.com/manage/optin&captcha.remoteAddr=122.180.20.83&captcha.challenge=&captcha.response=&captcha.key=6LcB5AIAAAAAANx7J2ADgUFxwd_zllfY4DxX81c5&captcha.control=$captcha&subscriptions.interestCategories[0].id=1162386597&subscriptions.interestCategories[0].name=Cat4WebsiteLeads&subscriptions.interestCategories[0].selected=true&_subscriptions.interestCategories[0].selected=on&subscriberProfile.visitorParams=001fF7rnIqcTi_SG89YYbyxWTqb74KJ671opSHQGdT3yj0ysR6DeRqhfdy4RzHf8LKS1uc-D2dd6Z3yOU9Q1SlRjB3el9lsSja8ef0IbNWQt-XYNul8pZ77bSl8wkQzpUlSrVhUeNEpShWXl3Zwj6UF8CbzUF34bPA2m991bPuKLjqiGRwx7QLYuBqwBz_fagjFaiyzKjly4i0AAXBcaFXD_50oT1yQJH-oOVGS8OyUAz8UjCCDCqsPcDryddegwPVWwIWhM-0BmfpE0CQH9ukWTkVqiXr1xA2wasgsSQtceuPhLsOiSHnq9btVATQjQ-D564Oqg4E_9gw7QdgaFZEnwY0oo7Tj10HXItlyp1B2EAna0n01DXc8y9I_FkNmXiGDT3BmSpatGb_Al8PK8K-yQVsTy2WBNj_L8UpA3XVz8OU=&subscriptions.interestCategoriesSize=1&subscriberProfile.visitorPropertiesSize=21";	
			$postinfo = "subscriberProfile.visitorProps[0].value=$email&subscriberProfile.visitorProps[0].id=80&subscriberProfile.visitorProps[0].name=Email Address&subscriberProfile.visitorProps[0].maxLen=50&subscriberProfile.visitorProps[0].required=true&subscriberProfile.visitorProps[0].inputType=input&subscriberProfile.visitorProps[0].refDataKey=&subscriberProfile.visitorProps[0].customPropID=&subscriberProfile.visitorProps[1].value=$first_name&subscriberProfile.visitorProps[1].id=100&subscriberProfile.visitorProps[1].name=First Name&subscriberProfile.visitorProps[1].maxLen=50&subscriberProfile.visitorProps[1].required=false&subscriberProfile.visitorProps[1].inputType=input&subscriberProfile.visitorProps[1].refDataKey=&subscriberProfile.visitorProps[1].customPropID=&subscriberProfile.visitorProps[2].value=$last_name&subscriberProfile.visitorProps[2].id=300&subscriberProfile.visitorProps[2].name=Last  Name&subscriberProfile.visitorProps[2].maxLen=50&subscriberProfile.visitorProps[2].required=false&subscriberProfile.visitorProps[2].inputType=input&subscriberProfile.visitorProps[2].refDataKey=&subscriberProfile.visitorProps[2].customPropID=&subscriberProfile.visitorProps[3].value=$phone&subscriberProfile.visitorProps[3].id=800&subscriberProfile.visitorProps[3].name=Phone Number&subscriberProfile.visitorProps[3].maxLen=50&subscriberProfile.visitorProps[3].required=false&subscriberProfile.visitorProps[3].inputType=input&subscriberProfile.visitorProps[3].refDataKey=&subscriberProfile.visitorProps[3].customPropID=&subscriberProfile.visitorProps[4].value=$street_address&subscriberProfile.visitorProps[4].id=900&subscriberProfile.visitorProps[4].name=Street Address&subscriberProfile.visitorProps[4].maxLen=50&subscriberProfile.visitorProps[4].required=false&subscriberProfile.visitorProps[4].inputType=input&subscriberProfile.visitorProps[4].refDataKey=&subscriberProfile.visitorProps[4].customPropID=&subscriberProfile.visitorProps[5].value=$city&subscriberProfile.visitorProps[5].id=1200&subscriberProfile.visitorProps[5].name=City&subscriberProfile.visitorProps[5].maxLen=50&subscriberProfile.visitorProps[5].required=false&subscriberProfile.visitorProps[5].inputType=input&subscriberProfile.visitorProps[5].refDataKey=&subscriberProfile.visitorProps[5].customPropID=&subscriberProfile.visitorProps[6].value=$state&subscriberProfile.visitorProps[6].id=1300&subscriberProfile.visitorProps[6].name=State/Province&subscriberProfile.visitorProps[6].maxLen=50&subscriberProfile.visitorProps[6].required=false&subscriberProfile.visitorProps[6].inputType=select&subscriberProfile.visitorProps[6].refDataKey=states&subscriberProfile.visitorProps[6].customPropID=&subscriberProfile.visitorProps[7].value=$postal_code&subscriberProfile.visitorProps[7].id=1700&subscriberProfile.visitorProps[7].name=Zip Code&subscriberProfile.visitorProps[7].maxLen=25&subscriberProfile.visitorProps[7].required=false&subscriberProfile.visitorProps[7].inputType=input&subscriberProfile.visitorProps[7].refDataKey=&subscriberProfile.visitorProps[7].customPropID=&subscriberProfile.visitorProps[8].value=&subscriberProfile.visitorProps[8].id=1500&subscriberProfile.visitorProps[8].name=Country&subscriberProfile.visitorProps[8].maxLen=25&subscriberProfile.visitorProps[8].required=false&subscriberProfile.visitorProps[8].inputType=select&subscriberProfile.visitorProps[8].refDataKey=countries&subscriberProfile.visitorProps[8].customPropID=&subscriberProfile.visitorProps[9].value=&subscriberProfile.visitorProps[9].id=2100&subscriberProfile.visitorProps[9].name=Cell Phone&subscriberProfile.visitorProps[9].maxLen=50&subscriberProfile.visitorProps[9].required=false&subscriberProfile.visitorProps[9].inputType=input&subscriberProfile.visitorProps[9].refDataKey=&subscriberProfile.visitorProps[9].customPropID=cell_phone&subscriberProfile.visitorProps[10].value=&subscriberProfile.visitorProps[10].id=2100&subscriberProfile.visitorProps[10].name=city&subscriberProfile.visitorProps[10].maxLen=50&subscriberProfile.visitorProps[10].required=false&subscriberProfile.visitorProps[10].inputType=input&subscriberProfile.visitorProps[10].refDataKey=&subscriberProfile.visitorProps[10].customPropID=city&subscriberProfile.visitorProps[11].value=&subscriberProfile.visitorProps[11].id=2100&subscriberProfile.visitorProps[11].name=date entered&subscriberProfile.visitorProps[11].maxLen=50&subscriberProfile.visitorProps[11].required=false&subscriberProfile.visitorProps[11].inputType=input&subscriberProfile.visitorProps[11].refDataKey=&subscriberProfile.visitorProps[11].customPropID=date_entered&subscriberProfile.visitorProps[12].value=&subscriberProfile.visitorProps[12].id=2100&subscriberProfile.visitorProps[12].name=full_name&subscriberProfile.visitorProps[12].maxLen=50&subscriberProfile.visitorProps[12].required=false&subscriberProfile.visitorProps[12].inputType=input&subscriberProfile.visitorProps[12].refDataKey=&subscriberProfile.visitorProps[12].customPropID=full_name&subscriberProfile.visitorProps[14].value=&subscriberProfile.visitorProps[13].id=2100&subscriberProfile.visitorProps[13].name=insurance carrier&subscriberProfile.visitorProps[13].maxLen=50&subscriberProfile.visitorProps[13].required=false&subscriberProfile.visitorProps[13].inputType=input&subscriberProfile.visitorProps[13].refDataKey=&subscriberProfile.visitorProps[13].customPropID=insurance_carrier&subscriberProfile.visitorProps[14].value=&subscriberProfile.visitorProps[14].id=2100&subscriberProfile.visitorProps[14].name=lead source&subscriberProfile.visitorProps[14].maxLen=50&subscriberProfile.visitorProps[14].required=false&subscriberProfile.visitorProps[14].inputType=input&subscriberProfile.visitorProps[14].refDataKey=&subscriberProfile.visitorProps[14].customPropID=lead_source&subscriberProfile.visitorProps[15].value=&subscriberProfile.visitorProps[15].id=2100&subscriberProfile.visitorProps[15].name=street_address&subscriberProfile.visitorProps[15].maxLen=50&subscriberProfile.visitorProps[15].required=false&subscriberProfile.visitorProps[15].inputType=input&subscriberProfile.visitorProps[15].refDataKey=&subscriberProfile.visitorProps[15].customPropID=street_address&subscriberProfile.visitorProps[16].value=&subscriberProfile.visitorProps[16].id=2100&subscriberProfile.visitorProps[16].name=what_is_the_schedule_a_value_of-your-home?&subscriberProfile.visitorProps[16].maxLen=50&subscriberProfile.visitorProps[16].required=false&subscriberProfile.visitorProps[16].inputType=input&subscriberProfile.visitorProps[16].refDataKey=&subscriberProfile.visitorProps[16].customPropID=what_is_the_schedule_a_value_of-your-home&subscriberProfile.visitorProps[17].value=&subscriberProfile.visitorProps[17].id=2100&subscriberProfile.visitorProps[17].name=what_is_your_current_hurricane_deductible&subscriberProfile.visitorProps[17].maxLen=50&subscriberProfile.visitorProps[17].required=false&subscriberProfile.visitorProps[17].inputType=input&subscriberProfile.visitorProps[17].refDataKey=&subscriberProfile.visitorProps[17].customPropID=what_is_your_current_hurricane_deductible&subscriberProfile.visitorProps[18].value=&subscriberProfile.visitorProps[18].id=2100&subscriberProfile.visitorProps[18].name=what_year_was_your_roof_last_updated?&subscriberProfile.visitorProps[18].maxLen=50&subscriberProfile.visitorProps[18].required=false&subscriberProfile.visitorProps[18].inputType=input&subscriberProfile.visitorProps[18].refDataKey=&subscriberProfile.visitorProps[18].customPropID=what_year_was_your_roof_last_updated&subscriberProfile.visitorProps[19].value=&subscriberProfile.visitorProps[19].id=2100&subscriberProfile.visitorProps[19].name=Year House Built&subscriberProfile.visitorProps[19].maxLen=50&subscriberProfile.visitorProps[19].required=false&subscriberProfile.visitorProps[19].inputType=input&subscriberProfile.visitorProps[19].refDataKey=&subscriberProfile.visitorProps[19].customPropID=year_house_built&subscriberProfile.visitorProps[20].value=&subscriberProfile.visitorProps[20].id=2100&subscriberProfile.visitorProps[20].name=zip_code&subscriberProfile.visitorProps[20].maxLen=50&subscriberProfile.visitorProps[20].required=false&subscriberProfile.visitorProps[20].inputType=input&subscriberProfile.visitorProps[20].refDataKey=&subscriberProfile.visitorProps[20].customPropID=zip_code&_save=Sign Up&captcha.url=$captchaurl&captcha.challenge=&captcha.response=&captcha.key=6LcB5AIAAAAAANx7J2ADgUFxwd_zllfY4DxX81c5&captcha.control=$captcha&subscriptions.interestCategories[0].id=1162386597&subscriptions.interestCategories[0].name=Cat4WebsiteLeads&subscriptions.interestCategories[0].selected=true&_subscriptions.interestCategories[0].selected=on&subscriberProfile.visitorParams=001fF7rnIqcTi_SG89YYbyxWTqb74KJ671opSHQGdT3yj0ysR6DeRqhfdy4RzHf8LKS1uc-D2dd6Z3yOU9Q1SlRjB3el9lsSja8ef0IbNWQt-XYNul8pZ77bSl8wkQzpUlSrVhUeNEpShWXl3Zwj6UF8CbzUF34bPA2m991bPuKLjqiGRwx7QLYuBqwBz_fagjFaiyzKjly4i0AAXBcaFXD_50oT1yQJH-oOVGS8OyUAz8UjCCDCqsPcDryddegwPVWwIWhM-0BmfpE0CQH9ukWTkVqiXr1xA2wasgsSQtceuPhLsOiSHnq9btVATQjQ-D564Oqg4E_9gw7QdgaFZEnwY0oo7Tj10HXItlyp1B2EAna0n01DXc8y9I_FkNmXiGDT3BmSpatGb_Al8PK8K-yQVsTy2WBNj_L8UpA3XVz8OU=&subscriptions.interestCategoriesSize=1&subscriberProfile.visitorPropertiesSize=21";	
			
			curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); 
			curl_close($ch);
			$response= json_decode($result);
			
			$dom = new DOMDocument();

			// load html
			$dom->loadHTML($result);
			$xpath = new DOMXPath($dom);

			$blockquote = $dom->getElementById('optinSuccess');
			
			if( strpos( $blockquote->nodeValue, 'Thank you' ) !== false ) {
				// update the table.
				$sql_update="update scrapped_emails_1 set ctct_status = 1 where id='".$id."'";
				$result_update = mysqli_query($db_handle, $sql_update);
				echo 'Done';
			}
		
			
			// getting sixth form
			/*$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://visitor.r20.constantcontact.com/manage/optin");
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); 
			curl_close($ch);
				
			print_r($result);*/
			
			// if($response==1){
			
				// update the table.
				//$sql_update="update scrapped_emails_1 set ctct_status = 1 where id='".$id."'";
				//$result_update = mysqli_query($db_handle, $sql_update);
			// }
			
		
		}
		
		
	}
	
	function getElementById($id)
	{
		$xpath = new DOMXPath($this->domDocument);
		return $xpath->query("//*[@id='$id']")->item(0);
	}
?>