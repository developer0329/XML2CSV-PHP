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
                <li class="active"><a href="#">Import XML</a></li>
                <li ><a href="report.php">Export CSV</a></li>
            </ul>
        </div>
      </nav>
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <form action="xml_read.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="file">Please seclect a XML File</label>
                  <input type="file" name="xmlfile" class="form-control"/>
                </div>
                  <input type="submit" name="submit" value="File Read" class="form-control"/>
            </form>
          </div>
        </div>
      </div>
  </body>
</html>
