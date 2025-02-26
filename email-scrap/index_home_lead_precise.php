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

function index_name($str){
	if($str!='') {
		$str = strtolower(str_replace(' ','_',trim($str)));
		return str_replace([':', '?', '.','=2e','=3f'],'',$str);
	}
}

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
		
		$message = imap_fetchbody($inbox,$email_number,1);
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
		
		
		if( strpos( $overview[0]->subject, 'Fwd: Home Lead - ID' ) !== false ) {
			
			$message = imap_fetchbody($inbox,$email_number,2);
			
			$message = explode('Check out our blog',$message);
		
			$message = str_replace('=3E','>',str_replace(' class=""','',preg_replace('/\r|\n/','',preg_replace('/=\r|=\n/','',str_replace('3D','',$message[0])))));
			
			$html = $message;
			
			$dom = new DOMDocument();
			
			// load html
			$dom->loadHTML($html);
			$xpath = new DOMXPath($dom);

			//this will gives you all td with class name is jobs.
			$my_xpath_query = "//table";
			$result_rows = $xpath->query($my_xpath_query);
			
			//echo '<pre>'; print_r($result_rows); echo '</pre>';
			
			$lead_id = explode(':',$overview[0]->subject);
			
			if($result_rows->length > 3) {
				
				for($i=0; $i < $result_rows->length; $i++) {
					$table = $dom->getElementsByTagName('table')->item($i);
					if($table) {

						$index_array[] = 'lead_id';
						$data_array[] = trim($lead_id[2]);
					
						foreach ($table->getElementsByTagName('tr') as $row){
						
							$cells = $row->getElementsByTagName('td');
							
							$k = 0;
							foreach($cells->item(0)->getElementsByTagName('b') as $child) {
								
								if($k.$i == '01' ) {
									$index_array[] = 'name'; 
									$data_array[] = $child->textContent;
								} else {
									$index_array[] = index_name($child->textContent);
								}
								if($child->nextSibling->nodeName=='br' && $child->nextSibling->nodeValue == '') {
									if($k.$i == '01'  ) {
										$index_array[] = 'address'; 
										$data_array[] = $child->nextSibling->nextSibling->nodeValue.' '.$child->nextSibling->nextSibling->nextSibling->nextSibling->nodeValue;
									}
									else {
										$data_array[] = $child->nextSibling->nextSibling->nextSibling->nextSibling->nodeValue;
									}
									
								}
								else {
									$data_array[] = trim($child->nextSibling->nodeValue);
								}
								$k++;
							}
							

							foreach($cells->item(1)->getElementsByTagName('b') as $child) {
								
								$index_array[] = index_name($child->textContent);
								if($child->textContent=='Email' && trim($child->nextSibling->nodeValue) == '') {
									$data_array[] = $child->nextSibling->nextSibling->nodeValue;
								}
								else {
									$data_array[] = trim($child->nextSibling->nodeValue);
								}
							}
						}
					}
				}
			}
			
			$final_array = array_combine($index_array,$data_array);
			//echo count($final_array);
			echo '<pre>'; print_r($final_array); echo '</pre>';
			
			$result = mysqli_query($db_handle,'SELECT * FROM scrapped_home_precise_leads WHERE lead_id = "'.$final_array['lead_id'].'"');
				if(mysqli_num_rows($result) == 0) {
					
					$columns = implode(',',array_keys($final_array));
					$escaped_values = array_values($final_array);
					$values  = implode('","', $escaped_values);
					
					$insert_query = 'INSERT INTO scrapped_home_precise_leads ('.$columns.',date_time) VALUES ( "'.$values.'" , "'.$date_db.'" ) ';
					
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
			
			echo '<br><hr><hr><br>';
			//$output .= '<div class="body">'.str_replace('','',str_replace('','',str_replace('3D','',$message))).'</div><br><hr><hr><br>';
			//echo $output;
			
		}
		elseif( strpos( $overview[0]->subject, 'Home Lead - ID' ) !== false ) {
			
			//echo $message;
			
			$message = explode('Check out our blog',$message);
		
			$message = str_replace('=C3','A',str_replace('=B1','+',str_replace('=2e','.',str_replace('=2E','.',str_replace('=3f','?',str_replace('=3E','>',str_replace(' class=""','',preg_replace('/\r|\n/','',preg_replace('/=\r|=\n/','',str_replace('3D','',$message[0]))))))))));
			
			$html = $message;
			
			$dom = new DOMDocument();
			
			// load html
			$dom->loadHTML($html);
			$xpath = new DOMXPath($dom);

			//this will gives you all td with class name is jobs.
			$my_xpath_query = "//table";
			$result_rows = $xpath->query($my_xpath_query);
			
			//echo '<pre>'; print_r($result_rows); echo '</pre>';
			
			$lead_id = explode(':',$overview[0]->subject);
			
			if($result_rows->length > 3) {
				
				for($i=0; $i < $result_rows->length; $i++) {
					$table = $dom->getElementsByTagName('table')->item($i);
					if($table) {

						$index_array[] = 'lead_id';
						$data_array[] = trim($lead_id[1]);
					
						foreach ($table->getElementsByTagName('tr') as $row){
						
							$cells = $row->getElementsByTagName('td');
							
							$k = 0;
							foreach($cells->item(0)->getElementsByTagName('b') as $child) {
								
								if($k.$i == '01' ) {
									$index_array[] = 'name'; 
									$data_array[] = $child->textContent;
								} else {
									$index_array[] = index_name($child->textContent);
								}
								if($child->nextSibling->nodeName=='br' && $child->nextSibling->nodeValue == '') {
									if($k.$i == '01'  ) {
										$index_array[] = 'address'; 
										$data_array[] = $child->nextSibling->nextSibling->nodeValue.' '.$child->nextSibling->nextSibling->nextSibling->nextSibling->nodeValue;
									}
									else {
										$data_array[] = $child->nextSibling->nextSibling->nextSibling->nextSibling->nodeValue;
									}
									
								}
								else {
									$data_array[] = trim($child->nextSibling->nodeValue);
								}
								$k++;
							}
							

							foreach($cells->item(1)->getElementsByTagName('b') as $child) {
								
								$index_array[] = index_name($child->textContent);
								if($child->textContent=='Email' && trim($child->nextSibling->nodeValue) == '') {
									$data_array[] = $child->nextSibling->nextSibling->nodeValue;
								}
								else {
									$data_array[] = trim($child->nextSibling->nodeValue);
								}
							}
						}
					}
				}
			}
			
			$final_array = array_combine($index_array,$data_array);
			//echo count($final_array);
			echo '<pre>'; print_r($final_array); echo '</pre>';
			
			$result = mysqli_query($db_handle,'SELECT * FROM scrapped_home_precise_leads WHERE lead_id = "'.$final_array['lead_id'].'"');
				if(mysqli_num_rows($result) == 0) {
					
					$columns = implode(',',array_keys($final_array));
					$escaped_values = array_values($final_array);
					$values  = implode('","', $escaped_values);
					
					$insert_query = 'INSERT INTO scrapped_home_precise_leads ('.$columns.',date_time) VALUES ( "'.$values.'" , "'.$date_db.'" ) ';
					
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
			
			echo '<br><hr><hr><br>';
			//$output .= '<div class="body">'.str_replace('','',str_replace('','',str_replace('3D','',$message))).'</div><br><hr><hr><br>';
			//echo $output;

		}
	
	}
		
}