<?php
    include './helpers/dbconfig.php'; 
    include './helpers/dbHelper.php';
    include './helpers/dbInfileLoader.php';

    mysqli_report(MYSQLI_REPORT_STRICT);


    $mysqlError = false;
    $mysqlErrorMessage = "";  
    $consumerComplaintTableRowCount = 0;
    $consumerComplaintTableCheck = false;
    $acsEstimateTableRowCount = 0;
    $acsEstimateTableCheck = false;
    $acsMarginOfErrorTableRowCount = 0;
    $acsMarginOfErrorTableCheck = false;
    $geographyTableRowCount = 0;
    $geographyTableCheck = false;
    $consumerACSUSARatioTableRowCount = 0;
    $consumerACSUSARatioTableCheck = false;
    $consumerComplaintIndexCheck = false;
    $geographyIndexCheck = false;


    try {
         $db = new mysqli($hostname, $username, $password, $database);

        /* check connection */
        if ($db->connect_error) {
            $mysqlError = true;
            $mysqlErrorMessage = $db->connect_error;
            //exit();
        }
    } catch (Exception $e ) {
        $mysqlError = true;
        $mysqlErrorMessage = "Could not connect to database";
         //exit;
    }



    if(!$mysqlError){
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

            if (mysqli_query($db, "DROP TABLE IF EXISTS cfpb.consumer_acs_usa_ratio;") === TRUE) {
            }else{
                $mysqlError = true;
                $mysqlErrorMessage = "Could not drop consumer_acs_usa_ratio table";           
            }


            //Part 1: Create Tables
            $sql = file_get_contents("./ddl/create_geography.sql");
            if (mysqli_query($db, $sql) === TRUE) {
            }else{
                $mysqlError = true;
                $mysqlErrorMessage = "Could not create geography table";           
            }


            $sql = file_get_contents("./ddl/create_geography_index.sql");
            if (mysqli_query($db, $sql) === TRUE) {            
            }else{
                $mysqlError = true;
                $mysqlErrorMessage = "Could not create geography table index";       
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



            $sql = file_get_contents("./ddl/create_consumer_complaint_index.sql");
            if (mysqli_query($db, $sql) === TRUE) {            
            }else{
                $mysqlError = true;
                $mysqlErrorMessage = "Could not create consumer_complaint table index";       
            }    



            $sql = file_get_contents("./ddl/create_consumer_acs_usa_ratio.sql");
            if (mysqli_query($db, $sql) === TRUE) {
            }else{
                $mysqlError = true;
                $mysqlErrorMessage = "Could not create consumer_acs_usa_ratio table";           
            }  




            if(!$mysqlError){
                loadInfileData();


                //PART 6: Aggregated Tables
                if (mysqli_query($db, file_get_contents("./queries/load_consumer_acs_usa_ratio.sql")) === TRUE) {
                }else{
                    $mysqlError = true;
                    $mysqlErrorMessage = "Could not load consumer_acs_usa_ratio table";           
                }

            }


        }


        /*
        * GETTING STATISTICS
        *
        * This process includes checking each table to see if the table exists. If so, it gets statistices
        */


        //GEOGRAPHY TABLE
        $geographyTableRowCount = 0;
        $geographyTableCheck = false;
        if(validateIfTableExists($db, "geography")){
            $geographyTableCheck = true;
            $rowCount = getRowCount($db, "geography");
            if($rowCount)
                $geographyTableRowCount = $rowCount;
        }



        //ACS_ESTIMATE TABLE
        $acsEstimateTableRowCount = 0;
        $acsEstimateTableCheck = false;
        if(validateIfTableExists($db, "acs_estimate")){
            $acsEstimateTableCheck = true;
            $rowCount = getRowCount($db, "acs_estimate");
            if($rowCount)
                $acsEstimateTableRowCount = $rowCount;
        }



        //ACS_MARGIN_OF_ERROR TABLE
        $acsMarginOfErrorTableRowCount = 0;
        $acsMarginOfErrorTableCheck = false;
        if(validateIfTableExists($db, "acs_margin_of_error")){
            $acsMarginOfErrorTableCheck = true;
            $rowCount = getRowCount($db, "acs_margin_of_error");
            if($rowCount)
                $acsMarginOfErrorTableRowCount = $rowCount;
        }    


        //CONSUMER_COMPLAINTS TABLE
        $consumerComplaintTableRowCount = 0;
        $consumerComplaintTableCheck = false;
        if(validateIfTableExists($db, "consumer_complaint")){
            $consumerComplaintTableCheck = true;
            $rowCount = getRowCount($db, "consumer_complaint");
            if($rowCount)
                $consumerComplaintTableRowCount = $rowCount;
        }   




        //Checking for geo join index
        $geographyIndexCheck = false;
        if(validateIfTableIndexExists($db, "geography", "geo_join_index")){
            $geographyIndexCheck = true;
        }  

        //Checking for cc join index
        $consumerComplaintIndexCheck = false;
        if(validateIfTableIndexExists($db, "consumer_complaint", "cc_join_index")){
            $consumerComplaintIndexCheck = true;
        }  


        //AGGREGATED TABLE TABLE
        $consumerACSUSARatioTableRowCount = 0;
        $consumerACSUSARatioTableCheck = false;
        if(validateIfTableExists($db, "consumer_acs_usa_ratio")){
            $consumerACSUSARatioTableCheck = true;
            $rowCount = getRowCount($db, "consumer_acs_usa_ratio");
            if($rowCount)
                $consumerACSUSARatioTableRowCount = $rowCount;
        }   

    }
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

    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65203727-1', 'auto');
  ga('send', 'pageview');

