<?php
namespace App\Views;
require_once '../../vendor/autoload.php';
use App\Presenter;
session_start();
if (!$_SESSION['logged_in']){
    header("Location: ../../index.php");
}
$reports = Presenter::getReports();
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Reports</title>
    </head>
    <body>
        <table>
            <tr>
                <?php foreach ($reports as $report): ?>
                    <td><?= $report->getLogText() ?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    </body>
</html>
