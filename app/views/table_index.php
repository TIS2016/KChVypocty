<?php
session_start();
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

if (!$_SESSION['logged_in']) {
    header("Location: ../../index.php");
}

if (isset($_POST['logout_button'])) {
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

$allData = Presenter::getTableData();

$data = [];

if (isset($_POST['job_type']) || isset($_POST['method']) || isset($_POST['basis_set']) || isset($_POST['stechiometry'])) {
    $_SESSION['post'] = $_POST;
}

function check($calc)
{
    if (isset($_SESSION['post'])) {
        foreach ($_SESSION['post'] as $key => $value) {
            if ($key == 'job_type' && $value != '') {
                if ($calc->getJobType() != $value) {
                    return false;
                }
            }
            if ($key == 'method' && $value != '') {
                if ($calc->getMethod() != $value) {
                    return false;
                }
            }
            if ($key == 'basis_set' && $value != '') {
                if ($calc->getBasisSet() != $value) {
                    return false;
                }
            }
            if ($key == 'stechiometry' && $value != '') {
                if ($calc->getStechiometry() != $value) {
                    return false;
                }
            }
        }
    }
    return true;
}

foreach ($allData as $calculation) {
    if (check($calculation)) {
        $data[] = $calculation;
    }
}

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


    <form method="POST" name="filter" action="table_index.php">
        <select name="job_type" onchange="this.form.submit();">
            <option value="">Select job type</option>
            <option value="">All</option>
            <?php if (isset($_SESSION['post']['job_type'])): ?>
                <?php foreach (Presenter::getJobTypes($data) as $jobType): ?>
                    <option value="<?= $jobType ?>" <?php if (isset($_SESSION['post']['job_type']) && $_SESSION['post']['job_type'] == $jobType) echo "selected"; ?>><?= $jobType ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach (Presenter::getDistinctValues('jobType') as $jobType): ?>
                    <option value="<?= $jobType ?>" <?php if (isset($_SESSION['post']['job_type']) && $_SESSION['post']['job_type'] == $jobType) echo "selected"; ?>><?= $jobType ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <select name="method" onchange="this.form.submit();">
            <option value="">Select method</option>
            <option value="">All</option>
            <?php if (isset($_SESSION['post']['method'])): ?>
                <?php foreach (Presenter::getMethods($data) as $method): ?>
                    <option value="<?= $method ?>" <?php if (isset($_SESSION['post']['method']) && $_SESSION['post']['method'] == $method) echo "selected"; ?>><?= $method ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach (Presenter::getDistinctValues('method') as $method): ?>
                    <option value="<?= $method ?>" <?php if (isset($_SESSION['post']['method']) && $_SESSION['post']['method'] == $method) echo "selected"; ?>><?= $method ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <select name="basis_set" onchange="this.form.submit();">
            <option value="">Select basis set</option>
            <option value="">All</option>
            <?php if (isset($_SESSION['post']['basis_set'])): ?>
                <?php foreach (Presenter::getBasisSets($data) as $basisSet): ?>
                    <option value="<?= $basisSet ?>" <?php if (isset($_SESSION['post']['basis_set']) && $_SESSION['post']['basis_set'] == $basisSet) echo "selected"; ?>><?= $basisSet ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach (Presenter::getDistinctValues('basisSet') as $basisSet): ?>
                    <option value="<?= $basisSet ?>" <?php if (isset($_SESSION['post']['basis_set']) && $_SESSION['post']['basis_set'] == $basisSet) echo "selected"; ?>><?= $basisSet ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <select name="stechiometry" onchange="this.form.submit();">
            <option value="">Select stechiometry</option>
            <option value="">All</option>

            <?php if (isset($_SESSION['post']['stechiometry'])): ?>
                <?php foreach (Presenter::getStechiometries($data) as $stechiometry): ?>
                    <option value="<?= $stechiometry ?>" <?php if (isset($_SESSION['post']['stechiometry']) && $_SESSION['post']['stechiometry'] == $stechiometry) echo "selected"; ?>><?= $stechiometry ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach (Presenter::getDistinctValues('stechiometry') as $stechiometry): ?>
                    <option value="<?= $stechiometry ?>" <?php if (isset($_SESSION['post']['stechiometry']) && $_SESSION['post']['stechiometry'] == $stechiometry) echo "selected"; ?>><?= $stechiometry ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <br>
        <br>
    </form>

    <div class="search">
        <input type="text" class='mySearch' id="ls_query" placeholder="Type to start searching ...">
    </div>

    <div class="container-fluid">
        <div class="row center">
            <div class="col-md-12">
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
                            $total = count($data);

                            $limit = 100;

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

                            $paginator = Presenter::getPaginationData($offset, $limit, $_SESSION['post']);

                            foreach ($paginator as $calculation) {
                                if (check($calculation)) {
                                    ?>
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
                                        <td class="table-column"><input
                                                    data-item-id="<?= $calculation->getCalculationID() ?>"
                                                    class="show_info" type="button" value="Show details">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        } catch (Exception $e) {
                            echo '<p>', $e->getMessage(), '</p>';
                        }
                    ?>
                </table>

                <style>
                    .pagination {
                        color: black;
                        text-decoration: none;
                    }
                </style>

                <?php
                $prevlink = ($page > 1) ? '<a class="pagination" href="?page=1" title="First page">&laquo;</a> <a class="pagination" href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

                $nextlink = ($page < $pages) ? '<a class="pagination" href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a class="pagination" href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
                echo '<div id="paging"><p>', ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results <br>', $prevlink;
                for ($i = 1; $i < $pages + 1; $i++) {
                    if ($page == $i) {
                        echo '<a class="pagination" style="text-decoration: underline; color: white;" href="?page=' . ($i) . '" title="page ' . $i . '">' . $i . '</a>  ';
                    } else {
                        echo '<a class="pagination" href="?page=' . ($i) . '" title="page ' . $i . '">' . $i . '</a>  ';
                    }
                }
                echo $nextlink, ' </p></div>';
                ?>
            </div>
        </div>
    </div>
    </body>
    </html>

    <script>
        function showReport() {
            window.location.href = "/tis/app/views/log.php";
        }

        function run() {
            window.location.href = "/tis/app/views/analyze.php";
        }

        $(".show_info").on("click", function (e) {
            var id = $(this).attr('data-item-id');
            window.location.href = "/tis/app/views/item_index.php?item_id=" + id;
        });

        jQuery("#ls_query").ajaxlivesearch({
            loaded_at: <?php echo time(); ?>,
            token: <?php echo "'" . $handler->getToken() . "'"; ?>,
            max_input: <?php echo Config::getConfig('maxInputLength'); ?>,
            onResultClick: function (e, data) {
                var selectedOne = jQuery(data.selected).find('td').eq('0').text();
                // set the input value
                jQuery('#ls_query').val(selectedOne);
                // hide the result
                jQuery("#ls_query").trigger('ajaxlivesearch:hide_result');
                window.location.href = "/tis/app/views/item_index.php?item_id=" + selectedOne;
            },
            onResultEnter: function (e, data) {
                jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
            }
        });
    </script>


<?php
function destroySessionAndRedirect()
{
    unset($_SESSION['logged_in']);
    session_destroy();
    header("Location: ../../index.php");
}

?>