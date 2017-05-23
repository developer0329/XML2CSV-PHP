<?php
    include 'db_config.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM properties";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title></title>
		<meta name="description" content=""/>

		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/jquery.timepicker.css" />
		<link rel="stylesheet" href="lib/bootstrap-datepicker.css" />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="lib/jquery.timepicker.js"></script>
		<script src="lib/bootstrap-datepicker.js"></script>
		<script src="http://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
		<script src="http://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>

  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
          <div class="navbar-header">
              <a class="navbar-brand" href="#">XML2CSV</a>
          </div>
          <ul class="nav navbar-nav">
              <li ><a href="file_read.php">Import XML</a></li>
              <li class="active"><a href="#">Export CSV</a></li>
          </ul>
      </div>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <ul id="datepairExample">
            <li>
              <span>Machine ID: </span>
              <select id="SiteID" class="input-sm">
                <option value="">Machine SN</option>
                <?php
                  if ($result->num_rows > 0) {
                      // output data of each row
                      while($row = $result->fetch_assoc()) {
                          echo '<option value="' . $row['id'] . '">' . $row['site_id'] . '</option>';
                      }
                  } else {
                      echo '0 results';
                  }

                  $conn->close();
                ?>
              </select>
            </li>
            <li><span>Start Date: </span><input id="startDate" type="text" class="input-sm date start" /></li>
            <li><span>Start Time: </span><input id="startTime" type="text" class="input-sm time start" /></li>
            <li><span>End Date: </span><input id="endDate" type="text" class="input-sm date end" /></li>
            <li><span>End Time: </span><input id="endTime" type="text" class="input-sm time end" /></li>
          </ul>
          <button type="button" class="btn btn-default"
            style="margin-left:40px;" onclick="makeReport()">Make Report</button>
        </div>
        <div class="col-md-9">
            <div class="table-responsive" style="overflow: auto; height:300px;">
              <table class="table">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>Date</th>
                          <th>Start Time</th>
                          <th>End Time</th>
                          <th>Enters</th>
                          <th>Exits</th>
                          <th>Status</th>
                      </tr>
                  </thead>
                  <tbody id="result">
                  </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
    <script>
        $(function() {
            $('#datepairExample .time').timepicker({
                'showDuration': true,
                'timeFormat': 'H:i:s'
            });
            $('#datepairExample .date').datepicker({
                'format': 'yyyy-mm-dd',
                'autoclose': true
            });
            $('#datepairExample').datepair();
        });

        function makeReport()
        {
          var mID = $("#SiteID").val();
          var sD = $("#startDate").val();
          var sT = $("#startTime").val();
          var eD = $("#endDate").val();
          var eT = $("#endTime").val();

          if(mID != '' && sD != '' && sT != '' && eD != '' && eT != '')
          {

              $.post("report_data.php",
              {
                  SiteID: mID,
                  startTime: sT,
                  startDate: sD,
                  endTime: eT,
                  endDate: eD
              },
              function(data, status){
                  //alert("Data: " + data + "\nStatus: " + status);
                  if(status == "success" && data !== "0")
                  {
                    var arr = JSON.parse(data);
                    var i;
                    var out = "";

                    for(i = 0; i < arr.length; i++) {
                        out += "<tr><td>" +
                        (i + 1) +
                        "</td><td>" +
                        arr[i].Date +
                        "</td><td>" +
                        arr[i].StartTime +
                        "</td><td>" +
                        arr[i].EndTime +
                        "</td><td>" +
                        arr[i].Enters +
                        "</td><td>" +
                        arr[i].Exits +
                        "</td><td>" +
                        arr[i].Status +
                        "</td></tr>";
                    }
                    document.getElementById("result").innerHTML = out;
                  }
                  else {
                    document.getElementById("result").innerHTML = "No Result";
                  }


              });

          }
          else {

          }
        }
    </script>
  </body>
</html>
