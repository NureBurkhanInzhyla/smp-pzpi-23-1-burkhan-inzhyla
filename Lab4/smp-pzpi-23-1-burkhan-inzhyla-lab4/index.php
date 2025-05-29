<?php
    session_start();

    $page = $_GET['page'] ?? '';

    $publicPages = ['login'];
    $privatePages = ['cart', 'profile', 'products'];

    if (in_array($page, $publicPages)) {
        require_once("$page.php");
    } elseif (isset($_SESSION['user']) && in_array($page, $privatePages)) {
        require_once("$page.php");
    } else {
        require_once("page404.php");
    }


?>
