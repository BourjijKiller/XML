<?php
error_reporting(E_ALL ^ E_WARNING);

global $buffer;

if(file_exists("MIAGE.xml")) {
    $xmlFile = simplexml_load_file("MIAGE.xml", "SimpleXMLElement", 0, "http://www.univ-amu.fr/XML/MIAGE");
    getFormations($xmlFile, $buffer);
    try {
        renderView($buffer);
    }
    catch (Exception $exception) {
        die($exception->getMessage());
    }
}
else {
    die("XML File not found");
}

/**
 * @param $xmlFile
 * @param $buffer
 */
function getFormations($xmlFile, &$buffer) : void
{
    registerNSForXPath($xmlFile);
    // Inclusion bootstrap 4.0 css
    constructView($buffer, "head", "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">",
        true, true);
    constructView($buffer, "div class='container'", null, true, false, true);
    constructView($buffer, "div class='jumbotron' style='background-color: rgba(18, 169, 49, 0.4)'", "<h1 class='display-4 text-center'>Les formations en MIAGE</h1><hr style='margin-bottom: unset;'>", true, true);
    // Parcours des formations
    constructView($buffer, "div class='row justify-content-around' id='cardFormations'", null, true, false, true);
    foreachFormations($xmlFile, $buffer);
    // Fermeture de la div contenant les boutons
    constructView($buffer, "div", null, false, true, true);
    foreachFormations($xmlFile, $buffer, true);
    // Fermeture du container
    constructView($buffer, "div", null, false, true, true);
    // Inclusion bootstrap 4.0 js
    constructView($buffer, "script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"", null, true, true, true);
    constructView($buffer, "script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\"crossorigin=\"anonymous\"", null, true, true, true);
}

function foreachFormations($xmlFile, &$buffer, bool $getContentFormations = false) : void
{
    foreach ($xmlFile->xpath(".//miage:formation") as $formations) {
        $niveau = $formations->xpath("@niveau")[0]." ".$formations->xpath("./miage:intitulé")[0];
        $linkFormation = str_replace(' ', '-', $niveau);
        if(strpos($linkFormation, ':')) {
            $linkFormation = substr_replace($linkFormation, '', strpos($linkFormation, ':')-1);
        }
        $getContentFormations ? getContentFormations($buffer, $linkFormation, $formations) : constructView($buffer, "button class='btn btn-success mt-3' data-toggle='collapse' data-target='#$linkFormation' aria-expanded='true' aria-controls='$linkFormation'", $niveau, true, true);
    }
}

function getContentFormations(&$buffer, string $linkSection, SimpleXMLElement $currentElement) : void
{
    $i = 0;
    // Ouverture de la balise div
    constructView($buffer, "div id='$linkSection' class='collapse hide' aria-labelledby='cardFormations'", "<div class='card'><div class='card-header mb-3'><ul class='nav nav-pills' id='pills-tab' role='tablist'>", true, false);
    foreach ($currentElement->xpath(".//miage:semestre") as $semestres) {
        $semestre = $semestres->xpath("@numéro")[0];
        $linkSemestre = $semestre."-".$linkSection;
        $i == 0 ? constructView($buffer, "li class='nav-item'", "<a class=\"nav-link active ml-3\" id=\"$linkSemestre-content\" data-toggle=\"pill\" href=\"#$linkSemestre-link\" role=\"tab\" aria-controls=\"$linkSemestre\" aria-selected=\"true\">$semestre</a>", true, true)
            : constructView($buffer, "li class='nav-item'", "<a class=\"nav-link ml-3\" id=\"$linkSemestre-content\" data-toggle=\"pill\" href=\"#$linkSemestre-link\" role=\"tab\" aria-controls=\"$linkSemestre\" aria-selected=\"false\">$semestre</a>", true, true);
        $i++;
    }
    // Fermeture balise ul
    constructView($buffer, "ul", null, false, true, true);
    // Fermeture de la balise card-header
    constructView($buffer, "div", null, false, true, true);
    // Fermeture de la balise card
    constructView($buffer, "div", null, false, true, true);
    // Fermeture de la balise div
    constructView($buffer, "div", null, false, true, true);
}

/**
 * Enregistre les espaces de nommage du fichier XML pour l'utilisation de XPath
 * @param $xmlFile fichier XML source
 */
function registerNSForXPath($xmlFile) : void
{
    $ns = $xmlFile->getNamespaces();
    foreach ($ns as $pre => $value) {
        $xmlFile->registerXPathNamespace($pre, $value);
    }
}

/**
 * Permet de construire le buffer HTML avec style
 * @param $buffer buffer HTML
 * @param string $balise balise HTML à ajouter
 * @param $data donnée à ajouter dans la balise concernée
 * @param bool $ouvrante si la balise doit être ouvrante
 * @param bool $fermante si la balise doit être fermante
 * @param bool $noData si des données doivent être introduite dans les balises
 */
function constructView(&$buffer, string $balise, $data, bool $ouvrante, bool $fermante, bool $noData = false) : void
{
    if($balise !== "") {
        if ($noData) {
            if ($ouvrante && $fermante) {
                $buffer .= <<<HTML
                    <$balise></$balise>
HTML;
            } else {
                if ($ouvrante) {
                    $buffer .= <<<HTML
                        <$balise>
HTML;
                }
                if ($fermante) {
                    $buffer .= <<<HTML
                        </$balise>
HTML;
                }
            }
        }
        else {
            if($ouvrante && $fermante) {
                $buffer .= <<<HTML
                    <$balise>$data</$balise>
HTML;
            }
            else {
                if($ouvrante) {
                    $buffer .= <<<HTML
                        <$balise>$data
HTML;
                }

                if($fermante) {
                    $buffer .= <<<HTML
                        $data</$balise>
HTML;
                }
            }
        }
    }
}

/**
 * Affiche le contenu du buffer
 * @param $buffer buffer contenant le code HTML
 * @throws Exception Buffer is NULL
 */
function renderView($buffer) : void
{
    if ($buffer != null) {
        echo $buffer;
    }
    else {
        throw new Exception("Buffer is NULL");
    }
}