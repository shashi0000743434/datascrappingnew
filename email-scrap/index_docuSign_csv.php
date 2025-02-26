<?php 
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/email-scrap/lib/imap-attachment.php';
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);

//$inbox = imap_open("{a2plvcpnl13722.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321") or die('Cannot connect to Email: ' . imap_last_error());
//$inbox = new IMAPMailbox("{a2plvcpnl17954.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321");
//$inbox = new IMAPMailbox("{a2plvcpnl40763.prod.iad2.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321");
$inbox = new IMAPMailbox("{p3plvcpnl54594.prod.phx3.secureserver.net}INBOX", "leads@enzymelabssetup.com", "Howard321");

/* grab emails */
$emails = $inbox->search('ALL');

$date_db = date('Y-m-d H:i:s');

$two_days_ago = strtotime('-2 days');
/* if emails are returned, cycle through each... */
if($emails) {
	/* put the newest emails on top */
	rsort($emails);
	$i = 1;
	/* for every email... */
	foreach($emails as $email_number) {
		
		/* begin output var */
		$output = '';
		
		/* Fields of first table */
		$index_array = array();
		/* Result array */
		$data_array = array();

		/* get information specific to this email */
		
		$overview = new IMAPOverview($inbox,$email_number,0);
		
	
		if( strpos( $overview[0]->subject, 'DocuSign Report' ) !== false) {
						
			$savedir = $_SERVER['DOCUMENT_ROOT'].'/datascrapping/email-scrap/csv_files/';
			
			
			foreach ($email_number->getAttachments() as $attachment) {
				$uniqid = $overview[0]->udate;
				$zipname = $uniqid.$attachment->getFilename();
				$savepath = $savedir . $zipname;
				if(!file_exists($savepath)) {
					if(file_put_contents($savepath, $attachment)){
						echo 'Copy<br>';
						$zip = new ZipArchive;
						$res = $zip->open($savepath);
						if ($res === TRUE) {
						  $zip->extractTo($savedir);
						  $zip->close();
						  echo 'Ex Done!<br>';
						} else {
						  echo 'Ex Not Done!<br>';
						}
						$oldcsv = $savedir.'Envelope_Recipient_Report1.csv';
						$new_csv = $savedir.$uniqid.'Envelope_Recipient_Report1.csv';
						rename($oldcsv,$new_csv);
						
						$file = fopen($new_csv,"r");
						$s = 1;
					while(! feof($file))
					{
						$csv_data_array = fgetcsv($file);
						echo $i.$s.'<pre>'; print_r($csv_data_array); echo '</pre>';
						if($s != 1) {
							$phone = '';
							$get_phone = explode('(',$csv_data_array[0]);
							//print_r($get_phone);
							if($get_phone && $get_phone[1]!=''){
								if(substr($get_phone[1],0,12) != '') {
									echo $phone = '('.substr($get_phone[1],0,12);
									echo '<br>';
								}								
							}
							else {
								$get_phone2 = explode('((',$csv_data_array[0]);
								if($get_phone2){
									if(substr($get_phone2[1],0,12) != '') {
										echo $phone = '('.substr($get_phone2[1],0,12);
										echo '<br>';
									}								
								}
							}
							
							$sent_date = strtotime($csv_data_array[4]);
							if(($csv_data_array[3] == 'Sent' || $csv_data_array[3] == 'Delivered') && $csv_data_array[4] != '' && $csv_data_array[5] == '' && $sent_date > $two_days_ago ) {
								$result = mysqli_query($db_handle,'SELECT * FROM scrapped_docusign_csv WHERE subject = "'.$csv_data_array[0].'" and status = "'.$csv_data_array[1].'" and recipient_name = "'.$csv_data_array[2].'" and action = "'.$csv_data_array[3].'" and sent_on = "'.$csv_data_array[4].'" and completed_on = "'.$csv_data_array[5].'" and view_date = "'.$csv_data_array[6].'" and status_changed_date = "'.$csv_data_array[7].'" and sign_date = "'.$csv_data_array[8].'" and recipient_email = "'.$csv_data_array[9].'"');
								if(mysqli_num_rows($result) == 0) {
									$insert_query = 'INSERT INTO scrapped_docusign_csv (phone, subject, status, recipient_name, action, sent_on, completed_on, view_date, status_changed_date, sign_date, recipient_email, date_time) VALUES ( "'.$phone.'", "'.$csv_data_array[0].'", "'.$csv_data_array[1].'", "'.$csv_data_array[2].'", "'.$csv_data_array[3].'", "'.$csv_data_array[4].'", "'.$csv_data_array[5].'", "'.$csv_data_array[6].'", "'.$csv_data_array[7].'", "'.$csv_data_array[8].'", "'.$csv_data_array[9].'" , "'.$date_db.'" ) ';
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
							}
						}
						$s++;
					}
						fclose($file);
					}
					else {
						echo 'No <br>';
					}
				}
				else {
					echo 'File exist<br>';
					$new_csv = $savedir.$uniqid.'Envelope_Recipient_Report1.csv';
					$file = fopen($new_csv,"r");
					$s = 1;
		
					
					while(! feof($file))
					{
						$csv_data_array = fgetcsv($file);
						echo $i.$s.'<pre>'; print_r($csv_data_array); echo '</pre>';
						if($s != 1) {
							$phone = '';
							$get_phone = explode('(',$csv_data_array[0]);
							//print_r($get_phone);
							if($get_phone && $get_phone[1]!=''){
								if(substr($get_phone[1],0,12) != '') {
									echo $phone = '('.substr($get_phone[1],0,12);
									echo '<br>';
								}								
							}
							else {
								$get_phone2 = explode('((',$csv_data_array[0]);
								if($get_phone2){
									if(substr($get_phone2[1],0,12) != '') {
										echo $phone = '('.substr($get_phone2[1],0,12);
										echo '<br>';
									}								
								}
							}
							$sent_date = strtotime($csv_data_array[4]);
							if(($csv_data_array[3] == 'Sent' || $csv_data_array[3] == 'Delivered') && $csv_data_array[4] != '' && $csv_data_array[5] == '' && $sent_date > $two_days_ago ) {
								$result = mysqli_query($db_handle,'SELECT * FROM scrapped_docusign_csv WHERE subject = "'.$csv_data_array[0].'" and status = "'.$csv_data_array[1].'" and recipient_name = "'.$csv_data_array[2].'" and action = "'.$csv_data_array[3].'" and sent_on = "'.$csv_data_array[4].'" and completed_on = "'.$csv_data_array[5].'" and view_date = "'.$csv_data_array[6].'" and status_changed_date = "'.$csv_data_array[7].'" and sign_date = "'.$csv_data_array[8].'" and recipient_email = "'.$csv_data_array[9].'"');
								if(mysqli_num_rows($result) == 0) {
									$insert_query = 'INSERT INTO scrapped_docusign_csv (phone, subject, status, recipient_name, action, sent_on, completed_on, view_date, status_changed_date, sign_date, recipient_email, date_time) VALUES ( "'.$phone.'", "'.$csv_data_array[0].'", "'.$csv_data_array[1].'", "'.$csv_data_array[2].'", "'.$csv_data_array[3].'", "'.$csv_data_array[4].'", "'.$csv_data_array[5].'", "'.$csv_data_array[6].'", "'.$csv_data_array[7].'", "'.$csv_data_array[8].'", "'.$csv_data_array[9].'" , "'.$date_db.'" ) ';
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
							}								
						}
						$s++;
					}
					fclose($file);
				}
				
			}

			//echo '<pre>';print_r($structure);echo '</pre>';
		
			echo '<br><hr><hr><br>';
			//$output .= '<div class="body">'.str_replace('""','"',str_replace('3D','',str_replace('=0D','',$message))).'</div><br><hr><hr><br>';
			//echo $output;
			$i++;
		}
		
	
	}
		
}