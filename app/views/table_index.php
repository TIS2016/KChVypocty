<?php
session_start();
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}
if (!$_SESSION['logged_in']){
   //header("Location: ../../index.php");
}

if (isset($_POST['logout_button'])){
    destroySessionAndRedirect();
}

require_once '../../vendor/autoload.php';
require_once '../../bootstrap.php';
use AjaxLiveSearch\core\Config;
use AjaxLiveSearch\core\Handler;
use App\Db\Calculation;
use App\Presenter;
use Doctrine\ORM\Tools\Pagination\Paginator;

$handler = new Handler();
$handler->getJavascriptAntiBot();
$token = $handler->getToken();

$data = Presenter::getTableData();



?>

<!DOCTYPE>
<html lang="en">
<head>
    <title>Table</title>
    <script src="js/jquery-3.1.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script type="text/javascript" src="/tis/app/assets/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="../assets/table_style.css">
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontello.css">
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

        #status-text {
            margin-top: 5px;
            width: 150px;
            height: 30px;
            float: right;
        }
    </style>

</head>
<body>
    <div>
        <form method="post" action="table_index.php">
            <button class="button button-run" name="logout_button">Logout</button>
        </form>
    </div>
    <div>
        <button class="button button-run" onclick="run()">Run</button>
    </div>
    <div>
        <button class="button button-run" onclick="showReport()">Show Report</button>
    </div>

    <div id="status-text"></div>

<form method="POST">
    <select name="job_type" onchange="this.form.submit();">
        <option value="">Select job type</option>
        <option value="">All</option>
        <?php foreach (Presenter::getDistinctValues("jobType") as $jobType): ?>
            <option value="<?= $jobType['jobType'] ?>" <?php if (isset($_POST['job_type']) && $_POST['job_type'] == $jobType['jobType']) echo "selected"; ?>><?= $jobType['jobType'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="method" onchange="this.form.submit();">
        <option value="">Select method</option>
        <option value="">All</option>
        <?php foreach (Presenter::getDistinctValues("method") as $method): ?>
            <option value="<?= $method['method'] ?>" <?php if (isset($_POST['job_type']) && $_POST['job_type'] == $method['method']) echo "selected"; ?>><?= $method['method'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="basis_set" onchange="this.form.submit();">
        <option value="">Select basis set</option>
        <option value="">All</option>
        <?php foreach (Presenter::getDistinctValues('basisSet') as $basisSet): ?>
            <option value="<?= $basisSet['basisSet'] ?>" <?php if (isset($_POST['job_type']) && $_POST['job_type'] == $basisSet['basisSet']) echo "selected"; ?>><?= $basisSet['basisSet'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="stechiometry" onchange="this.form.submit();">
        <option value="">Select stechiometry</option>
        <option value="">All</option>

        <?php foreach (Presenter::getDistinctValues('stechiometry') as $stechiometry): ?>
            <option value="<?= $stechiometry['stechiometry'] ?>" <?php if (isset($_POST['job_type']) && $_POST['job_type'] == $stechiometry['stechiometry']) echo "selected"; ?>><?= $stechiometry['stechiometry'] ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <br>
</form>

<div>
    <button class="button button-run" onclick="logout()">Logout</button>
</div>
<div>
    <button class="button button-run" onclick="run()">Run</button>
</div>
<div>
    <button class="button button-run" onclick="showReport()">Show Report</button>
</div>

<div id="status-text"></div>

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
                <?php
                try {

                    // Find out how many items are in the table
                    $total = count($data);

                    // How many items to list per page
                    $limit = 100;

                    // How many pages will there be
                    $pages = ceil($total / $limit);

                    // What page are we currently on?
                    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                        'options' => array(
                            'default' => 1,
                            'min_range' => 1,
                        ),
                    )));

                    // Calculate the offset for the query
                    $offset = ($page - 1) * $limit;

                    // Some information to display to the user
                    $start = $offset + 1;
                    $end = min(($offset + $limit), $total);

                    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

                    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
//                    echo '<br>page = ' . $page . '<br>';
                    echo '<div id="paging"><p>', ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results <br>', $prevlink;
                    for ($i = 1; $i < $pages+1; $i++) {
                        echo '<a href="?page=' . ($i) . '" title="page ' . $i . '">' . $i .'</a>';
                    }
                    echo $nextlink, ' </p></div>';

                    function check($calc)
                    {
                        foreach ($_POST as $key => $value) {
                            if ($key == 'job_type' && $value != '') {
                                if ($calc->getJobType() != $value) return false;
                            }
                            if ($key == 'method' && $value != '') {
                                if ($calc->getMethod() != $value) return false;
                            }
                            if ($key == 'basis_set' && $value != '') {
                                if ($calc->getBasisSet() != $value) return false;
                            }
                            if ($key == 'stechiometry' && $value != '') {
                                if ($calc->getStechiometry() != $value) return false;
                            }
                        }
                        return true;
                    }

                    $paginator = Presenter::getPagiData($offset, $limit);

                    foreach ($paginator as $calculation):
                        if (check($calculation)) { ?>
                            <tr class="table-row">
                                <td class="table-column"><?= $calculation->getCalculationID() ?></td>
                                <td class="table-column"><?= $calculation->getJobType() ?></td>
                                <td class="table-column"><?= $calculation->getMethod() ?></td>
                                <td class="table-column"><?= $calculation->getBasisSet() ?></td>
                                <td class="table-column"><?= $calculation->getStechiometry() ?></td>
                                <td class="table-column"><?= $calculation->getUser() ?></td>
                                <td class="table-column"><?= $calculation->getDate() ?></td>
                                <td class="table-column"><?= $calculation->getServer() ?></td>
                                <td class="table-column"><?= $calculation->getPath() ?></td>
                                <td class="table-column"><input data-item-id="<?= $calculation->getCalculationID() ?>"
                                                                class="show_info" type="button" value="Show details">
                                </td>
                            </tr>
                            <?php
                        } else continue;
                    endforeach;
                } catch (Exception $e) {
                    echo '<p>', $e->getMessage(), '</p>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<script>
    function showReport() {
        window.location.href = "/tis/app/views/log.php";
    }

    function run(){
        sendAjaxRequest(function(responseData){
            console.log(responseData);
            $("#status-text").text(responseData);
        });
    }

    function logout() {
//        console.log("ok", window.location);
        <?php
        session_unset();
        ?>
        window.location = "login.php";
    };

    function sendAjaxRequest(callBackFunction) {
        var requestPath = "/tis/app/ajax/run_script.php";
        var responseData = "";
        $.ajax({
            timeout: 1000000000,
            url: requestPath,
            type: 'POST',
            dataType: 'text',
            success: function (data) {
                console.log("End");
                callBackFunction(data);
            },
            error: function(data){
                console.log(data);
            },
            data: { name: "John", location: "Boston" }
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

    $(".button").on("click", function (e) {
        var id = $(this).attr('data-item-id');
        window.location.href = "/tis/app/views/item_index.php?item_id=" + id;
    });
</script>


<?php
    function destroySessionAndRedirect(){
        unset($_SESSION['logged_in']);
        session_destroy();
        header("Location: ../../index.php");
    }
?>