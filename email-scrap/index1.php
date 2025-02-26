<?php
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);

//$inbox = imap_open("{a2plvcpnl13722.prod.iad2.secureserver.net}INBOX", "cat4leads@enzymelabssetup.com", "Howard321!") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl17954.prod.iad2.secureserver.net}INBOX", "cat4leads@enzymelabssetup.com", "Howard321!") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = imap_open("{a2plvcpnl40763.prod.iad2.secureserver.net}INBOX", "cat4leads@enzymelabssetup.com", "Howard321!") or die('Cannot connect to Email: ' . imap_last_error());
$inbox = imap_open("{p3plvcpnl54594.prod.phx3.secureserver.net}INBOX", "cat4leads@enzymelabssetup.com", "Howard321!") or die('Cannot connect to Email: ' . imap_last_error());

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
		$index_array2 = array("phone","first_name","last_name","email","city","postal_code","street_address","schedule_value","hurricane_deductible","year_roof_updated");
		/* Result array */
		$data_array = array();

		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		
		$message = imap_fetchbody($inbox,$email_number,2);
		if($message == '') {
			$message = imap_fetchbody($inbox,$email_number,1.1);
			if($message == '') {
				$message = imap_fetchbody($inbox,$email_number,1);
			}
		}
		
		
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject"> Subject: '.$overview[0]->subject.'</span> <br>';
		$output.= '<span class="from"> From: '.str_replace('>','"',str_replace('<','"',$overview[0]->from)).'</span> <br>';
		$output.= '<span class="date">Date: '.$overview[0]->date.'</span> <br>';
		$output.= '</div>';
		
		if( strpos( $overview[0]->subject, 'NEW CAT4LEAD PLUS HOMEINSURANCE LEAD' ) !== false ) {
			
			if(preg_match( "/\/[a-z]*>/i", $message ) != 0) {
				/* DO NOTHING */
				unset($index_array2); unset($data_array); 
			}
			else {

				$skuList = preg_split("/\\r\\n|\\r|\\n/", $message);
				
				$data_array = array_slice($skuList,0,10);
				
				$final_array = array_combine($index_array2,$data_array);
				$result = mysqli_query($db_handle,'SELECT * FROM scrapped_emails_2 WHERE phone = "'.$final_array['phone'].'"');
				if(mysqli_num_rows($result) == 0) {
					$insert_query = 'INSERT INTO scrapped_emails_2 ('.implode(',',$index_array2).',date_time) VALUES ( "'.implode('","',$data_array).'" , "'.$date_db.'" ) ';
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
				unset($index_array2); unset($data_array); unset($final_array);
				
				$output .= '<div class="body">'.nl2br($message).'</div><br><hr><hr><br>';
				echo $output;
				
			}

			
		}
	
	}
		
}