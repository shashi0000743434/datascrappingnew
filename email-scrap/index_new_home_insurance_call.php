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
		/* Result array */
		$data_array = array();

		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		$structure = imap_fetchstructure($inbox, $email_number);
		
		
		/*if($message == '') {
			$message = imap_fetchbody($inbox,$email_number,1.1);
			if($message == '') {
				$message = imap_fetchbody($inbox,$email_number,1);
			}
		}*/
		
		
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject"> Subject: '.$overview[0]->subject.'</span> <br>';
		$output.= '<span class="from"> From: '.str_replace('>','"',str_replace('<','"',$overview[0]->from)).'</span> <br>';
		$output.= '<span class="date">Date: '.$overview[0]->date.'</span> <br>';
		$output.= '</div>';
		$output.= 'Structure '.$structure->encoding;
		
		if( strpos( $overview[0]->subject, 'New Home Insurance Call' ) !== false && strpos( $overview[0]->subject, 'RE: New Home Insurance Call' ) === false && strpos( $overview[0]->subject, 'Re: New Home Insurance Call' ) === false) {
		
		
			$message = imap_fetchbody($inbox,$email_number,2);
		
		
			$message = str_replace('""','"',str_replace('3D','',str_replace('=0D','',$message)));
			
			$html = $message;
			
			
			$dom = new DOMDocument();
			
			// load html
			$dom->loadHTML($html);
			$xpath = new DOMXPath($dom);

			//this will gives you all td with class name is jobs.
			$my_xpath_query = "//table[contains(@class, 'deviceWidth')]";
			$result_rows = $xpath->query($my_xpath_query);
			
			//echo '<pre>'; print_r($result_rows); echo '</pre>';
		
			if($result_rows->length > 6) {
				
				
				$table = $dom->getElementsByTagName('table')->item(0);
					if($table) {
						$i=$j=1;
						foreach ($table->getElementsByTagName('tr') as $row){
							$cells = $row->getElementsByTagName('td');
							if ( trim($cells->item(1)->nodeValue) !='' && $i > 3) {
								if( strpos( $cells->item(1)->nodeValue, 'Awaiting Your Call' ) !== false) {
									break;
								}
								$index_array[] 	=	trim(str_replace('>','',str_replace('=','',strtolower(str_replace(' ','_',$cells->item(1)->nodeValue))))); //First Node
								if(trim(str_replace('>','',str_replace('=','',strtolower(str_replace(' ','_',$cells->item(1)->nodeValue))))) == 'email' )
								{
									$data_array[] 	=	trim(preg_replace('/\s+/','',str_replace('>','',str_replace('n>','',str_replace('an>','',str_replace('pan>','',str_replace('span>','',str_replace('=','',$cells->item(2)->nodeValue)))))))); // Second Node
								}
								else {
									$data_array[] 	=	trim(str_replace('>','',str_replace('=','',$cells->item(2)->nodeValue))); // Second Node
								}
								$j++;
							}
							$i++;
						}
					}
					
				$final_array = array_combine($index_array,$data_array);
				echo '<pre>'; print_r($final_array); echo '</pre>';
				
				$result = mysqli_query($db_handle,'SELECT * FROM scrapped_datalot_new_home_insurance_call WHERE home_phone = "'.$final_array['home_phone'].'"');
				if(mysqli_num_rows($result) == 0) {
					echo $insert_query = 'INSERT INTO scrapped_datalot_new_home_insurance_call ('.implode(',',$index_array).',date_time) VALUES ( "'.implode('","',$data_array).'" , "'.$date_db.'" ) ';
					if(mysqli_query($db_handle,$insert_query)) {
						echo '<br>Database updated successfully <br>';
					}
					else {
						echo '<br>Database can\'t be updated. Please try again later <br>';
					}
				}
				else {
					echo '<br>Record already exist <br>';
				}
				
				/* Unset all arrays used */
				unset($index_array); unset($data_array); unset($final_array);	
			}
		
			//echo '<br><hr><hr><br>';
			//$output .= '<div class="body">'.str_replace('""','"',str_replace('3D','',str_replace('=0D','',$message))).'</div><br><hr><hr><br>';
			//echo $output;

		}
		
		//only for replied messages
		elseif( strpos( $overview[0]->subject, 'New Home Insurance Call' ) !== false && (strpos( $overview[0]->subject, 'RE: New Home Insurance Call' ) !== false || strpos( $overview[0]->subject, 'Re: New Home Insurance Call' ) !== false)) {
		
		echo 'Replied'.'<br>';
			$message = imap_fetchbody($inbox,$email_number,1.2);
		
			$message = str_replace('""','"',str_replace('3D','',str_replace('=0D','',$message)));
			
			$html = $message;
			
			$dom = new DOMDocument();
			
			// load html
			$dom->loadHTML($html);
			$xpath = new DOMXPath($dom);

			//this will gives you all td with class name is jobs.
			$my_xpath_query = "//table[contains(@class, 'MsoNormalTable')]";
			$result_rows = $xpath->query($my_xpath_query);
			
			//echo '<pre>'; print_r($result_rows); echo '</pre>';
		
			if($result_rows->length > 6) {
				
				
				$table = $dom->getElementsByTagName('table')->item(0);
					if($table) {
						$i=$j=1;
						foreach ($table->getElementsByTagName('tr') as $row){
							$cells = $row->getElementsByTagName('td');
							if ( trim($cells->item(1)->nodeValue) !='' && $i > 3) {
								//echo preg_replace('/^\s+|\n|\r|\s+$/m', ' ', str_replace('=','',$cells->item(1)->nodeValue)).'////';
								//echo $cells->item(1)->nodeValue;
								if( strpos( $cells->item(1)->nodeValue, 'Awaitin' ) !== false) {
									break;
								}
								
								$index_array[] 	=	trim(preg_replace('/\s+/','',str_replace('>','',str_replace('=','',str_replace('p>','',str_replace(':p>','',str_replace('o:p>','',str_replace('n>','',str_replace('u>','',str_replace('b>','',str_replace('an>','',str_replace('pan>','',str_replace('span>','',strtolower(str_replace(' ','_',$cells->item(1)->nodeValue))))))))))))))); //First Node
								if(trim(str_replace('>','',str_replace('=','',strtolower(str_replace(' ','_',$cells->item(1)->nodeValue))))) == 'email' )
								{
									$data_array[] 	=	trim(preg_replace('/\s+/','',str_replace('>','',str_replace('=','',$cells->item(2)->nodeValue)))); // Second Node
								}
								else {
									$data_array[] 	=	trim(preg_replace('/^\s+|\n|\r|\s+$/m', '',str_replace('>','',str_replace('=','',str_replace('p>','',str_replace(':p>','',str_replace('o:p>','',str_replace('n>','',$cells->item(2)->nodeValue)))))))); // Second Node
								}
								$j++;
							}
							$i++;
						}
					}
				//$index_array = array_unique($index_array);
				$final_array = array_combine($index_array,$data_array);
				echo '<pre>'; print_r($final_array); echo '</pre>';
				
				$result = mysqli_query($db_handle,'SELECT * FROM scrapped_datalot_new_home_insurance_call WHERE home_phone = "'.$final_array['home_phone'].'"');
				if(mysqli_num_rows($result) == 0) {
					echo $insert_query = 'INSERT INTO scrapped_datalot_new_home_insurance_call ('.implode(',',$index_array).',date_time) VALUES ( "'.implode('","',$data_array).'" , "'.$date_db.'" ) ';
					if(mysqli_query($db_handle,$insert_query)) {
						echo '<br>Database updated successfully <br>';
					}
					else {
						echo '<br>Database can\'t be updated. Please try again later <br>';
					}
				}
				else {
					echo '<br>Record already exist <br>';
				}
				
				/* Unset all arrays used */
				unset($index_array); unset($data_array); unset($final_array);	
			}
		
			echo '<br><hr><hr><br>';
			//$output .= '<div class="body">'.str_replace('""','"',str_replace('3D','',str_replace('=0D','',$message))).'</div><br><hr><hr><br>';
			//echo $output;

		}
		
	
	}
		
}