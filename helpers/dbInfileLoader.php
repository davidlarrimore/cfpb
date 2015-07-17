<?php
/* Unfortunatley, I have not found a good way to load data using mysqli...so i'm forced to use the older mysql libraries
*/

	function loadInfileData(){
		
		include './helpers/dbconfig.php';
		
	    $geographyDataFile = "./data/g20135us-baked.csv";
	    $acsEstimateDataFile = "./data/e20135us0015000-baked.csv";
	    $acsMarginOfErrorDataFile = "./data/m20135us0015000-baked.csv";
	    $consumerComplaintDataFile = "./data/Consumer_Complaints.csv";

	    $conn = mysql_connect($hostname, $username, $password,false,128) or die("Connecting to MySQL failed");
	    mysql_select_db($database, $conn) or die("Selecting MySQL database failed");


		//PART 2: Query to load geography table
        $sql = "LOAD DATA LOCAL INFILE '".$geographyDataFile."' INTO TABLE cfpb.geography FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 
        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.geography"; 
        $result = mysql_query($sql, $conn); 
        $geographyTableRowCount = mysql_fetch_array( $result );
        if ($geographyTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$geographyDataFile."' INTO TABLE cfpb.geography FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }



        //PART 3: Query to load Estimates table
        $sql = "LOAD DATA LOCAL INFILE '".$acsEstimateDataFile."' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 
        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.acs_estimate"; 
        $result = mysql_query($sql, $conn); 
        $acsEstimateTableRowCount = mysql_fetch_array( $result );
        if ($acsEstimateTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$acsEstimateDataFile."' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }


        //PART 4: Query to load Margin of Error
        $sql = "LOAD DATA LOCAL INFILE '".$acsMarginOfErrorDataFile."' INTO TABLE cfpb.acs_margin_of_error FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 
        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.acs_estimate"; 
        $result = mysql_query($sql, $conn); 
        $acsEstimateTableRowCount = mysql_fetch_array( $result );
        if ($acsEstimateTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$acsMarginOfErrorDataFile."' INTO TABLE cfpb.acs_margin_of_error FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }


        //PART 5: Query to load consumer complaints table
        $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 
        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.consumer_complaint"; 
        $result = mysql_query($sql, $conn); 
        $consumerComplaintTableRowCount = mysql_fetch_array( $result );
        if ($consumerComplaintTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }

	}

?>