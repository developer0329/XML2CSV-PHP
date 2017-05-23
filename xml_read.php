<?php
    include 'db_config.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO properties(site_id, site_name, mac_address, ip_address,".
          " timezone, dst, device_type, sn, obj_id, device_id, device_name,".
          " obj_type, obj_name) VALUES (";

    //$xml = simplexml_load_file("test.xml") or die("Error: Cannot create object");
    $echoMsg = "";

    if ($_FILES["xmlfile"]["error"] > 0)
    {
        $echoMsg =  "Please Select XML File";
    }
    else
    {
        $upload = (object) $_FILES['xmlfile'];
        $xml = $upload->error ? NULL : simplexml_load_file($upload->tmp_name);

        $metrics = $xml->attributes();

        $sql .= "'".$metrics["SiteId"];
        $sql .= "', '".$metrics["Sitename"];

        foreach ($xml->Properties as $Propertie)
        {
            $sql .= "', '" . $Propertie->MacAddress;
            $sql .= "', '" . $Propertie->IpAddress;
            $sql .= "', '" . $Propertie->Timezone;
            $sql .= "', '" . $Propertie->DST;
            $sql .= "', '" . $Propertie->DeviceType;
            $sql .= "', '" . $Propertie->SerialNumber;
        }

        $object = $xml->ReportData->Report ->Object->attributes();
        $rptDate = $xml->ReportData->Report->attributes();

        $sql .= "', '" . $object["Id"];
        $sql .= "', '" . $object["DeviceId"];
        $sql .= "', '" . $object["Devicename"];
        $sql .= "', '" . $object["ObjectType"];
        $sql .= "', '" . $object["Name"]."');";

        $siteInfoCheckSql = "SELECT * FROM properties WHERE site_id='" . $metrics["SiteId"] . "' AND device_id='" . $object["DeviceId"] . "'";
        $siteInfoCheckResult = $conn->query($siteInfoCheckSql);

        if ($siteInfoCheckResult->num_rows > 0) {
            // output data of each row
            $siteId = "";
            $deviceId = "";
            $rowId = 0;
            while($row = $siteInfoCheckResult->fetch_assoc()) {
                $siteId = $row["site_id"];
                $deviceId = $row["device_id"];
                $rowId = $row["id"];
            }

            $dataCheckSql = "SELECT * FROM report_data WHERE pid='" . $rowId . "' AND Date=DATE('" . $rptDate["Date"] . "')";
            $dataCheckResult = $conn->query($dataCheckSql);

            if ($dataCheckResult->num_rows > 0) {
                // Update
            }
            else {
                // Insert
                $sql = "INSERT INTO report_data(pid, Date, StartTime, EndTime, Enters,".
                        " Exits, Status) VALUES (";

                $sql .= $rowId. ", DATE('" . $rptDate["Date"];
                $temp = $sql;
                foreach ($xml->ReportData->Report ->Object->Count as $count)
                {
                    $sql = $temp;
                    $attr = $count->attributes();

                    $sql .= "'),'" . $attr['StartTime'];
                    $sql .= "', '" . $attr['EndTime'];
                    $sql .= "', " . $attr['Enters'];
                    $sql .= ", " . $attr['Exits'];
                    $sql .= ", " . $attr['Status'];
                    $sql .= ");";

                    if ($conn->query($sql) === TRUE) {
                        $echoMsg = "Success";
                    }
                    else {
                        $echoMsg = "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }            

        } else {
            if ($conn->query($sql) === TRUE) {

                $last_id = $conn->insert_id;

                $sql = "INSERT INTO report_data(pid, Date, StartTime, EndTime, Enters,".
                        " Exits, Status) VALUES (";

                $sql .= $last_id. ", DATE('" . $rptDate["Date"];
                $temp = $sql;
                foreach ($xml->ReportData->Report ->Object->Count as $count)
                {
                    $sql = $temp;
                    $attr = $count->attributes();

                    $sql .= "'),'" . $attr['StartTime'];
                    $sql .= "', '" . $attr['EndTime'];
                    $sql .= "', " . $attr['Enters'];
                    $sql .= ", " . $attr['Exits'];
                    $sql .= ", " . $attr['Status'];
                    $sql .= ");";

                    if ($conn->query($sql) === TRUE) {
                        $echoMsg = "Success";
                    }
                    else {
                        $echoMsg = "Error: " . $sql . "<br>" . $conn->error;
                    }
                }

            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }



    }

    $conn->close();

    echo '<a href="index.html">' . $echoMsg . '</a>';

?>
