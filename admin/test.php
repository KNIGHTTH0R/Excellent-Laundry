<!DOCTYPE html>
<html>

  <head>
    <title>Bar Chart</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="http://code.highcharts.com/highcharts.js"></script>
      <script src="http://code.highcharts.com/highcharts-3d.js"></script>
<meta name="viewport" content="initial-scale = 1, user-scalable = no" />
    <style>
        #container {
            height: 400px;
            min-width: 310px;
            max-width: 800px;
            margin: 0 auto;
        }
		</style>
  </head>

  <body>
    <div id="container"></div>
    <script type="text/javascript">

        $(document).ready(function(){
    $('#container').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
				enabled: true,
                alpha: 15,
                beta: 0,
                depth: 110
            }
        },
        plotOptions: {
            column: {
                depth: 40,
                stacking: true,
                grouping: true,
                groupZPadding: 10
            }
        },
        series: [{
            data: [1, 2, 4, 3, 2, 4],
            stack: 0
        }, {
            data: [5, 6, 3, 4, 1, 2],
            stack: 2
        }, {
            data: [7, 9, 8, 7, 5, 8],
            stack: 1
        }]
    });
});
	
	</script>
  </body>

</html>
