<?php
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);

//$inbox = imap_open("{p3plcpnl0270.prod.phx3.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());

//$inbox = imap_open('{p3plcpnl0270.prod.phx3.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());

//$inbox = imap_open('{a2plvcpnl40763.prod.iad2.secureserver.net}INBOX', 'leads@enzymelabssetup.com', 'Howard321') or die('Cannot connect to Email: ' . imap_last_error());

$inbox = imap_open('{p3plvcpnl54594.prod.phx3.secureserver.net}INBOX', 'leads@enzymelabssetup.com', 'Howard321') or die('Cannot connect to Email: ' . imap_last_error());

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
		
		$message = imap_fetchbody($inbox,$email_number,2);
		
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject"> Subject: '.$overview[0]->subject.'</span> <br>';
		$output.= '<span class="from"> From: '.str_replace('>','"',str_replace('<','"',$overview[0]->from)).'</span> <br>';
		$output.= '<span class="date">Date: '.$overview[0]->date.'</span> <br>';
		$output.= '</div>';
		
		/* For first case email with title "New realtor.com lead" */
		if( strpos( $overview[0]->subject, 'New realtor.com lead' ) !== false) {
			
			$message_new = quoted_printable_decode($message);
		
		
			$mes1 = explode('href="tel:',$message_new);
			$mes2 = explode('Status',$mes1[1]);
			
			$phone_html = explode('"',$mes2[0]);
			
			$email_html = explode('href="mailto:',$mes2[0]);
			$email_html_new = explode('"',$email_html[1]);
			
			$get_name = explode('-',$overview[0]->subject);
			
			echo $name = ucwords(trim($get_name[1]));
			
			$split_name = explode(' ',$name);
			$first_name = $split_name[0];
			if(isset($split_name[1])) {
				$last_name = $split_name[1];
			}
			else {
				$last_name = '';
			}
			
			
			echo '<br>';
			echo $phone = $phone_html[0];
			echo '<br>';
			echo $email = $email_html_new[0];
			echo '<br>';
			
			$font_area = explode('class="font12"',$mes2[0]);
			
			$j=0;
			foreach($font_area as $font) {
				
				if($j > 0 && $j < 6) {
				
					if($j == 1) {
						$data_font = explode('text-decoration:none;">',$font);
						$data = explode('</a>',$data_font[1]);
						echo $mlsid = $data[0];
						echo '<br>';
					}
					if($j == 3) {
						$data_font = explode('text-decoration:none;">',$font);
						$data = explode('</a>',$data_font[1]);
						echo $address = strip_tags($data[0]);
						echo '<br>';
					}
					if($j == 4) {
						$data_font = explode('<strong>',$font);
						$data = explode('</strong>',$data_font[1]);
						echo $price = strip_tags($data[0]);
						echo '<br>';
					}
					if($j == 5) {
						$data_font = explode('1.4;">',$font);
						$data = explode('</font>',$data_font[1]);
						echo $size = strip_tags($data[0]);
						echo '<br>';
					}
	
				}
				
				$j++;
			}
			
			$result = mysqli_query($db_handle,'SELECT * FROM scrapped_realtor WHERE phone = "'.$phone.'"');
			if(mysqli_num_rows($result) == 0) {
				$insert_query = 'INSERT INTO scrapped_realtor (first_name, last_name, email, phone, mls_id, address, price, size, date_time) VALUES ( "'.$first_name.'" , "'.$last_name.'" , "'.$email.'" , "'.$phone.'" , "'.$mlsid.'" , "'.$address.'" , "'.$price.'" , "'.$size.'" , "'.$date_db.'" ) ';
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
			
			echo '<br><hr><hr><br>';
	
		}
		
	}
	die;
		
}