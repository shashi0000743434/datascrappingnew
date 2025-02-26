<?php
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);

//$inbox = imap_open("{a2plvcpnl13722.prod.iad2.secureserver.net}INBOX", "phonelads1@enzymelabssetup.com", "phoneleads1") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl17954.prod.iad2.secureserver.net}INBOX", "phonelads1@enzymelabssetup.com", "phoneleads1") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl40763.prod.iad2.secureserver.net}INBOX", "phonelads1@enzymelabssetup.com", "phoneleads1") or die('Cannot connect to Email: ' . imap_last_error());
$inbox = imap_open("{p3plvcpnl54594.prod.phx3.secureserver.net}INBOX", "phonelads1@enzymelabssetup.com", "phoneleads1") or die('Cannot connect to Email: ' . imap_last_error());

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
		$index_array = array("first_name","last_name","email","phone","street_address","apt_suite","city","state","postal_code","contact_method","insurance_type","ip_address","lead_source");
		/* Fields of second table */
		$index_array2 = array("phone","first_name","last_name","email","city","postal_code","street_address");
		/* Result array */
		$data_array = array();
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		$structure = imap_fetchstructure($inbox, $email_number);
		
		$message = imap_fetchbody($inbox,$email_number,1);
		
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject"> Subject: '.$overview[0]->subject.'</span> <br>';
		$output.= '<span class="from"> From: '.str_replace('>','"',str_replace('<','"',$overview[0]->from)).'</span> <br>';
		$output.= '<span class="date">Date: '.$overview[0]->date.'</span> <br>';
		$output.= '</div>';
		
		/* For first case email with title "You have a new quote request" */
		if( strpos( $overview[0]->subject, 'You have a new quote request' ) !== false ) {
			
			//echo '<pre>'; print_r($structure); echo '</pre>';
		
			/* output the email body */
			if ($structure->parts[0]->encoding==3) {
				$message_new = imap_base64($message);
				$output.= '<div class="body">'.(imap_base64($message)).'</div> <br><br><br><br><hr><hr><br><br><br><br>';
			}
			else if ($structure->parts[0]->encoding==4) {
				$message_new = imap_qprint($message);
				$output.= '<div class="body">'.(imap_qprint($message)).'</div> <br><br><br><br><hr><hr><br><br><br><br>';
			}
			else {
				$body_content = quoted_printable_decode($message);
				$message_new = quoted_printable_decode($message);
				$output.= '<div class="body">'.quoted_printable_decode($message).'</div> <br><br><br><br><hr><hr><br><br><br><br>';
			}
			
			$mes1 = explode('First Name',$message_new);
			$mes2 = explode('This email was',$mes1[1]);
			
			$result_data = 'First Name'.trim($mes2[0]);
			
			$skuList = preg_split("/\\r\\n|\\r|\\n/", $result_data);
			
			//echo '<pre>'; print_r($skuList); echo '</pre>';
			
			$skuList1 = explode(":",$result_data);
			
			$i = 1;
			foreach($skuList1 as $sku) {
				//$skuListNEw = explode(":", trim(str_replace('*','',$sku)));
				$skuListNEw = preg_split("/\\r\\n|\\r|\\n/", trim(str_replace('>','"',str_replace('<','"',str_replace('*','',$sku)))));
				if($i!=1 && $i!=4) {
					if($i==8) {
						if($skuListNEw[0]=='City') {
							echo $data_array[] =  ' ';
							echo '<br>';
						}
						else {
							echo $data_array[] =  $skuListNEw[0];
							echo '<br>';
						}
					}
					elseif($i==5) {
						echo $data_array[] =  str_replace('"','',$skuListNEw[0]);
						echo '<br>';
					}	
					else {
						echo $data_array[] = $skuListNEw[0];
						echo '<br>';
					}
				}
				$i++;
			}
			
			echo '<hr><hr>';
		
			/* Final array for first table */
			$final_array = array_combine($index_array,$data_array);
			
				/* Enter records in scrapped_emails_1 table */
				$result = mysqli_query($db_handle,'SELECT * FROM scrapped_emails_1 WHERE phone = "'.$final_array['phone'].'"');
				if(mysqli_num_rows($result) == 0) {
					$insert_query = 'INSERT INTO scrapped_emails_1 ('.implode(',',$index_array).',date_time) VALUES ( "'.implode('","',$data_array).'" , "'.$date_db.'" ) ';
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
			
			
			/* Unset all arrays used */
			unset($index_array); unset($data_array); unset($final_array);	
			//echo $output;
	
		}
		/* For first case email with title "NEW CAT4LEAD PLUS HOMEINSURANCE LEAD" */
/*		elseif( strpos( $overview[0]->subject, 'NEW CAT4LEAD PLUS HOMEINSURANCE LEAD' ) !== false ) {
			
			$body_content2 = quoted_printable_decode($message);
			$output .= '<div class="body">'.($body_content2).'</div> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
			
			/* Email data scraping */
/*			$dom2 = new DOMDocument();
			$html2 = $body_content2;
			// load html
			$dom2->loadHTML($html2);
			$xpath = new DOMXPath($dom2);

			$blockquote = $dom2->getElementsByTagName('blockquote');
			
			/* If email contains blockquote */
/*			if($blockquote->length != 0	) {
			
				$spans = $dom2->getElementsByTagName('span');
				
				/* If blockquote contains spans */
/*				if($spans->length != 0 ) {
					
					$n=1;
					foreach ($spans as $span){
						
						if($n <= 7) { 
							
							$data_array[] = trim($span->nodeValue);
							//echo '<pre>'; print_r($span); echo '</pre>';
						}
						$n++;
					}
				}
				
			}
			
			$final_array = array_combine($index_array2,$data_array);
			
			/* Enter records in scrapped_emails_2 table */
/*			$result = mysqli_query($db_handle,'SELECT * FROM scrapped_emails_2 WHERE phone = "'.$final_array['phone'].'"');
			if(mysqli_num_rows($result) == 0) {
				$insert_query = 'INSERT INTO scrapped_emails_2 ('.implode(',',$index_array2).') VALUES ( "'.implode('","',$data_array).'" ) ';
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
			/* Unset all arrays used */
/*			unset($index_array2); unset($data_array); unset($final_array);
			
			echo $output;
			
		}
*/	
	}
		
}