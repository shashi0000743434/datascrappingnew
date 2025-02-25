<?php
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/simple_html_dom.php';	
$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db_found = mysqli_select_db($db_handle, DB_DATABASE);
echo "<table border='2'>";
echo "<tr><th>#</th><th>Property Address</th><th>Consumer Information</th><th>Need By</th><th>Amount($)</th><th>Property Type</th><th>Year</th><th>Construction</th><th></th></tr>";
$sqlproperty_refreshedQuery="select * from scrapped_data order by serial_number ASC";
$exeproperty_refreshedQuery=mysqli_query($db_handle,$sqlproperty_refreshedQuery);
$sno=1;
if(mysqli_num_rows($exeproperty_refreshedQuery)>0){
	while ($db_field = mysqli_fetch_assoc($exeproperty_refreshedQuery)) {
		echo "<tr>";
		//echo "<td>".$db_field['serial_number']."</td>";
		echo "<td>".$sno."</td>";
		echo "<td>".$db_field['property_address']."</td>";
		echo "<td>".str_replace("<none&gt","",$db_field['consumer'])."</td>";
		echo "<td>".$db_field['need_by']."</td>";
		echo "<td>".$db_field['amount']."</td>";
		echo "<td>".$db_field['property_type']."</td>";
		echo "<td>".$db_field['year']."</td>";
		echo "<td>".$db_field['construction']."</td>";
		echo '<td><a target="_blank" href="detailed_data.php?id='.$db_field['property_id'].'&search_id='.$db_field['search_id'].'"><img src="img/details.gif" alt="Details" title="View property details"></a></td>';				
		echo "</tr>";
        $sno++;
	}

}
else{
	echo "<tr>";
	echo "<td colspan='9'><center>No data found</center></td>";
	echo "</tr>";
}
echo "</table>";		   


?>