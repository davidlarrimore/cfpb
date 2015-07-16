<?php
    $mysqlError = false;
    $username = "cfpb";
    $password = "cfpb";
    $hostname = "localhost";
    $database = "cfpb";

    $conn = mysql_connect($hostname, $username, $password) or die("Connecting to MySQL failed");
    mysql_select_db($database, $conn) or die("Selecting MySQL database failed");

    $db = new mysqli($hostname, $username, $password, $database);

    /* check connection */
    if ($db->connect_errno) {
        $mysqlError = true;
        $mysqlErrorMessage = $db->connect_error;
        exit();
    }

    $geographyDataFile = "./data/g20135us-baked.csv";
    $acsEstimateDataFile = "./data/e20135us0015000-baked.csv";
    $acsMarginOfErrorDataFile = "./data/m20135us0015000-baked.csv";
    $consumerComplaintDataFile = "./data/Consumer_Complaints.csv";


    //If Posted, we load
    if ( !empty($_POST) && !$mysqlError) {

        //Part 0: drop tables
        if (mysqli_query($db, "DROP TABLE IF EXISTS cfpb.geography;") === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not drop geography table";           
        }

        if (mysqli_query($db, "DROP TABLE IF EXISTS cfpb.acs_estimate;") === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not drop acs_estimate table";           
        }

        if (mysqli_query($db, "DROP TABLE IF EXISTS cfpb.acs_margin_of_error;") === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not drop acs_margin_of_error table";           
        }

        if (mysqli_query($db, "DROP TABLE IF EXISTS cfpb.consumer_complaint;") === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not drop consumer_complaint table";           
        }


        //Part 1: Create Tables
        $sql = file_get_contents("./ddl/create_geography.sql");
        if (mysqli_query($db, $sql) === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not create geography table";           
        }


        $sql = file_get_contents("./ddl/create_acs_estimate.sql");
        if (mysqli_query($db, $sql) === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not create acs_estimate table";           
        }


        $sql = file_get_contents("./ddl/create_acs_margin_of_error.sql");
        if (mysqli_query($db, $sql) === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not create acs_margin_of_error table";           
        }       


        $sql = file_get_contents("./ddl/create_consumer_complaint.sql");
        if (mysqli_query($db, $sql) === TRUE) {
        }else{
            $mysqlError = true;
            $mysqlErrorMessage = "Could not create consumer_complaint table";           
        }  



        if(!$mysqlError){

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
                $sql = "LOAD DATA LOCAL INFILE '".$acsEstimateDataFile."' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES (FILEID,FILETYPE,STUSAB,CHARITER,SEQUENCE,LOGRECNO,GEOID,Geography Name,B06010_001,B06010_002,B06010_003,B06010_004,B06010_005,B06010_006,B06010_007,B06010_008,B06010_009,B06010_010,B06010_011,B06010_012,B06010_013,B06010_014,B06010_015,B06010_016,B06010_017,B06010_018,B06010_019,B06010_020,B06010_021,B06010_022,B06010_023,B06010_024,B06010_025,B06010_026,B06010_027,B06010_028,B06010_029,B06010_030,B06010_031,B06010_032,B06010_033,B06010_034,B06010_035,B06010_036,B06010_037,B06010_038,B06010_039,B06010_040,B06010_041,B06010_042,B06010_043,B06010_044,B06010_045,B06010_046,B06010_047,B06010_048,B06010_049,B06010_050,B06010_051,B06010_052,B06010_053,B06010_054,B06010_055,B06010PR_001,B06010PR_002,B06010PR_003,B06010PR_004,B06010PR_005,B06010PR_006,B06010PR_007,B06010PR_008,B06010PR_009,B06010PR_010,B06010PR_011,B06010PR_012,B06010PR_013,B06010PR_014,B06010PR_015,B06010PR_016,B06010PR_017,B06010PR_018,B06010PR_019,B06010PR_020,B06010PR_021,B06010PR_022,B06010PR_023,B06010PR_024,B06010PR_025,B06010PR_026,B06010PR_027,B06010PR_028,B06010PR_029,B06010PR_030,B06010PR_031,B06010PR_032,B06010PR_033,B06010PR_034,B06010PR_035,B06010PR_036,B06010PR_037,B06010PR_038,B06010PR_039,B06010PR_040,B06010PR_041,B06010PR_042,B06010PR_043,B06010PR_044,B06010PR_045,B06010PR_046,B06010PR_047,B06010PR_048,B06010PR_049,B06010PR_050,B06010PR_051,B06010PR_052,B06010PR_053,B06010PR_054,B06010PR_055,B06011_001,B06011_002,B06011_003,B06011_004,B06011_005,B06011PR_001,B06011PR_002,B06011PR_003,B06011PR_004,B06011PR_005,B06012_001,B06012_002,B06012_003,B06012_004,B06012_005,B06012_006,B06012_007,B06012_008,B06012_009,B06012_010,B06012_011,B06012_012,B06012_013,B06012_014,B06012_015,B06012_016,B06012_017,B06012_018,B06012_019,B06012_020,B06012PR_001,B06012PR_002,B06012PR_003,B06012PR_004,B06012PR_005,B06012PR_006,B06012PR_007,B06012PR_008,B06012PR_009,B06012PR_010,B06012PR_011,B06012PR_012,B06012PR_013,B06012PR_014,B06012PR_015,B06012PR_016,B06012PR_017,B06012PR_018,B06012PR_019,B06012PR_020)"; 
                mysql_query($sql) or die(mysql_error()); 
            }




            //PART 4: Query to load consumer complaints table
            $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 

            //lets check to see if data was loaded, noticed difference between windows/osx/linux
            $sql = "SELECT count(*) as `count` FROM cfpb.consumer_complaint"; 
            $result = mysql_query($sql, $conn); 

            $geographyTableRowCount = mysql_fetch_array( $result );

            if ($geographyTableRowCount = 0) {
                $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
                mysql_query($sql) or die(mysql_error()); 
            }

            
        }


    }


    /*
    * GETTING STATISTICS
    *
    * This process includes checking each table to see if the table exists. If so, it gets statistices
    */


    //GEOGRAPHY TABLE
    $val = mysql_query('select 1 from cfpb.geography LIMIT 1');
    if($val !== FALSE)
    {
        //GETTING STATISTICS
        //Get Geography Table Row Count
        $sql = "SELECT count(*) as `count` FROM cfpb.geography"; 
        $result = mysql_query($sql, $conn); 
        $geographyTableRowCount = mysql_fetch_array($result)['count'];
        $geographyTableCheck = true;
    }
    else
    {
        $geographyTableRowCount = 0;
        $geographyTableCheck = false;
    }


    //ACS_ESTIMATE TABLE
    $val = mysql_query('select 1 from cfpb.acs_estimate LIMIT 1');
    if($val !== FALSE)
    {
        //GETTING STATISTICS
        //Get Geography Table Row Count
        $sql = "SELECT count(*) as `count` FROM cfpb.acs_estimate"; 
        $result = mysql_query($sql, $conn); 
        $acsEstimateTableRowCount = mysql_fetch_array($result)['count'];
        $acsEstimateTableCheck = true;
    }
    else
    {
        $acsEstimateTableRowCount = 0;
        $acsEstimateTableCheck = false;
    }

    //ACS_MARGIN_OF_ERROR TABLE
    $val = mysql_query('select 1 from cfpb.acs_margin_of_error LIMIT 1');
    if($val !== FALSE)
    {
        //GETTING STATISTICS
        //Get Geography Table Row Count
        $sql = "SELECT count(*) as `count` FROM cfpb.acs_margin_of_error"; 
        $result = mysql_query($sql, $conn); 
        $acsMarginOfErrorTableRowCount = mysql_fetch_array($result)['count'];
        $acsMarginOfErrorTableCheck = true;
    }
    else
    {
        $acsMarginOfErrorTableRowCount = 0;
        $acsMarginOfErrorTableCheck = false;
    }

    //CONSUMER_COMPLAINTS TABLE
    $val = mysql_query('select 1 from cfpb.consumer_complaint LIMIT 1');
    if($val !== FALSE)
    {
        //GETTING STATISTICS
        //Get Geography Table Row Count
        $sql = "SELECT count(*) as `count` FROM cfpb.consumer_complaint"; 
        $result = mysql_query($sql, $conn); 
        $consumerComplaintTableRowCount = mysql_fetch_array($result)['count'];
        $consumerComplaintTableCheck = true;
    }
    else
    {
        $consumerComplaintTableRowCount = 0;
        $consumerComplaintTableCheck = false;
    }

    mysql_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>MixFin</title>

      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">



    <style>
        body {
           background:AliceBlue !important;
        }
        .container {
            background:white !important;
        }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">David Larrimore Demo Site</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-left" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="mailto:davidlarrimore@gmail.com">davidlarrimore@gmail.com</a></li>
            <li><p class="navbar-text">/</li>
            <li><p class="navbar-text">(410) 598-6569</p></li>
          </ul>
        </div>
         <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="./">Dashboard</a></li>
            <li class="active"><a href="./">Administration <span class="sr-only">(current)</span></a></li>
          </ul>         
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container">
        <?php if($mysqlError){ ?>
                <div class="row">&nbsp;</div>
                <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php echo $mysqlErrorMessage;?>
                    </div>
                </div>
            </div>
        <?php } else if (!$mysqlError && !empty($_POST)) { ?>
                <div class="row">&nbsp;</div>
                <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Load Successful
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12"><div><h1>Administration</h1></div></div>
        </div>
        <div class="row">
            <div class="col-md-12"><h3>Configuration Status</h3></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Table Name</th>
                            <th>Exists?</th>
                            <th>Loaded?</th>
                            <th>Number of Records</th>
                        </tr>                                                                             
                    </thead>
                    <tbody>
                        <tr>
                            <td>geographies</td>
                            <td class="text-center"><span class="label <?php echo ($geographyTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($geographyTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($geographyTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($geographyTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $geographyTableRowCount;?></td>
                        </tr>
                         <tr>
                            <td>acs_estimates</td>
                            <td class="text-center"><span class="label <?php echo ($acsEstimateTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($acsEstimateTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($acsEstimateTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($acsEstimateTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $acsEstimateTableRowCount;?></td>
                        </tr>   
                         <tr>
                            <td>acs_margin_of_error</td>
                            <td class="text-center"><span class="label <?php echo ($acsMarginOfErrorTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($acsMarginOfErrorTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($acsMarginOfErrorTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($acsMarginOfErrorTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $acsMarginOfErrorTableRowCount;?></td>
                        </tr>    
                         <tr>
                            <td>consumer_complaint</td>
                            <td class="text-center"><span class="label <?php echo ($consumerComplaintTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($consumerComplaintTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($consumerComplaintTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($consumerComplaintTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $consumerComplaintTableRowCount;?></td>
                        </tr>                                                                       
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <form method="post">
                    <button type="sumbit" style="font-size:200%;" class="btn btn-md btn-block btn-primary">Load Tables and Data</a>
                    <input type="hidden" name="load" id="load" value="load"/>
                </button>
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- Chart.js -->
    <script src="{% static "dashboard/js/Chart.min.js" %}"></script>

    <!-- D3.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js" charset="utf-8"></script>
  </body>
</html>