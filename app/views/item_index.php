<?php
namespace App\Views;
require_once '../../vendor/autoload.php';
use App\Presenter;

turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

$id = 0;
if (isset($_GET['item_id']))
    $id = $_GET['item_id'];

$information = Presenter::getCalculationData($id);
?>

<!DOCTYPE>
<html>
<head>
    <title>Item</title>
    <link rel="stylesheet" type="text/css" href="/tis/app/assets/bootstrap/css/bootstrap.css">
    <script type="text/javascript" src="/tis/app/assets/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="/tis/app/assets/item_style.css">

</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <table>
                <tr>
                    <th>ID</th>
                    <td class="text-wrap"><?= $information->getCalculationID() ?></td>
                </tr>
                <tr>
                    <th>Job type</th>
                    <td class="text-wrap"><?= $information->getJobType() ?></td>
                </tr>
                <tr>
                    <th>Method</th>
                    <td class="text-wrap"><?= $information->getMethod() ?></td>
                </tr>
                <tr>
                    <th>Basis set</th>
                    <td class="text-wrap"><?= $information->getBasisSet() ?></td>
                </tr>
                <tr>
                    <th>Stechiometry</th>
                    <td class="text-wrap"><?= $information->getStechiometry() ?></td>
                </tr>
                <tr>
                    <th>User</th>
                    <td class="text-wrap"><?= $information->getUser() ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td class="text-wrap"><?= $information->getDate() ?></td>
                </tr>
                <tr>
                    <th>Server</th>
                    <td class="text-wrap"><?= $information->getServer() ?></td>
                </tr>
                <tr>
                    <th>Path</th>
                    <td class="text-wrap"><?= $information->getPath() ?></td>
                </tr>

                <tr>
                    <th>InfoInput</th>
                    <td class="text-wrap"><?= $information->getInfoInput() ?></td>
                </tr>
                <tr>
                    <th>InfoEnd</th>
                    <td class="text-wrap"><?= $information->getInfoEnd() ?></td>
                </tr>
                <tr>
                    <th>Energy</th>
                    <td class="text-wrap"><?= $information->getEnergy() ?></td>
                </tr>
                <tr>
                    <th>Thermochemistry</th>
                    <td class="text-wrap"><?= $information->getThermoChemistry() ?></td>
                </tr>

            </table>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h2>Náhľad</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="insight">
                    </div>
                </div>
            </div>

        </div>

    </div>



    </div>




</div>

</body>
</html>