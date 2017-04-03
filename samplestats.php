<!DOCTYPE html>
<style>

  h1{text-align: center;}

</style>

<h1>CRI Sample Statistics</h1>

<!-- Connect to database -->
<?php
  include ("account.php");
  ($dbh = mysql_connect($hostname, $username, $password)) or die ("Unable to connect to MySQL database.");
  mysql_select_db($db);
?>

<!-- Start of Google Chart code -->
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      // Function to draw all three charts
      function drawChart() {

        //Chart #1
        var data = google.visualization.arrayToDataTable([
          ['Manufacturer', '# of Searches'],

          // This block of PHP code dynamically populates the relevant chart with the most current information from the database.
          // Applies to all three charts.
          <?php
            // Define SQL query needed to pull the relevant information.
            $chartData = "SELECT make, SUM(requests) AS total FROM recallcache GROUP BY make ORDER BY total DESC LIMIT 5";

            // Execute query
            ($query = mysql_query($chartData)) or die (mysql_error());

            // Process query into array that Google Charts will use to draw the diagrams.
            while ($result = mysql_fetch_array($query))
            {
              echo "['" . ucfirst($result['make']) . "'," . $result['total'] . "],";
            }
           ?>
        ]);

        //Chart #2
        var data2 = google.visualization.arrayToDataTable([
          ['Model', '# of Searches', {role: 'annotation'}, {role: 'style'}],

          <?php
            $chartData2 = "SELECT model, SUM(requests) AS total FROM recallcache GROUP BY model ORDER BY total DESC LIMIT 5";
            ($query = mysql_query($chartData2)) or die (mysql_error());

            while ($result = mysql_fetch_array($query))
            {
              $color = dechex(rand(0x000000, 0xFFFFFF));
              echo "['". ucfirst($result['model']) . "'," . $result['total'] . "," . $result['total'] . ",'" . $color . "'],";
            }
           ?>
        ]);

        //Chart #3
        var data3 = google.visualization.arrayToDataTable([
          ['Car', '# of Searches', {role: 'annotation'}, {role: 'style'}],

          <?php
            $chartData3 = "SELECT * FROM `recallcache` ORDER BY requests DESC LIMIT 5";
            ($query = mysql_query($chartData3)) or die (mysql_error());

            while ($result = mysql_fetch_array($query))
            {
              $color = dechex(rand(0x000000, 0xFFFFFF));
              echo "['" . ucfirst($result['year'])  . " " . ucfirst($result['make']) . " " . ucfirst($result['model']) . "'," . $result['requests'] . "," . $result['requests'] . ",'" . $color . "'],";
            }
           ?>
        ]);

        // Sets options for the charts. Options will be used below when the tables are drawn.
        var options = {
                      title: 'Top 5 Searched Brands/Manufacturers',
                      };

        var options2 = {
                        title: 'Top 5 Searched Models',
                       };

        var options3 = {
                        title: 'Top Searched Car'
                       };

        // Charts are drawn here; any options set above are applied.
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);

        var chart2 = new google.visualization.ColumnChart(document.getElementById('columnchart_values'));
        chart2.draw(data2, options2);

        var chart3 = new google.visualization.BarChart(document.getElementById('barchart_values'));
        chart3.draw(data3, options3);

      }
    </script>
  </head>
  <body>
    <!-- Each chart lives in its own <div> (division) tag. -->
    <div id="piechart" style="width: 1800px; height: 1000px;"></div>
    <div id="columnchart_values" style="width: 1800px; height: 1000px;"></div>
    <div id="barchart_values" style="width: 1800px; height: 1000px;"></div>
  </body>
</html>
