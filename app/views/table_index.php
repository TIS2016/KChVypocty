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


$handler = new Handler();

$handler->getJavascriptAntiBot();
$token = $handler->getToken();

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
    <form>
        <select>
            <option value="">Select job type</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select method</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select basis set</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select stechiometry</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select user</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select date</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select server</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>
        <select>
            <option value="">Select path</option>
            <option value="B">B</option>
            <option value="L">L</option>
            <option value="A">A</option>
        </select>

        <br>
        <br>

        <input type="text" class='mySearch' id="ls_query" placeholder="Type to start searching ...">

    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Job type</th>
            <th>Method</th>
            <th>Basis set</th>
            <th>Stechiometry</th>
            <th>User</th>
            <th>Date</th>
            <th>Server</th>
            <th>Path</th>
        </tr>
        <tr>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
        </tr>
        <tr>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
        </tr>
        <tr>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
            <td>B</td>
            <td>L</td>
            <td>A</td>
        </tr>
    </table>
</body>
</html>

<script>
    jQuery("#ls_query").ajaxlivesearch({
        loaded_at: <?php echo time(); ?>,
        token: <?php echo "'" . $handler->getToken() . "'"; ?>,
        max_input: <?php echo Config::getConfig('maxInputLength'); ?>,
        onResultClick: function(e, data) {
            var selectedOne = jQuery(data.selected).find('td').eq('1').text();

            // set the input value
            jQuery('#ls_query').val(selectedOne);

            // hide the result
            jQuery("#ls_query").trigger('ajaxlivesearch:hide_result');
        },
        onResultEnter: function(e, data) {
            // do whatever you want
            // jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
        },
        onAjaxComplete: function(e, data) {

        }
    });
</script>