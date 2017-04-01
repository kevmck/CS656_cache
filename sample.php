
<!DOCTYPE html>

<?php
  include ("account.php");
  ($dbh = mysql_connect($hostname, $username, $password)) or die ("Unable to connect to MySQL database.");
  mysql_select_db($db);
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Car', '# of Searches'],

          <?php
            $chartData = "SELECT * FROM testdata";
            ($query = mysql_query($chartData)) or die (mysql_error());

            while ($result = mysql_fetch_array($query))
            {
              echo "['".$result['car']."',".$result['searchnum']."],";
            }

           ?>
        ]);

        var options = {
          title: 'Test Stats'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>