</script>
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
          <a class="navbar-brand" href="./">David Larrimore Demo Site</a>
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
            <li class="active"><a href="./admin.php">Administration <span class="sr-only">(current)</span></a></li>
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
            <div class="col-md-12"><h3>Table Status</h3></div>
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
                            <th>Indexes</th>
                        </tr>                                                                             
                    </thead>
                    <tbody>
                        <tr>
                            <td>geographies</td>
                            <td class="text-center"><span class="label <?php echo ($geographyTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($geographyTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($geographyTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($geographyTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $geographyTableRowCount;?></td>
                            <td class="text-center"><span class="label <?php echo ($geographyIndexCheck == false? 'label-danger': 'label-success');?>"><?php echo ($geographyIndexCheck == false? 'No': 'Yes');?></span></td>
                        </tr>
                         <tr>
                            <td>acs_estimates</td>
                            <td class="text-center"><span class="label <?php echo ($acsEstimateTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($acsEstimateTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($acsEstimateTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($acsEstimateTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $acsEstimateTableRowCount;?></td>
                            <td class="text-center"><span class="label label-info">N/A</span></td>
                        </tr>   
                         <tr>
                            <td>acs_margin_of_error</td>
                            <td class="text-center"><span class="label <?php echo ($acsMarginOfErrorTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($acsMarginOfErrorTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($acsMarginOfErrorTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($acsMarginOfErrorTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $acsMarginOfErrorTableRowCount;?></td>
                            <td class="text-center"><span class="label label-info">N/A</span></td>
                        </tr>    
                         <tr>
                            <td>consumer_complaint</td>
                            <td class="text-center"><span class="label <?php echo ($consumerComplaintTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($consumerComplaintTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($consumerComplaintTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($consumerComplaintTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $consumerComplaintTableRowCount;?></td>
                            <td class="text-center"><span class="label <?php echo ($consumerComplaintIndexCheck == false? 'label-danger': 'label-success');?>"><?php echo ($consumerComplaintIndexCheck == false? 'No': 'Yes');?></span></td>
                        </tr>       
                         <tr>
                            <td>consumer_acs_usa_ratio</td>
                            <td class="text-center"><span class="label <?php echo ($consumerACSUSARatioTableCheck == false? 'label-danger': 'label-success');?>"><?php echo ($consumerACSUSARatioTableCheck == false? 'No': 'Yes');?></span></td>
                            <td class="text-center"><span class="label <?php echo ($consumerACSUSARatioTableRowCount == 0? 'label-danger': 'label-success');?>"><?php echo ($consumerACSUSARatioTableRowCount == 0? 'No': 'Yes');?></span></td>
                            <td><?php echo $consumerACSUSARatioTableRowCount;?></td>
                            <td class="text-center"><span class="label label-info">N/A</span></td>
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