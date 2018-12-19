<?php
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

function getFormations($xmlFile, &$buffer) : void
{
    registerNSForXPath($xmlFile);
    // Inclusion bootstrap 4.0 css
    constructView($buffer, "head", "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">",
        true, true);
    constructView($buffer, "div class='container'", null, true, false, true);
    constructView($buffer, "div class='jumbotron' style='background-color: rgba(18, 169, 49, 0.4)'", "<h1 class='display-4 text-center'>Les formations en MIAGE</h1><hr style='margin-bottom: unset;'>", true, true);
    // Parcours des formations
    foreach ($xmlFile->xpath(".//miage:formation") as $formations) {
        $niveau = $formations->xpath("@niveau")[0]." ".$formations->xpath("./miage:intitulé")[0];
    }

    constructView($buffer, "div", null, false, true, true);
    // Inclusion bootstrap 4.0 js
    constructView($buffer, "script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"", null, true, true, true);
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