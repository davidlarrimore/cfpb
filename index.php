
<?php
    $mysqlError = false;
    $username = "cfpb";
    $password = "cfpb";
    $hostname = "localhost";
    $database = "cfpb";

    $db = new mysqli($hostname, $username, $password, $database);

    /* check connection */
    if ($db->connect_errno) {
        $mysqlError = true;
        $mysqlErrorMessage = $db->connect_error;
        exit();
    }

    $DBConfig = true;

    //Code added to check to see if site is setup
    $result = $db->query("SHOW TABLES LIKE 'geography';");
    if($result->num_rows == 0) $DBConfig = false;
    $result->close();

    $result = $db->query("SHOW TABLES LIKE 'acs_estimate';");
    if($result->num_rows == 0) $DBConfig = false;
    $result->close();

    $result = $db->query("SHOW TABLES LIKE 'acs_margin_of_error';");
    if($result->num_rows == 0) $DBConfig = false;
    $result->close();


    $result = $db->query("SHOW TABLES LIKE 'consumer_complaint';");
    if($result->num_rows == 0) $DBConfig = false;
    $result->close();


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
            <li class="active"><a href="./">Dashboard</a></li>
            <li><a href="./admin.php">Administration <span class="sr-only">(current)</span></a></li>
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


        <?php if(!$DBConfig){ ?>
                <div class="row">&nbsp;</div>
                <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger " role="alert">
                        Database is not configured, please go to <a href="./admin.php">Admin Page</a>.
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron text-center">
                  <h1>CFPB Complaint Dashboard</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-heading">States with the Higest number of complaints</div>
                  <div class="panel-body" id="number_of_complaints"></div>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-heading">Complaints over Time</div>
                  <div class="panel-body" id="chart_div"></div>
                </div>
            </div>
        </div><!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Data Table showing ratio of population to Complaints</div>
                    <div class="panel-body" id="table_div"></div>
                </div>
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- Chart.js -->
    <script src="./js/Chart.min.js"></script>

    <!-- D3.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js" charset="utf-8"></script>

    <!-- Google Charts API -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1.1", {packages:['corechart', 'bar', 'table', 'line']});
        google.setOnLoadCallback(runAll);

      function runAll(){
        drawMultSeries();
        drawBasic();
        drawTable();
      }


      function drawMultSeries() {
        $.getJSON( "./api.php?query=1", function(json) {

            // create an array of data
            var dataArray = [];
            var tmp = [];
            for (x in json[0]) {
                tmp.push(x);
            }
            dataArray.push(tmp);
              
            for (var i = 0; i < json.length; i++) {
                tmp = [];
                for (var j = 0; j < dataArray[0].length; j++) {
                    thiVal = json[i][dataArray[0][j]];
                    //console.log(thiVal);
                    if(!isNaN(parseFloat(thiVal)) && isFinite(thiVal)){
                      tmp.push(parseFloat(thiVal)); 
                    }else{
                        tmp.push(thiVal);
                    }
                }
                dataArray.push(tmp);
            }


              var data = google.visualization.arrayToDataTable(dataArray);

              var options = {
                title: 'States with the largest number of complaints',
                chartArea: {},
                hAxis: {
                  title: 'Total Complaints',
                  minValue: 0
                },
                bars: 'horizontal',
                vAxis: {
                  title: 'State'
                }
              };
              var material = new google.charts.Bar(document.getElementById('number_of_complaints'));
              material.draw(data, options);
        });

      }



    function drawTable() {
        $.getJSON( "./api.php?query=3", function(json) {

                // create an array of data
                var dataArray = [];
                var tmp = [];
                var tmp2 = [];
                for (x in json[0]) {
                    tmp.push(x);
                }
                tmp2.push(tmp);
                  
                for (var i = 0; i < json.length; i++) {
                    tmp = [];
                    for (var j = 0; j < tmp2[0].length; j++) {
                        thiVal = json[i][tmp2[0][j]];
                        //console.log(thiVal);
                        if(!isNaN(parseFloat(thiVal)) && isFinite(thiVal)){
                          tmp.push(parseFloat(thiVal)); 
                        }else{
                            tmp.push(thiVal);
                        }
                    }
                    dataArray.push(tmp);
                }


                var data = new google.visualization.DataTable();
                data.addColumn('string', 'State');
                data.addColumn('number', 'Complaints');
                data.addColumn('number', 'Population');
                data.addColumn('number', 'Complaint Ratio');
                data.addRows(dataArray);

                var table = new google.visualization.Table(document.getElementById('table_div'));

                table.draw(data, {showRowNumber: false, width: '100%', height: '100%'});
                    });

      }





    function drawBasic() {
        $.getJSON( "./api.php?query=2", function(json) {

             // create an array of data
            var dataArray = [];
            var tmp = [];
            for (x in json[0]) {
                tmp.push(x);
            }
            dataArray.push(tmp);
              
            for (var i = 0; i < json.length; i++) {
                tmp = [];
                for (var j = 0; j < dataArray[0].length; j++) {
                    thiVal = json[i][dataArray[0][j]];
                    //console.log(thiVal);
                    if(!isNaN(parseFloat(thiVal)) && isFinite(thiVal)){
                      tmp.push(parseFloat(thiVal)); 
                    }else{
                        tmp.push(thiVal);
                    }
                }
                dataArray.push(tmp);
            }


                  var data = google.visualization.arrayToDataTable(dataArray);

                  var options = {
                  curveType: 'function',
                  legend: { position: 'bottom' }
                };

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                  chart.draw(data, options);
          });
    }






    </script>
  </body>
</html>