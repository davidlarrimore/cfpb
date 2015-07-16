<?php

    $username = "cfpb";
    $password = "cfpb";
    $hostname = "localhost";
    $database = "cfpb";

    $conn = mysql_connect($hostname, $username, $password)

    or die("Connecting to MySQL failed");

    mysql_select_db($database, $conn)

    or die("Selecting MySQL database failed");



    $geographyDataFile = "./data/g20135us-baked.csv";
    $acsEstimateDataFile = "./data/e20135us0015000-baked.csv";
    $acsMarginOfErrorDataFile = "./data/m20135us0015000-baked.csv";
    $consumerComplaintDataFile = "./data/Consumer_Complaints.csv";


    //If Posted, we load
    if ( !empty($_POST) ) {
        //PART 1: Query to load geography table
        $sql = "LOAD DATA LOCAL INFILE '".$geographyDataFile."' INTO TABLE geography FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 

        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.geography"; 
        $result = mysql_query($sql, $conn); 

        $geographyTableRowCount = mysql_fetch_array( $result );

        if ($geographyTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$geographyDataFile."' INTO TABLE geography FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }


        //PART 2: Query to load Estimates table
        $sql = "LOAD DATA LOCAL INFILE '".$acsEstimateDataFile."' INTO TABLE acs_estimate FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 

        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.acs_estimate"; 
        $result = mysql_query($sql, $conn); 

        $acsEstimateTableRowCount = mysql_fetch_array( $result );

        if ($acsEstimateTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$acsEstimateDataFile."' INTO TABLE acs_estimate FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
        }




        //PART 4: Query to load consumer complaints table
        $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r' IGNORE 1 LINES"; 
        mysql_query($sql) or die(mysql_error()); 

        //lets check to see if data was loaded, noticed difference between windows/osx/linux
        $sql = "SELECT count(*) as `count` FROM cfpb.consumer_complaint"; 
        $result = mysql_query($sql, $conn); 

        $geographyTableRowCount = mysql_fetch_array( $result );

        if ($geographyTableRowCount = 0) {
            $sql = "LOAD DATA LOCAL INFILE '".$consumerComplaintDataFile."' INTO TABLE consumer_complaint FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES"; 
            mysql_query($sql) or die(mysql_error()); 
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
          <img style="padding:10px;" alt="Brand" src="./images/logo.jpg">
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-left" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><p class="navbar-text">David Larrimore Demo Site</p></li>
            <li><p class="navbar-text">/</li>
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
            <div class="row">
            <div class="col-md-12"><div><h1>Administration</h1></div></div>
        </div>
        <div class="row">
            <div class="col-md-12"><h3>Table Load Status</h3></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <head>
                        <tr>
                            <td>Table Name</td>
                            <td>Exists?</td>
                            <td>Loaded?</td>
                            <td>Number of Records</td>
                        </tr>                                                                             
                    </head>
                    <body>
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
                    </body>
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