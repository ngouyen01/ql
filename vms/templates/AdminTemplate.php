<?php
namespace vms\templates;

use vms\components\HeaderComponent;
use api\v1\UserAPI;

class AdminTemplate {
    // Khai báo child và hàm render child view-model
    public $child;
    public $rows;
    public function renderChild($child) {
        $res = UserAPI::getUserById($_SESSION['user_id']);
        $_SESSION['temporary_type'] = $res->message[0]['type'];
        $this->child = $child;
        $this->rows = UserAPI::getTotalByDay();
        $this->render();
    }

    public function __construct($params = null) {}

    public function render() {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $this->child->title ?></title>
        <link rel='shortcut icon' type='image/x-icon' href='/assets/img/favicon.ico'/>
        <!-- Bootstrap Core CSS -->
        <link href="/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- MetisMenu CSS -->
        <link href="/assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet" type="text/css">

        <!-- Custom CSS -->
        <link href="/assets/css/admin.css" rel="stylesheet" type="text/css">

        <!-- Custom Fonts -->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

        <!-- DataTables CSS -->
        <link href="/assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="/assets/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
        
        <!-- Library -->
    </head>
    <body>
        <div id="wrapper">
            <?php (new HeaderComponent())->render(); ?>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <?php $this->child->__render(); ?>
                </div>
            </div>
        </div>
    </body>
    <!-- jQuery -->
    <script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/assets/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/assets/js/handle.js"></script>

    <!-- DataTables JavaScript -->
    <script src="/assets/bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="/assets/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Day');
            data.addColumn('number', 'Total');
            data.addRows([
                <?php foreach($this->rows->message as $row): ?>   
                ["<?= $row["DATE(created_at)"] ?>",<?= (int)$row["sum(total)"] ?>],
                <?php endforeach; ?>
            ]);

            // Set chart options
            var options = {'title':'Doanh thu các ngày trong tháng',
                            'width':600,
                            'height':600};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
    </html>
<?php }}