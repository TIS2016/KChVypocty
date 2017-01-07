<?php
namespace App\Views;
require_once '../../vendor/autoload.php';
use App\DoctrineSetup;
use App\Presenter;
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

session_start();
if (!$_SESSION['logged_in']) {
    header("Location: ../../index.php");
}

$entityManager = DoctrineSetup::getEntityManager();
$dql = "SELECT logs FROM \App\Db\Logs logs ORDER BY logs.logID DESC";
$logs = $entityManager->createQuery($dql)
    ->setMaxResults(1)
    ->getResult();


$i = 1;

if (isset($_POST['back'])){
    header("Location: table_index.php");
}
?>

<!DOCTYPE>
<html>
<head>
    <title>Item</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/tis/app/assets/bootstrap/css/bootstrap.css">
    <script type="text/javascript" src="/tis/app/assets/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="/tis/app/assets/item_style.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h2>Log from last full run</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" >
                        <input type="submit" value="späť" name="back">
                    </form>
                    <table class="info-table">
                        <?php foreach ($logs as $log): ?>
                            <?php foreach (explode("!", $log->getLogText()) as $row): ?>
                                <tr>
                                    <th><?= $i ?></th>
                                    <?php $i++; ?>
                                    <td class="text-wrap"><?= $row ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>