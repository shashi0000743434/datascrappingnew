<?php
	// Turn off all error reporting
	error_reporting(1);	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/simple_html_dom.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);
	
		//username and password of account
		//$username = 'p125193';
		//$password = 'Cole2017@';		
		//$username = 'w572250';	
		//$password = 'AB2020';
		echo $username = 'abartlett';	
		echo $password = 'Protective7!';
		//$search_id=$_POST['search_id'];
		//set the directory for the cookie using defined document root var
		 $dir = $_SERVER['DOCUMENT_ROOT']."/datascrapping";
		//build a unique path with every request to store 
		//the info per user with custom func. 
		$path = $dir;
		//login form action url
		//$url="https://fmap.citizensfla.com/login"; 
	    echo $url="https://cag.citizensfla.com/cag/login";
		$postinfo = "j_username=".$username."&j_password=".$password."&_event_=login&systemId=FMAP";
		$cookie_file_path = $path."/cookie.txt";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		echo "111";
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		//curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:9050');
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
		//set the cookie the site has for certain features, this is optional
		curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
		//curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		echo "112";
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		echo "113";
		echo "<br>";
		print_r($ch);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		$html23=curl_exec($ch);	
		print_r($html23);
		echo $html23;
		
		// exit;
		$url="https://fmap.citizensfla.com/fmap/searchcriteria.do"; 
		$postinfo = "searchId=$search_id&_event_=runSearch";
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:9150');
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		$html = curl_exec($ch);
		echo "<br>114";
		$html1=str_replace("<br>","<br> ",$html);
		curl_close($ch);
		print_r($html);
		$htmlContent = $html1;
		//$DOM = new DOMDocument();
		//$DOM->loadHTML($htmlContent);
		//$result = $DOM->getElementById('property');
    	//$result->nodeValue;	
		$xml = new DOMDocument();
		$xml->validateOnParse = true;
		$xml->loadHTML($htmlContent);
		$xpath = new DOMXPath($xml);
		$table =$xpath->query("//*[@id='property']")->item(0);
		// for printing the whole html table just type:
		//print $xml->saveXML($table); 
		$rows = $table->getElementsByTagName("tr");	
		echo "<table border='2'>";
		echo "<tr><th>#</th><th>Property Address</th><th>Consumer Information</th><th>Need By</th><th>Amount($)</th><th>Property Type</th><th>Year</th><th>Construction</th></tr>";
		$i=0;
		foreach($rows as $row) {
			echo "<tr>";		
			$cells = $row -> getElementsByTagName('td');
			$SQL = "INSERT INTO scrapped_data (serial_number,property_address,consumer,need_by,amount,property_type,year,construction) VALUES(";
		  	
			foreach($cells as $cell) {
			    
				if(!empty($cell->nodeValue) || $i==3){
				    if($i==2){
				        
				       $mynew=explode(",",$cell->nodeValue); 
				       $mynew1=explode(" ",$mynew[0]);
				       print_r($mynew1);
				      $mynew = array_slice($mynew1, -1); 
				      echo "sdasdasd====".$lastEl = array_pop($mynew1);
				      echo "<br><br>";
				      
				    }
				 if($i!=9){	
			        echo "<td>".$cell->nodeValue."</td>";
				    $SQL.='"'.$cell->nodeValue.'"';
					if($i!=8){
						$SQL.=",";
						
					}
					
			     }
				}
			   $i++;
			}
			$SQL.=")";
			echo $SQL;
			// checking whether there is any data in the scrapped_data .		
			//$result = mysqli_query($db_handle, $SQL);		
			unset($i);
			echo "</tr>";
			 
		}
		
		echo "</table>";
		mysqli_close($db_handle);
		
		
		
	
?>