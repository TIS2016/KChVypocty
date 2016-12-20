<?php
turnOnDisplayErrors();

function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

require_once '../../vendor/autoload.php';
use AjaxLiveSearch\core\Config;
use AjaxLiveSearch\core\Handler;
use App\Db\Calculation;
use App\Presenter;

$handler = new Handler();
$handler->getJavascriptAntiBot();
$token = $handler->getToken();

$data = Presenter::getTableData();
?>



<!DOCTYPE>
<html lang="en">
<head>
    <title>Table</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script type="text/javascript" src="/tis/app/assets/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="../assets/table_style.css">
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ajaxlivesearch.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/tis/app/assets/item_style.css">

    <style>

        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 4px 8px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button-run {
            background-color: white;
            color: black;
            border: 2px solid #e7e7e7;
            float: right;
        }

        .button-run:hover {background-color: #e7e7e7;}

        .center {
            text-align: center;
            margin-bottom: 10px;
        }
        
        
    </style>

</head>
<body>
    <div>
        <button class="button button-run" onclick="logout()">Logout</button>
    </div>
    <div>
        <button class="button button-run" onclick="run()">Run</button>
    </div>

    <div class="search">
        <input type="text" class='mySearch' id="ls_query" placeholder="Type to start searching ...">
    </div>

    <div class="container-fluid">
        <div class="row center">
            <div class="col-md-12">
		<form action=“../upload.php" method="post" enctype="multipart/form-data">
		<input type="file" name="file">
		<input type="submit" value="Upload file" name="submit">
		</form>
                <table align="center">
                    <tr class="table-row">
                        <th class="table-header">Id</th>
                        <th class="table-header">Job Type</th>
                        <th class="table-header">Method</th>
                        <th class="table-header">Basis Set</th>
                        <th class="table-header">Stechiometry</th>
                        <th class="table-header">User</th>
                        <th class="table-header">Date</th>
                        <th class="table-header">Server</th>
                        <th class="table-header">Path</th>
                        <th class="table-header">Show info</th>

                    </tr>
                    <?php foreach ($data as $calculation ): ?>
                        <tr class="table-row">
                            <td class="table-column"><?= $calculation->getCalculationID() ?></td>
                            <td class="table-column"><?= $calculation->getJobType() ?></td>
                            <td class="table-column"><?= $calculation->getMethod() ?></td>
                            <td class="table-column"><?= $calculation->getBasisSet() ?></td>
                            <td class="table-column"><?= $calculation->getStechiometry() ?></td>
                            <td class="table-column"><?= $calculation->getUser() ?></td>
                            <td class="table-column"><?= $calculation->getDate()?></td>
                            <td class="table-column"><?= $calculation->getServer()  ?></td>
                            <td class="table-column"><?= $calculation->getPath() ?></td>
                            <td class="table-column"><input data-item-id="<?= $calculation->getCalculationID() ?>" class="show_info" type="button" value="Show details"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

</body>
</html>

<script>

    function run(){
        sendAjaxRequest(function(responseData){
            console.log(responseData);
        });
    }

    function logout() {
        console.log("ok", window.location);
        <?php
        session_unset();
        ?>
        window.location = "login.php";
    };

    function sendAjaxRequest(callBackFunction) {
        var requestPath = "/tis/app/ajax/ajax_handler.php";
        var responseData = "";
        $.ajax({
            url: requestPath,
            type: 'post',
            dataType: 'text',
            success: function (data) {
                callBackFunction(data);
            }
        });

        return responseData;
    }



    jQuery("#ls_query").ajaxlivesearch({
        loaded_at: <?php echo time(); ?>,
        token: <?php echo "'" . $handler->getToken() . "'"; ?>,
        max_input: <?php echo Config::getConfig('maxInputLength'); ?>,
        onResultClick: function(e, data) {
            var selectedOne = jQuery(data.selected).find('td').eq('0').text();

            // set the input value
            jQuery('#ls_query').val(selectedOne);

            // hide the result
            jQuery("#ls_query").trigger('ajaxlivesearch:hide_result');

//            var requestData = {};
//            requestData.calculation_id = selectedOne;
//
//            sendAjaxRequest(function (data) {
//                console.log(data);
//            }, requestData);

            window.location.href = "/tis/app/views/item_index.php?item_id=" + selectedOne;
        },
        onResultEnter: function(e, data) {
             jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
        },
        onAjaxComplete: function(e, data) {

        }
    });

    $(".show_info").on("click", function (e) {
        var id = $(this).attr('data-item-id');
        window.location.href = "/tis/app/views/item_index.php?item_id=" + id;
    });

    $(".button.").on("click", function (e) {
        var id = $(this).attr('data-item-id');
        window.location.href = "/tis/app/views/item_index.php?item_id=" + id;
    });
</script>