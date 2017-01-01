<?php
turnOnDisplayErrors();

function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
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
        <button class="button button-run" onclick="logout()">Logout</button>
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
        <option value="FOpt" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "FOpt") echo "selected"; ?>>FOpt</option>
        <option value="Freq" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "Freq") echo "selected"; ?>>Freq</option>
        <option value="Mixed" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "Mixed") echo "selected"; ?>>Mixed</option>
        <option value="POpt" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "POpt") echo "selected"; ?>>POpt</option>
        <option value="Scan" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "Scan") echo "selected"; ?>>Scan</option>
        <option value="Stability" <?php if (isset($_POST['job_type']) &&
            $_POST['job_type'] == "Stability") echo "selected"; ?>>Stability</option>
    </select>
    <select name="method" onchange="this.form.submit();">
        <option value="">Select method</option>
        <option value="">All</option>
        <option value="G3" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "G3") echo "selected"; ?>>G3</option>
        <option value="G3MP2" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "G3MP2") echo "selected"; ?>>G3MP2</option>
        <option value="RB3LYP" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "RB3LYP") echo "selected"; ?>>RB3LYP</option>
        <option value="RHF" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "RHF") echo "selected"; ?>>RHF</option>
        <option value="RM062X" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "RM062X") echo "selected"; ?>>RM062X</option>
        <option value="RMP2-FU" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "RMP2-FU") echo "selected"; ?>>RMP2-FU</option>
        <option value="RPBE1PBE" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "RPBE1PBE") echo "selected"; ?>>RPBE1PBE</option>
        <option value="UB3LYP" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "UB3LYP") echo "selected"; ?>>UB3LYP</option>
        <option value="UHF" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "UHF") echo "selected"; ?>>UHF</option>
        <option value="UMP2-FU" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "UMP2-FU") echo "selected"; ?>>UMP2-FU</option>
        <option value="UPBE1PBE" <?php if (isset($_POST['method']) &&
            $_POST['method'] == "UPBE1PBE") echo "selected"; ?>>UPBE1PBE</option>
    </select>
    <select name="basis_set" onchange="this.form.submit();">
        <option value="">Select basis set</option>
        <option value="">All</option>
        <option  value='6-31+G(d)' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "6-31+G(d)") echo "selected"; ?>>6-31+G(d)</option>
        <option  value='6-311++G(2df,2pd)' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "6-311++G(2df,2pd)") echo "selected"; ?>>6-311++G(2df,2pd)</option>
        <option  value='6-31G(2df,p)' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "6-31G(2df,p)") echo "selected"; ?>>6-31G(2df,p)</option>
        <option  value='6-31G(d)' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "6-31G(d)") echo "selected"; ?>>6-31G(d)</option>
        <option  value='G3' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "G3") echo "selected"; ?>>G3</option>
        <option  value='G3MP2' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "G3MP2") echo "selected"; ?>>G3MP2</option>
        <option  value='GTMP2large' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "GTMP2large") echo "selected"; ?>>GTMP2large</option>
        <option  value='GTlarge' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "GTlarge") echo "selected"; ?>>GTlarge</option>
        <option  value='Gen' <?php if (isset($_POST['basis_set']) &&
            $_POST['basis_set'] == "Gen") echo "selected"; ?>>Gen</option>
    </select>
    <select name="stechiometry" onchange="this.form.submit();">
        <option value="">Select stechiometry</option>
        <option value="">All</option>
        <option  value='B1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "B1(1+)") echo "selected"; ?>>B1(1+)</option>
        <option  value='B1O1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "B1O1(1+)") echo "selected"; ?>>B1O1(1+)</option>
        <option  value='B1O2(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "B1O2(1+)") echo "selected"; ?>>B1O2(1+)</option>
        <option  value='B1O3(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "B1O3(1+)") echo "selected"; ?>>B1O3(1+)</option>
        <option  value='C1H2O1' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H2O1") echo "selected"; ?>>C1H2O1</option>
        <option  value='C1H3(2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3(2)") echo "selected"; ?>>C1H3(2)</option>
        <option  value='C1H3B1O1(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3B1O1(1+,2)") echo "selected"; ?>>C1H3B1O1(1+,2)</option>
        <option  value='C1H3B1O3(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3B1O3(1+,2)") echo "selected"; ?>>C1H3B1O3(1+,2)</option>
        <option  value='C1H3O1(1-)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3O1(1-)") echo "selected"; ?>>C1H3O1(1-)</option>
        <option  value='C1H3O1(2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3O1(2)") echo "selected"; ?>>C1H3O1(2)</option>
        <option  value='C1H3Si1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H3Si1(1+)") echo "selected"; ?>>C1H3Si1(1+)</option>
        <option  value='C1H4Si1(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H4Si1(1+,2)") echo "selected"; ?>>C1H4Si1(1+,2)</option>
        <option  value='C1H5Si1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1H5Si1(1+)") echo "selected"; ?>>C1H5Si1(1+)</option>
        <option  value='C1O1' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C1O1") echo "selected"; ?>>C1O1</option>
        <option  value='C2Fe1O2(1+,4)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C2Fe1O2(1+,4)") echo "selected"; ?>>C2Fe1O2(1+,4)</option>
        <option  value='C2H5N1O2' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C2H5N1O2") echo "selected"; ?>>C2H5N1O2</option>
        <option  value='C2H6B1O2(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C2H6B1O2(1+)") echo "selected"; ?>>C2H6B1O2(1+)</option>
        <option  value='C2H6B1O2(2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C2H6B1O2(2)") echo "selected"; ?>>C2H6B1O2(2)</option>
        <option  value='C2H6B1O3(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C2H6B1O3(1+)") echo "selected"; ?>>C2H6B1O3(1+)</option>
        <option  value='C3Fe1O3(1+,4)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3Fe1O3(1+,4)") echo "selected"; ?>>C3Fe1O3(1+,4)</option>
        <option  value='C3Fe1O3(3)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3Fe1O3(3)") echo "selected"; ?>>C3Fe1O3(3)</option>
        <option  value='C3H7N1O2' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3H7N1O2") echo "selected"; ?>>C3H7N1O2</option>
        <option  value='C3H8B1O3(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3H8B1O3(1+)") echo "selected"; ?>>C3H8B1O3(1+)</option>
        <option  value='C3H9B1O3' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3H9B1O3") echo "selected"; ?>>C3H9B1O3</option>
        <option  value='C3H9B1O3(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3H9B1O3(1+,2)") echo "selected"; ?>>C3H9B1O3(1+,2)</option>
        <option  value='C3H9B1O3(1-,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C3H9B1O3(1-,2)") echo "selected"; ?>>C3H9B1O3(1-,2)</option>
        <option  value='C4Fe1O4' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C4Fe1O4") echo "selected"; ?>>C4Fe1O4</option>
        <option  value='C4Fe1O4(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C4Fe1O4(1+,2)") echo "selected"; ?>>C4Fe1O4(1+,2)</option>
        <option  value='C4Fe1O4(1+,4)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C4Fe1O4(1+,4)") echo "selected"; ?>>C4Fe1O4(1+,4)</option>
        <option  value='C4Fe1O4(1-,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C4Fe1O4(1-,2)") echo "selected"; ?>>C4Fe1O4(1-,2)</option>
        <option  value='C4Fe1O4(3)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C4Fe1O4(3)") echo "selected"; ?>>C4Fe1O4(3)</option>
        <option  value='C5Fe1O5' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C5Fe1O5") echo "selected"; ?>>C5Fe1O5</option>
        <option  value='C5Fe1O5(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C5Fe1O5(1+,2)") echo "selected"; ?>>C5Fe1O5(1+,2)</option>
        <option  value='C5Fe1O5(1-,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C5Fe1O5(1-,2)") echo "selected"; ?>>C5Fe1O5(1-,2)</option>
        <option  value='C5H11N1O2' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C5H11N1O2") echo "selected"; ?>>C5H11N1O2</option>
        <option  value='C6H5(2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C6H5(2)") echo "selected"; ?>>C6H5(2)</option>
        <option  value='C6H5Si1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C6H5Si1(1+)") echo "selected"; ?>>C6H5Si1(1+)</option>
        <option  value='C6H6' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C6H6") echo "selected"; ?>>C6H6</option>
        <option  value='C6H6Si1(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C6H6Si1(1+,2)") echo "selected"; ?>>C6H6Si1(1+,2)</option>
        <option  value='C6H7Si1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C6H7Si1(1+)") echo "selected"; ?>>C6H7Si1(1+)</option>
        <option  value='C7H10Si1(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C7H10Si1(1+,2)") echo "selected"; ?>>C7H10Si1(1+,2)</option>
        <option  value='C7H8Si1(1+,2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C7H8Si1(1+,2)") echo "selected"; ?>>C7H8Si1(1+,2)</option>
        <option  value='C7H9Si1(1+)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C7H9Si1(1+)") echo "selected"; ?>>C7H9Si1(1+)</option>
        <option  value='C8Fe2O8' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C8Fe2O8") echo "selected"; ?>>C8Fe2O8</option>
        <option  value='C9H11N1O3' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "C9H11N1O3") echo "selected"; ?>>C9H11N1O3</option>
        <option  value='Fe1' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "Fe1") echo "selected"; ?>>Fe1</option>
        <option  value='H1(2)' <?php if (isset($_POST['stechiometry']) &&
            $_POST['stechiometry'] == "H1(2)") echo "selected"; ?>>H1(2)</option>
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
                    $limit = 2;

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
