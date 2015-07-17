<?php 
/* 
* This is a simple API to get data from the database....
* add queries to the getQuery function and they become accessible 
* to other parts of the application.
*
* Queries are fed from the .sql files in the queries folder
*
*/
    include './helpers/dbHelper.php';
    mysqli_report(MYSQLI_REPORT_STRICT);


	$db = new mysqli($hostname, $username, $password, $database);

	if (isset($_GET["query"]) && !empty($_GET["query"])) {
		$myArray = array();
		if ($result = $db->query(getQuery($_GET["query"]))) {

		    while($row = $result->fetch_array(MYSQL_ASSOC)) {
		    	$myArray[] = $row;
		    }
		    header('Content-Type: application/json');
		    echo json_encode($myArray);
		}
		$result->close();
	}


	function getQuery ($i){
	    return file_get_contents("./queries/query".$i.".sql");
	}

?>

