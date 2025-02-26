<?php 
error_reporting(0); 
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);

//$inbox = imap_open("{a2plvcpnl13722.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl17954.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl40763.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());
$inbox = imap_open("{p3plvcpnl54594.prod.phx3.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'ALL');

$date_db = date('Y-m-d H:i:s');

/* if emails are returned, cycle through each... */
if($emails) {

	/* put the newest emails on top */
	rsort($emails);
	
	/* for every email... */
	foreach($emails as $email_number) {
		
		/* begin output var */
		$output = '';
		
		/* Fields of first table */
		$index_array = array();
	
		//$index_array_claim = array("lead_id","lead_type","date_received","time_received","name","email_address","daytime_phone","street_address","location","currently_insured","current_provider","covered_for","policy_expires","new_purchase","occupancy_status","property_type","construction_type","foundation_type","roof_type","garage_type","electrical_system","security_system","year_built","square_feet","number_of_floors","number_of_bedrooms","number_of_bathrooms","number_of_units","dog","company","property_extras","address","location","applicant_dob","credit_standing","flood_interest","earthquake_interest","deductible","coverage_amount","liability_amount","claim_type","amount","claim_date");
		
		/* Result array */
		$data_array = array();

		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		
		$message = imap_fetchbody($inbox,$email_number,1.2);
		if($message == '') {
			$message2 = imap_fetchbody($inbox,$email_number,1);
			/*if($message == '') {
				$message = imap_fetchbody($inbox,$email_number,1);
			}*/
		}
		
		
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject"> Subject: '.$overview[0]->subject.'</span> <br>';
		$output.= '<span class="from"> From: '.str_replace('>','"',str_replace('<','"',$overview[0]->from)).'</span> <br>';
		$output.= '<span class="date">Date: '.$overview[0]->date.'</span> <br>';
		$output.= '</div>';
		
		if( strpos( $overview[0]->subject, 'New Home Lead Received' ) !== false /*&& strpos( $overview[0]->subject, 'RE: New Home Insurance Call' ) === false && strpos( $overview[0]->subject, 'Re: New Home Insurance Call' ) === false*/) {

			$message = str_replace('""','"',str_replace('3D','',$message));
			
			if($message == '') {
				
				$message2 = str_replace('""','"',str_replace('3D','',$message2));
				$message2 =	trim(preg_replace('/[\n\r]/','e-e',$message2));
				$message2 =	str_replace('=e-e','',$message2);
				$message2 =	str_replace('e-e','',$message2);
				
			}
			
			if($message != '') {
				
				//echo 'first';
				
				$content_part = explode('Lead Information',$message);
			
				$content = explode('</div>',$content_part[1]);
				
				//$skuList = preg_split("/\\r\\n|\\r|\\n/", str_replace('>>','',$content[0]));
			
				$html = '<table class="MsoNormalTable"><tbody><tr><td><h2>'.$content[0];
				
				
				$dom = new DOMDocument();
				
				// load html
				$dom->loadHTML($html);
				$xpath = new DOMXPath($dom);

				//this will gives you all td with class name is jobs.
				$my_xpath_query = "//table[contains(@class, 'MsoNormalTable')]";
				$result_rows = $xpath->query($my_xpath_query);
				
				//echo '<pre>'; print_r($result_rows); echo '</pre>';
			
				if($result_rows->length > 3) {
					for($i=0; $i < $result_rows->length; $i++){
						$table = $dom->getElementsByTagName('table')->item($i);
						if($table) {
							foreach ($table->getElementsByTagName('tr') as $row){
								$cells = $row->getElementsByTagName('td');
								if ( trim($cells->item(0)->nodeValue) !='') {
									$index_array[] = trim(strtolower(str_replace(':','',str_replace(' ','_',$cells->item(0)->nodeValue)))); //First Node
									$data_array[] = str_replace('a>','',str_replace('=','',preg_replace('/\s\s+/','',$cells->item(1)->nodeValue))); // Second Node
								}
							}
						}
						//echo '<hr>';
					}
				}
	
			}
			elseif($message2 != '') {
				
				//echo 'second';
				
				$content_part = explode('Lead Information',$message2);
			
				$content = explode('=20=C2=A9',$content_part[1]);
				
				//$skuList = preg_split("/\\r\\n|\\r|\\n/", str_replace('>>','',$content[0]));
			
				$html = '<table class=""><tbody><tr><td><h2>'.$content[0];
				
				
				$dom = new DOMDocument();
				
				// load html
				$dom->loadHTML($html);
				$xpath = new DOMXPath($dom);

				//this will gives you all td with class name is jobs.
				//$my_xpath_query = "//table[contains(@class, 'MsoNormalTable')]";
				
				$result_rows = $xpath->query('//table');
				
				//echo '<pre>'; print_r($result_rows); echo '</pre>';
			
				if($result_rows->length > 3) {
					for($i=0; $i < $result_rows->length; $i++){
						$table = $dom->getElementsByTagName('table')->item($i);
						if($table) {
							foreach ($table->getElementsByTagName('tr') as $row){
								$cells = $row->getElementsByTagName('td');
								if ( trim($cells->item(0)->nodeValue) !='') {
									$index_array[] = trim(strtolower(str_replace(':','',str_replace(' ','_',$cells->item(0)->nodeValue)))); //First Node
									$data_array[] = str_replace('a>','',str_replace('=09','',str_replace('=20','',preg_replace('/\s\s+/','',$cells->item(1)->nodeValue)))); // Second Node
								}
							}
						}
						//echo '<hr>';
					}
				}
				
			}
			
			
			$final_array = array_combine($index_array,$data_array);
			
			/* Create DB Table and uncomment following area */			
				$result = mysqli_query($db_handle,'SELECT * FROM scrapped_new_home_lead_received WHERE lead_id = "'.$final_array['lead_id'].'"');
				if(mysqli_num_rows($result) == 0 && !empty($final_array)) {
					
					$columns = implode(',',array_keys($final_array));
					$escaped_values = array_values($final_array);
					$values  = implode('","', $escaped_values);
					
					$insert_query = 'INSERT INTO scrapped_new_home_lead_received ('.$columns.',date_time) VALUES ( "'.$values.'" , "'.$date_db.'" ) ';
					
					if(mysqli_query($db_handle,$insert_query)) {
						echo 'Database updated successfully <br>';
					}
					else {
						echo 'Database can\'t be updated. Please try again later <br>';
					}
				}
				else {
					echo 'Record already exist <br>';
				}
			
			echo /*count($index_array).' '.count($data_array).' '.count($final_array).*/'<pre>'; /*print_r($index_array);print_r($data_array);*/print_r($final_array); echo '</pre>'.'</div><br><hr><hr><br>';
			//echo $overview[0]->subject.'<br><hr><hr><br>';
			


			//$output .= '<div class="body">'.str_replace('""','"',str_replace('3D','',$message)).'</div><br><hr><hr><br>';
			//echo $output;
			
			unset($index_array); unset($index_array_claim); unset($data_array); unset($final_array);
			
		}
		
	
	}
		
}