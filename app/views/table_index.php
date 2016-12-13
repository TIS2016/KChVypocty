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
    <link rel="stylesheet" type="text/css" href="../assets/table_style.css">
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ajaxlivesearch.min.js"></script>


</head>
<body>
<!--    <form>-->
<!--        <select>-->
<!--            <option value="">Select job type</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select method</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select basis set</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select stechiometry</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select user</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select date</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select server</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!--        <select>-->
<!--            <option value="">Select path</option>-->
<!--            <option value="B">B</option>-->
<!--            <option value="L">L</option>-->
<!--            <option value="A">A</option>-->
<!--        </select>-->
<!---->
<!--        <br>-->
<!--        <br>-->
<!---->
<!--      -->
<!---->
<!--    </form>-->
    <input type="text" class='mySearch' id="ls_query" placeholder="Type to start searching ...">
    <table>
        <?php foreach ($data as /* @var Calculation */ $calculation ): ?>
            <tr>
                <th><?= $calculation->getCalculationID() ?></th>
                <th><?= $calculation->getJobType() ?></th>
                <th><?= $calculation->getMethod() ?></th>
                <th><?= $calculation->getBasisSet() ?></th>
                <th><?= $calculation->getStechiometry() ?></th>
                <th><?= $calculation->getUser() ?></th>

                <th><?= $calculation->getDate()?></th>
                <th><?= $calculation->getServer()  ?></th>
                <th><?= $calculation->getPath() ?></th>
                <th><input data-item-id="<?= $calculation->getCalculationID() ?>" class="show_info" type="button" value="Show details"></th>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

<script>

    function sendAjaxRequest(callBackFunction, data) {
        var requestPath = "/app/ajax/ajax_handler.php";
        var responseData = "";

        $.ajax({
            url: requestPath,
            type: 'post',
            dataType: 'json',
            success: function (data) {
                callBackFunction(data);
            },
            data: {
                calculation_id: data.calculation_id
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

            window.location.href = "/app/views/item_index.php?item_id=" + selectedOne;
        },
        onResultEnter: function(e, data) {
             jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
        },
        onAjaxComplete: function(e, data) {

        }
    });

    $(".show_info").on("click", function (e) {
        var id = $(this).attr('data-item-id');
        window.location.href = "/app/views/item_index.php?item_id=" + id;
    });
</script>