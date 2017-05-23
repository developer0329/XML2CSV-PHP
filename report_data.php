<?php
    include 'db_config.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    if(isset($_POST['SiteID'])){

        $sql =  "SELECT properties.*, report_data.* "
                . "FROM report_data "
                . "INNER JOIN properties "
                . "ON report_data.pid=properties.id "
                . "WHERE concat(report_data.Date,' ',report_data.StartTime) >= '"
                .  $_POST['startDate'] . " " . $_POST['startTime']
                . "' AND concat(report_data.Date,' ',report_data.EndTime) <= '" . $_POST['endDate']
                . " " . $_POST['endTime']
                ."' AND properties.id="
                . "'" . $_POST['SiteID'] . "'";

        $result = $conn->query($sql);

        $outp = "[";

        if ($result->num_rows > 0) {

          $outp = "[";
          $list = array("Date,StartTime,EndTime,Enters,Exits,Status");
          while($rs = $result->fetch_array(MYSQLI_ASSOC)) {

              if ($outp != "["){
                  $outp .= "," ;
              }

              $outp .= '{"Date":"'  . $rs["Date"] . '",';
              $outp .= '"StartTime":"' . $rs["StartTime"] . '",';
              $outp .= '"EndTime":"' . $rs["EndTime"] . '",';
              $outp .= '"Enters":"' . $rs["Enters"] . '",';
              $outp .= '"Exits":"' . $rs["Exits"] . '",';
              $outp .= '"Status":"'. $rs["Status"] . '"}';

              array_push($list, $rs["Date"] . "," . $rs["StartTime"] . "," . $rs["EndTime"] . "," . $rs["Enters"] . "," . $rs["Exits"] . "," . $rs["Status"]);
          }

          $outp .="]";

          $file = fopen("report.csv","w");

          foreach ($list as $line)
          {
              fputcsv($file, explode(',',$line));
          }

          fclose($file);


        }else {
            $outp = "0";
        }

        $conn->close();
        echo($outp);
    }

?>
