<?php 
/* 
* This is a simple API to get data from the database....
* add queries to the getQuery function and they become accessible 
* to other parts of the application.
*/

    $username = "cfpb";
    $password = "cfpb";
    $hostname = "localhost";
    $database = "cfpb";


	$db = new mysqli($hostname, $username, $password, $database);

	if (isset($_GET["query"]) && !empty($_GET["query"])) {
		$myArray = array();
		if ($result = $db->query(getQuery($_GET["query"]))) {

		    while($row = $result->fetch_array(MYSQL_ASSOC)) {
		            $myArray[] = $row;
		    }
		    echo json_encode($myArray);
		}
		$result->close();
	}


function getQuery ($i){
	$query = "";
	switch ($i) {
    case 1:
        $query = "SELECT zip_code, count(*) as number_of_complaints, company FROM consumer_complaint WHERE zip_code IS NOT NULL AND company IS NOT NULL AND zip_code <> ' ' GROUP BY zip_code, company;;";
        break;
    case 2:
        $query = "SELECT state, count(*) as 'Number of Complaints' FROM consumer_complaint WHERE state IS NOT NULL AND state <> ' ' GROUP BY state order by count(*) desc LIMIT 10;";
        break;
    case 3:
        $query = "select * from cfpb.geography LIMIT 1;";
        break;
    }
	header('Content-Type: application/json');
    return $query;
}



?>

