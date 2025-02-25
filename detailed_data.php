<?php
    // Turn off all error reporting
    error_reporting(0);
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/simple_html_dom.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);	
	if(isset($_GET['search_id']) && !empty($_GET['search_id'])){
		//username and password of account
		//$username = 'p125193';
		//$password = 'Cole2017@';
		$username = 'w572250';
		$password = 'AB2020';
		$search_id=$_GET['search_id'];
		$dir = $_SERVER['DOCUMENT_ROOT']."/datascrapping";
		$path = $dir;
		//login form action url
		$url="https://fmap.citizensfla.com/fmap/login.do"; 
		$postinfo = "loginId=".$username."&password=".$password."&_event_=login";
		$cookie_file_path = $path."/cookie.txt";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 400); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
		//set the cookie the site has for certain features, this is optional
		curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		curl_exec($ch);	
	
		$url="https://fmap.citizensfla.com/fmap/quoterequest.do"; 		
		$postinfo = "searchId=$search_id&_event_=viewQuoteRequest&quoteRequestId=".$_GET['id'];	
		curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		$html2 = curl_exec($ch);
		curl_close($ch);
		$xml2 = new DOMDocument();
		$xml2->validateOnParse = true;
		$xml2->loadHTML($html2);	    	
		$xpath2 = new DOMXPath($xml2);
		$table2 =$xpath2->query("//*[@class='eightPt_normal']")->item(0);
		// for printing the whole html table just type: print $xml->saveXML($table); 
		$rows2 = $table2->getElementsByTagName("tr");	
		echo "<table border='1' cellspacing='0' style='width: 100%;'><tr><th colspan='3'>Property Information</th></tr>";
		$j=1;
		foreach($rows2 as $row2) {
			echo "<tr>";		
			$cells2 = $row2 -> getElementsByTagName('td');
			foreach($cells2 as $cell2) {		    
				if($j!=1 && $j!=3){     
				  echo "<td>".utf8_decode($cell2->nodeValue)."</td>";
				}
			}
			echo '</tr>';
			$j++;
		}
		echo "</table>";
		
	}	
?>