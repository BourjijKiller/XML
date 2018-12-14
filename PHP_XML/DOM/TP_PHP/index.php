<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 03/12/2018
 * Time: 11:27
 */

namespace TP_PHP;
use TP_PHP\app\Autoloader;
use TP_PHP\src\Catalogue\Catalogue;
use TP_PHP\src\MIAGE\MIAGE;

/**
 * App entry
 */

include __DIR__.'/app/Autoloader.php';
Autoloader::register();

error_reporting(E_ALL ^ E_WARNING);

if(!isset($_GET['section']) || empty($_GET['section'])) {
    $url = $_SERVER['HTTP_HOST'];
    echo "<div style='text-align: center; font-size: 50px; color: #ff1822;'> Usage : ".$url."/index.php?section=[catalogue | MIAGE | ...]</div>";
    die();
}

switch ($_GET['section'])
{
    case 'catalogue':
        try {
            $controller = new Catalogue();
            $controller->view();
        }
        catch (\Exception $e) {
            die($e->getMessage());
        }
        break;

    case 'MIAGE':
        try {
            $controller = new MIAGE();
            $controller->renderView();
        }
        catch (\Exception $e) {
            die($e->getMessage());
        }
        break;

    default:
        header('Location: index.php');
        break;
}