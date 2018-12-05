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

include __DIR__.'/app/Autoloader.php';
Autoloader::register();

if(!isset($_GET['section']) || empty($_GET['section'])) {
    $url = $_SERVER['HTTP_HOST'];
    die("Format URL demandé : ".$url. "/index.php?section=...");
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

    default:
        header('Location: index.php');
        break;
}