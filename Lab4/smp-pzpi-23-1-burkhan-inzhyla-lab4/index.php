<?php
session_start();

$page = $_GET['page'] ?? '';

switch ($page) {
    case 'login':
        require_once("login.php");
        break;
    default:
        if (!isset($_SESSION['user'])) {
            require_once("page404.php");
            exit;
        }

        switch ($page) {
            case 'cart':
                require_once("cart.php");
                break;
            case 'profile':
                require_once("profile.php");
                break;
            case 'products':
                require_once("products.php");
                break;
            default:
                require_once("page404.php");
                break;
        }
        break;
}

?>
