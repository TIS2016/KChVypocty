<?php
namespace App\Views;
session_start();

require_once __DIR__ . '/vendor/autoload.php';
use App\DoctrineSetup;

turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

$em = DoctrineSetup::getEntityManager();
$query = $em->createQueryBuilder()->select('u.login, u.password')
    ->from('App\Db\User', 'u')
    ->where('u.login = ?1 and u.password = ?2')
    ->setParameter(1, $_POST['name'])
    ->setParameter(2, $_POST['password'])
    ->getQuery();
$users = $query->getResult();
if (sizeof($users) > 0) {
    $_SESSION['logged_in'] = true;
    unset($_POST['name']);
    unset($_POST['password']);
}

if (isset($_SESSION['logged_in'])){
    header("Location: app/views/table_index.php");
} else {
    header("Refresh");
}

?>
<!DOCTYPE>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="app/assets/login_style.css">

    <link rel="stylesheet" type="text/css" href="../ajaxlivesearch/css/ajaxlivesearch.min.css">
    <script src="../ajaxlivesearch/js/ajaxlivesearch.min.js"></script>
</head>
<body>
<form method="post">
    <input type="text" name="name" placeholder="Name">
    <br>
    <input type="password" name="password" placeholder="Enter password">
    <input type="submit" name="submit" value="&#10148;">
</form>
</body>
</html>



