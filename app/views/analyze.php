<?php
namespace App\Views;
use App\Interactor;

require_once '../../vendor/autoload.php';
require_once '../../bootstrap.php';

$interactor = new Interactor();

if (isset($_POST['back'])){
    echo "something";
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
                    <form method="post" >
                        <input type="submit" value="späť" name="back">
                    </form>
                    <?php if ($interactor->isRunning()): ?>
                        <h2>Parser is already running</h2>
                    <?php else: ?>
                        <h2>Parser successfully started</h2>
                        <?php shell_exec("/usr/bin/php /home/tis/KChVypocty/app/ajax/ajax_handler.php > /dev/null &"); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>