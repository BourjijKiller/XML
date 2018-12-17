<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samuel Entezam
 * Date: 17/12/2018
 * Time: 08:58
 */

use App\main\catalogue\SAXParserCatalogue;
require 'vendor/autoload.php';

switch ($_SERVER['REQUEST_URI']) {
    case "/catalogue":                                             
        $parser = xml_parser_create('UTF-8');
        try {
            $saxParser = new SAXParserCatalogue($parser, "catalogue.xml", "src/main/catalogue/");
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
        break;
    default:
        die("Route not defined in project");
        break;
}