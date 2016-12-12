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
    <link rel="stylesheet" type="text/css" href="/app/assets/item_style.css">
</head>
<body>
    <table>
        <tr>
            <th>ID</th>
            <td><?= $information->getCalculationID() ?></td>
        </tr>
        <tr>
            <th>Job type</th>
            <td><?= $information->getJobType() ?></td>
        </tr>
        <tr>
            <th>Method</th>
            <td><?= $information->getMethod() ?></td>
        </tr>
        <tr>
            <th>Basis set</th>
            <td><?= $information->getBasisSet() ?></td>
        </tr>
        <tr>
            <th>Stechiometry</th>
            <td><?= $information->getStechiometry() ?></td>
        </tr>
        <tr>
            <th>User</th>
            <td><?= $information->getUser() ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= $information->getDate() ?></td>
        </tr>
        <tr>
            <th>Server</th>
            <td><?= $information->getServer() ?></td>
        </tr>
        <tr>
            <th>Path</th>
            <td><?= $information->getPath() ?></td>
        </tr>
        <tr>
            <th>InfoInput</th>
            <td><?= $information->getInfoInput() ?></td>
        </tr>
        <tr>
            <th>InfoEnd</th>
            <td><?= $information->getInfoEnd() ?></td>
        </tr>
        <tr>
            <th>Energy</th>
            <td><?= $information->getEnergy() ?></td>
        </tr>
        <tr>
            <th>Thermochemistry</th>
            <td><?= $information->getThermoChemistry() ?></td>
        </tr>
    </table>
</body>
</html>