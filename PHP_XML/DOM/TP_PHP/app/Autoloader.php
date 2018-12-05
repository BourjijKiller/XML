<?php
/**
 * Created by IntelliJ IDEA.
 * User: Entezam Samuel
 * Date: 03/12/2018
 * Time: 11:46
 */

namespace TP_PHP\app;

/**
 * Class Autoloader
 * @package TP_PHP\app
 */
class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class)
    {
        $parts = preg_split('#\\\#', $class);
        $className = array_pop($parts);
        switch($className) {
            case 'Controller':
                require_once __DIR__.'/../core/'.$className.'.php';
                break;
            case 'Collection':
                require_once  __DIR__ . '/../utils/' .$className.'.php';
                break;
            default:
                require_once __DIR__.'/../src/'.$className.'/'.$className.'.php';
                break;
        }
    }
}